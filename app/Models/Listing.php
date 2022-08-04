<?php

namespace App\Models;

use App\Library\Contracts\Listing as ListingContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;

class Listing extends Model implements ListingContract
{
    use HasFactory;

    private Connection $storage;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->storage = Redis::connection();
    }


    protected $fillable = [
        'title',
        'company',
        'location',
        'website',
        'email',
        'tags',
        'description',
        'logo'
    ];

    /**
     * @param $query
     * @param array $filters
     * @return void
     */
    public function scopeFilter($query, array $filters)
    {
        if ($filters['tag'] ?? false) {
            $query->where('tags', 'like', '%' . request('tag') . '%');
        }
        if ($filters['search'] ?? false) {
            $query->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%')
                ->orWhere('tags', 'like', '%' . request('search') . '%');
        }
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Models\User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function fetchAll()
    {
        return cache()->remember('listings_cache', 60, function () {
            return $this->latest()->filter(request(['tag', 'search']))->paginate(10);
        });
    }

    public function fetch($id)
    {

        $this->storage->pipeLine(function ($pipe) use ($id) {
            $pipe->zIncrBy('listingViews', 1, 'Listing:' . $id);
            $pipe->incr('Listing' . $id . ':views');

        });
        return $this->where('id', $id)->first();
    }

    public function getListingViews($id)
    {
        return $this->storage->get('article:' . $id . ':views');
    }

    public function getViews()
    {

    }


}
