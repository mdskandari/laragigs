@props(['listing'])

<x-card>
    <div class="flex">
        <img
            class="hidden h-40 w-48 mr-6 md:block"
            src="{{$listing->logo? asset('storage/'.$listing->logo) : asset('images/no-image.png')}}"
            alt=""
        />
        <div>
            <h3 class="text-2xl">
                <a href="/listing/{{$listing->id}}">{{$listing->title}}</a>
            </h3>
            <div class="text-xl font-bold mb-4">{{$listing->company}}</div>
            <x-listing-tags :tagsCsv="$listing->tags"/>
            <div class="text-lg mt-4">
                <i class="fa-solid fa-location-dot"></i> {{$listing->location}}
            </div>
            <div class="text-sm">
                <i class="fa-solid fa-pencil"></i> author:  {{$listing->user->name}}
            </div>
        </div>
    </div>
</x-card>
