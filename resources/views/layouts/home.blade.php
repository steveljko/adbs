<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta
            content="width=device-width, initial-scale=1.0"
            name="viewport"
        >
        <title>adbs</title>
        <link
            href="https://fonts.googleapis.com"
            rel="preconnect"
        >
        <link
            crossorigin
            href="https://fonts.gstatic.com"
            rel="preconnect"
        >
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
            rel="stylesheet"
        >
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body
        class="bg-[#fcfcfc]"
        hx-ext="sse"
        hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
    >
        <x-htmx-error-handler />

        <x-modal />
        <x-bottombar />

        <div>
            @yield('content')
        </div>

        <script>
            window.userId = {{ auth()->id() ?? 'null' }}
        </script>
    </body>

</html>
