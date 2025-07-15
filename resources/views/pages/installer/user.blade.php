@extends('layouts.installer')

@section('header')
<div></div>
@endsection

@section('content')
<div class="w-full" id="content">
    <form hx-post="{{ route('installer.user.create') }}" hx-swap="none" class="space-y-6">
        <x-form.input name="name" label="Name" type="text"></x-form.input>
        <x-form.input name="email" label="Email Address" type="email"></x-form.input>
        <x-form.input name="password" label="Password" type="password"></x-form.input>
        <x-form.input name="password_confirmation" label="Confirm Password" type="password"></x-form.input>

        <div class="flex justify-between">
            <x-button hx-get="{{ route('installer.user.skip') }}" variant="secondary" type="button">Skip</x-button>
            <x-button type="submit">Create User</x-button>
        </div>
    </form>
</div>
@endsection
