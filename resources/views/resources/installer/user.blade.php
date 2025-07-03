@extends('layouts.installer')

@section('content')
<div id="content">
    <h1 class="text-2xl text-center">User Creation</h1>

    <form hx-post="{{ route('installer.user.create') }}" hx-swap="none" class="space-y-6">
        @csrf

        <x-form.input name="name" label="Name" type="text"></x-form.input>
        <x-form.input name="email" label="Email Address" type="email"></x-form.input>
        <x-form.input name="password" label="Password" type="password"></x-form.input>
        <x-form.input name="password_confirmation" label="Confirm Password" type="password"></x-form.input>

        <div class="flex space-x-4">
            <x-button hx-get="{{ route('installer.user.skip') }}" class="w-full bg-gray-500"
                type="button">Skip</x-button>
            <x-button class="w-full" type="submit">Create User</x-button>
        </div>
    </form>
</div>
@endsection
