@php
$step = [
route('installer.welcome') => 0,
route('installer.requirements') => 1,
route('installer.database') => 2,
route('installer.user') => 3,
];

$currentStep = array_search(url()->current(), array_keys($step));
@endphp
<div class="hidden sm:block">
    <ul class="relative space-y-8">
        <div class="absolute left-2.5 top-8 bottom-0 w-0.5 bg-gray-200"></div>

        @foreach ($step as $route => $index)
        <li class="relative">
            <div class="flex items-center">
                <div class="relative z-10 flex items-center justify-center w-6 h-6 rounded-full
                    {{ $index <= $currentStep ? 'bg-orange-500' : 'bg-gray-300' }}
                    border-2 border-white shadow-lg">
                    @if ($index < $currentStep) <i class="fa fa-check text-white text-xs"></i>
                        @elseif ($index == $currentStep)
                        <span class="text-white text-xs font-bold">{{ $index + 1 }}</span>
                        @else
                        <span class="text-gray-600 text-xs font-bold">{{ $index + 1 }}</span>
                        @endif
                </div>
                <span class="ml-4 font-semibold {{ $index <= $currentStep ? 'text-gray-800' : 'text-gray-500' }}">
                    {{ $index == 0 ? 'Welcome' : ($index == 1 ? 'Check Extensions Availability' : ($index == 2 ?
                    'Database Connection' : 'User Creation')) }}
                </span>
            </div>
            @if ($index < count($step) - 1) <div class="absolute left-2.5 top-8 h-8 w-0.5 bg-gray-200">
</div>
@endif
</li>
@endforeach
</ul>
</div>
