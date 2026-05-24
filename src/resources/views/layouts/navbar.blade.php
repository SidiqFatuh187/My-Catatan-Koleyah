<header class="bg-white border-b border-gray-100 px-6 py-3.5 flex items-center justify-between sticky top-0 z-20">

    {{-- Hamburger (mobile) --}}
    <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700 md:hidden">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    {{-- Page Title --}}
    <h2 class="text-base font-semibold text-gray-700">@yield('page-title', 'ToDoApp')</h2>

    {{-- Right --}}
    <div class="flex items-center gap-4">

   {{-- Search --}}
    <div class="relative" id="search-wrapper">
        <form action="{{ route('todo.index') }}" method="GET" autocomplete="off">
            <input
                type="text"
                id="navbar-search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search tasks..."
                class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 w-48 bg-gray-50"
            >
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </form>

        {{-- Dropdown suggest --}}
        <div id="search-dropdown"
            class="hidden absolute top-full left-0 mt-1.5 w-72 bg-white rounded-xl border border-gray-100 shadow-lg z-50 overflow-hidden">
            <div id="search-results" class="py-1"></div>
        </div>
    </div>

        {{-- Icons --}}
        <button class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
            </svg>
        </button>

        <button class="relative text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </button>

        <button class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </button>

        {{-- User --}}
        <div class="flex items-center gap-2 pl-3 border-l border-gray-100">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</p>
            </div>
            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold text-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>

    </div>
</header>