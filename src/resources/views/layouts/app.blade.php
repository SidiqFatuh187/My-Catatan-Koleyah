<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ToDoApp')</title>
    @vite(['resources/css/app.css', 'resources/js/layoutsApp.js'])
</head>
<body class="bg-gray-50 min-h-screen flex">

    @include('layouts.sidebar')

    <div class="flex-1 ml-64 flex flex-col min-h-screen">
        @include('layouts.navbar')

        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>