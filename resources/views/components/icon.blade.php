@php
$iconPath = resource_path("views/components/icons/{$name}.blade.php");
@endphp

@if (file_exists($iconPath))
<svg xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor"
    viewBox="{{ $viewBox ?? '0 0 24 24' }}">
    @include("components.icons.{$name}")
</svg>
@endif
