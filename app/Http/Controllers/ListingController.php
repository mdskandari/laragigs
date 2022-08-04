<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;
use MongoDB\Driver\Session;
use phpDocumentor\Reflection\DocBlock\Tag;

class ListingController extends Controller
{
    private Connection $redis;
    private Listing $listing;

    public function __construct(Listing $listing)
    {
        $this->listing = $listing;
        $this->redis = Redis::connection();
    }


    /**
     * @return View
     */
    public function index()
    {
        $listings = $this->listing->fetchAll();
        return view('listings.index',
            [
                'listings' => $listings
            ]);
    }


    /**
     * @param Listing $listing
     * @return View
     */
    public function show(Listing $listing)
    {
        $views = $this->redis->incr('Listing' . $listing->id . ':views');
        return view('listings.show', ['listing' => $listing, 'noViews' => $views]);
    }

    public function create()
    {
        $this->authorize('create', Listing::class);
        return view('listings.create');
    }


    public function store()
    {
        $formFileds = request()->validate([
            'title' => 'required|',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);
        if (request()->hasFile('logo')) {
            $formFileds['logo'] = request()->file('logo')
                ->store('logos', 'public');
        }

        $formFileds['user_id'] = auth()->id();

        Listing::create($formFileds);

        return redirect('/')->with('message', 'Listing Created Successfully');
    }


    public function edit(Listing $listing)
    {
        try {
            $this->authorize('update', $listing);

            return view('listings.edit', ['listing' => $listing]);

        } catch (AuthorizationException $e) {
            return back()->with('message', 'You are not authorized to edit this Listing');
        }
    }

    public function update(Listing $listing)
    {
        try {
            $this->authorize('update', $listing);

            $formFileds = request()->validate([
                'title' => 'required|',
                'company' => ['required'],
                'location' => 'required',
                'website' => 'required',
                'email' => ['required', 'email'],
                'tags' => 'required',

                'description' => 'required'
            ]);
            if (request()->hasFile('logo')) {
                $formFileds['logo'] = request()->file('logo')
                    ->store('logos', 'public');
            }

            $listing->update($formFileds);

            return back()->with('message', 'Listing Updated Successfully!');

        } catch (AuthorizationException $e) {
            return back()->with('message', 'You are not authorized to edit this Listing');
        }


    }


    public function destroy(Listing $listing)
    {
        try {
            $this->authorize('delete', $listing);
//            if (auth()->user()->can('delete-listing'))
            $listing->delete();
            return redirect('/')->with('message', 'Listing Deleted Successfully!');

        } catch (AuthorizationException $e) {
            return back()->with('message', 'You are not authorized to delete this Listing');
        }

    }


    public function manage()
    {
        if (auth()->user()->isAdministrator()) {
            $listings = Listing::all();
        } else {
            $listings = auth()->user()->listings()->get();
        }

        return view('listings.manage', ['listings' => $listings]);
    }


    private function checkIfAuthorized(string $ability, object $object)
    {
        try {
            $this->authorize($ability, $object);
        } catch (AuthorizationException $e) {
            return false;
        }
        return true;
    }
}
