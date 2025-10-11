@extends('layouts.home')

@section('content')
    <div class="mx-auto max-w-7xl gap-4 px-4 py-8 sm:px-6 lg:px-8">
        <div class="w-full space-y-4">
            @include('partials.settings.tags')

            @include('partials.settings.view-type')

            @include('partials.settings.import-export')

            @fragment('clients')
                @include('partials.settings.clients')
            @endfragment

            <div class="h-16"></div>
        </div>
    </div>
@endsection
