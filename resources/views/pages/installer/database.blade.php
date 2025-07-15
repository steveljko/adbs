@extends('layouts.installer')

@section('header')
<div></div>
@endsection

@section('content')
<div class="flex-1 max-w-full">
    <form hx-post="{{ route('installer.database.setup') }}" hx-swap="none" hx-include="#db_driver">
        <x-form.select id="db_driver" name="db_driver" label="Database Driver" placeholder="Database Driver"
            :options="$options" :value="$selectedDriver ?? ''" hx-get="{{ route('installer.database.select') }}"
            hx-include="#db_driver" hx-target="#fcontent" hx-swap="outerHTML" />

        <div id="fcontent"></div>

        <div class="flex justify-between items-center mt-6">
            <x-button href="{{ route('installer.requirements') }}" variant="secondary">Back</x-button>
            <x-button type="submit" id="submit" disabled>Test & Save Configuration</x-button>
        </div>
    </form>
</div>
@endsection
