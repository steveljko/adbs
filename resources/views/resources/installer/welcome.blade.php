@extends('layouts.installer')

@section('content')
<div id="content">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Welcome to the Installation Wizard</h1>
        <p class="text-gray-600 text-md">Let's get your application up and running in just a few simple steps.</p>
    </div>

    <form hx-post="{{ route('installer.welcome.next') }}" class="max-w-md mx-auto">
        <div class="mb-6">
            <x-form.input label="Application URL" name="url" type="url" :value="config('app.url')" />
        </div>
        <div class="flex">
            <button type="submit"
                class="py-3 px-6 w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Continue Setup
            </button>
        </div>
    </form>
</div>
@endsection
