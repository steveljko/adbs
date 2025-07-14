@extends('layouts.installer')

@section('header')
<div class="text-center mb-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-3">Welcome to the Installation Wizard</h1>
    <p class="text-gray-600 text-lg">Let's get your application up and running in just a few simple steps.</p>
</div>
@endsection

@section('content')
<div class="flex-1 max-w-full" id="content">
    <form hx-post="{{ route('installer.welcome.next') }}" class="space-y-6">
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Application Configuration</h3>
            <div class="space-y-4">
                <x-form.input label="Application URL" name="url" type="url" :value="config('app.url')"
                    placeholder="https://your-domain.com" />
                <div class="text-sm text-gray-600">
                    <p>This URL will be used for generating links and configuring your application.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit"
                class="px-8 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transform hover:scale-105 shadow-lg hover:shadow-xl">
                Continue Setup
                <i class="fa fa-arrow-right ml-2"></i>
            </button>
        </div>
    </form>
</div>
@endsection
