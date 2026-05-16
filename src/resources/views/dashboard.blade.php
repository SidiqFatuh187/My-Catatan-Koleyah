<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDoApp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="max-w-md w-full mx-auto p-6">

        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-indigo-600 mb-2">ToDoApp</h1>
            <p class="text-gray-500">Tailwind v4 is working! 🎉</p>

            {{-- Logout --}}
            <form action="{{ route('logout') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">
                    Logout
                </button>
            </form>
        </div>
        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">My Tasks</h2>

            {{-- Todo Item --}}
            <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 mb-2">
                <input type="checkbox" class="w-5 h-5 accent-indigo-600">
                <span class="text-gray-700">Setup Docker ✅</span>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 mb-2">
                <input type="checkbox" class="w-5 h-5 accent-indigo-600">
                <span class="text-gray-700">Install Laravel 13</span>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50">
                <input type="checkbox" class="w-5 h-5 accent-indigo-600">
                <span class="text-gray-700">Setup Tailwind v4</span>
            </div>
        </div>

        {{-- Button --}}
        <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition-colors duration-200">
            + Add New Task
        </button>

    </div>

</body>
</html>