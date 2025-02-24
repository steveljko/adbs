@extends('layouts.default')

@section('content')
    <div class="w-full h-screen block md:flex justify-center items-center">
        <div class="w-full px-4 md:px-0 md:w-1/2 lg:w-1/3 mx-auto">
            @fragment('form')
                <form hx-post="{{ route('auth.login') }}">
                    <x-form.input name="email" label="Email Address" type="text" :error="$errors->first('email')" />
                    <x-form.input name="password" label="Password" type="password" :error="$errors->first('password')" />
                    <div class="flex justify-end">
                        <button type="submit" class="px-3 rounded text-white py-1.5 bg-orange-500">Sign In</button>
                    </div>
                </form>
            @endfragment
        </div>
    </div>
@endsection
