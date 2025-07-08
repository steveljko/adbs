@extends('layouts.home')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h3>Settings</h3>

    <div class="space-y-4">
        @include('partials.settings.tags')

        @include('partials.settings.import-export')
    </div>
</div>
@endsection
