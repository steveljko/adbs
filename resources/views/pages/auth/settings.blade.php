@extends('layouts.home')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-4">
        @include('partials.settings.tags')

        @include('partials.settings.import-export')

        @fragment('clients')
        @include('partials.settings.clients')
        @endfragment

        <div class="h-16"></div>
    </div>
</div>
@endsection
