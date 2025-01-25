<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Order Cycle</title>
    @vite('resources/css/app.css')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite('resources/js/app.js')
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
