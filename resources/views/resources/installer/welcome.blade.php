@extends('layouts.installer')

@section('content')
<div id="content">
    <h1 class="text-2xl text-center">Welcome to Installer!</h1>
    <div class="flex">
        <button hx-get="{{ route('installer.requirements') }}" hx-target="#content" class="py-3 w-full bg-orange-500 text-white rounded">Start Wizard</button>
    </div>
</div>
@endsection
