@extends('layouts.installer')

@section('header')
<div></div>
@endsection

@section('content')
<div class="flex-1 max-w-full" id="content">
    <div>
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-semibold">Check Extensions Availability</h3>
            <span class="block text-gray-600 text-sm bg-gray-100 font-semibold px-2 py-1 rounded-full">{{ $phpVersion
                }}</span>
        </div>
        <div class="grid md:grid-cols-2 gap-3">
            @foreach($extensions as $extension => $details)
            <div class="flex items-center justify-between p-3 bg-white rounded border">
                <span class="font-medium">{{ $extension }}</span>
                <div class="flex items-center">
                    @if($details['satisfied'])
                    <x-icon name="check" class="w-4 h-4 stroke-2 text-green-500" />
                    @else
                    <x-icon name="x" class="w-4 h-4 stroke-2 text-red-500" />
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="flex justify-end mt-6">
        <x-button hx-get="{{ url()->current() }}">Next</x-button>
    </div>
</div>
@endsection
