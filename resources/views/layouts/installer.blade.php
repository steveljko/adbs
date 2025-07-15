<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>adbs</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
    class="block sm:flex items-center justify-center h-screen bg-[#fcfcfc]">
    <x-htmx-error-handler />

    <div class="w-full md:w-10/12 lg:w-8/12 mx-auto bg-white p-6 shadow rounded-lg">
        @yield('header')

        <div class="w-full flex gap-12 items-start">
            <x-steps class="hidden" />

            @yield('content')
        </div>

        <!-- Installation Footer -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-center text-sm text-gray-500">
                <i class="fa fa-info-circle mr-2"></i>
                <span>This setup wizard will guide you through the installation process step by step.</span>
            </div>
        </div>
    </div>
</body>

</html>
