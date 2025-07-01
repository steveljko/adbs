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

<body hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}' class="bg-[#fcfcfc]">
    <x-htmx-error-handler />

    <div class="w-1/3 mx-auto bg-white shadow">
        @yield('content')
    </div>
</body>

</html>
