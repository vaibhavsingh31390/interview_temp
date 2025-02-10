<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Order Cycle</title>


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @vite('resources/css/app.css')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @vite('resources/js/app.js')

    <style>
        body {
            overflow-x: hidden;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-selection {
            width: 100% !important;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">

    <nav class="bg-blue-500 p-4">
        <div class="container mx-auto">
            <a href="/" class="text-white text-lg font-semibold">User Order Cycle</a>
        </div>
    </nav>

    <div class="container mx-auto mt-6">
        @yield('content')
    </div>

</body>

</html>
