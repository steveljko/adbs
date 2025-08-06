@extends('layouts.default')

@section('content')
    <div class="block h-screen w-full items-center justify-center md:flex">
        <div class="mx-auto w-full px-4 md:w-1/2 md:px-0 lg:w-1/3">
            <form hx-post="{{ route('auth.login.execute') }}">
                <x-form.input
                    label="Email Address"
                    name="email"
                    type="text"
                />
                <x-form.input
                    label="Password"
                    name="password"
                    type="password"
                />
                <div class="flex justify-end">
                    <x-button type="submit">Sign In</x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
