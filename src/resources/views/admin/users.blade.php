@extends('layouts.app')

@section('title', 'Admin - Users')
@section('page-title', 'User Management')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
@endpush

@section('content')

{{-- Page Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Admin Panel</h1>
        <p class="text-sm text-gray-400 mt-0.5">{{ now()->format('l, d F Y') }}</p>
    </div>
    <a href="{{ route('admin.export') }}"
       class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Export CSV
    </a>
</div>

{{-- Horizontal Admin Nav --}}
<nav class="flex items-center gap-1 mb-6 bg-white border border-gray-200 rounded-xl p-1 w-fit">
    @php
        $navLinks = [
            ['route' => 'admin.index',    'label' => 'Overview',     'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
            ['route' => 'admin.users',    'label' => 'Users',        'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ['route' => 'admin.tasks',    'label' => 'All Tasks',    'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['route' => 'admin.category','label' => 'Category',     'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
            ['route' => 'admin.activity','label' => 'Activity Log', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
    @endphp

    @foreach($navLinks as $nav)
    <a href="{{ route($nav['route']) }}"
       class="flex items-center gap-2 px-3.5 py-2 rounded-lg text-sm font-medium transition-colors
              {{ request()->routeIs($nav['route'])
                  ? 'bg-gray-900 text-white'
                  : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $nav['icon'] }}"/>
        </svg>
        {{ $nav['label'] }}
    </a>
    @endforeach
</nav>

{{-- Section Header + Search --}}
<div class="flex items-center justify-between mb-4">
    <div>
        <p class="text-[11px] font-semibold tracking-widest uppercase text-gray-400">All Users</p>
        <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $users->total() }} users registered</p>
    </div>

    <form method="GET" action="{{ route('admin.users') }}" class="flex items-center gap-2">
        <div class="relative">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search name or email..."
                class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 w-56 bg-white"
            >
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <button type="submit" class="px-3.5 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
            Search
        </button>
        @if(request('search'))
            <a href="{{ route('admin.users') }}" class="px-3.5 py-2 text-xs font-semibold border border-gray-200 rounded-xl text-gray-500 hover:bg-gray-50">
                Clear
            </a>
        @endif
    </form>
</div>

{{-- Flash message --}}
@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl">
        {{ session('success') }}
    </div>
@endif

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-5 py-3">User</th>
                <th class="text-left text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-5 py-3">Role</th>
                <th class="text-left text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-5 py-3">Status</th>
                <th class="text-left text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-5 py-3">Tasks</th>
                <th class="text-left text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-5 py-3">Joined</th>
                <th class="text-left text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-5 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition-colors">

                {{-- User --}}
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg shrink-0 flex items-center justify-center text-xs font-bold [font-family:'JetBrains_Mono',monospace]
                                    {{ $user->role === 'admin' ? 'bg-blue-600 text-white' : 'bg-gray-900 text-white' }}">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400 [font-family:'JetBrains_Mono',monospace]">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>

                {{-- Role --}}
                <td class="px-5 py-3.5">
                    @if($user->role === 'admin')
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 tracking-wide">admin</span>
                    @else
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-lg bg-gray-100 text-gray-500 tracking-wide">user</span>
                    @endif
                </td>

                {{-- Status --}}
                <td class="px-5 py-3.5">
                    @if($user->is_banned)
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-lg bg-red-50 text-red-500 tracking-wide">banned</span>
                    @else
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-600 tracking-wide">active</span>
                    @endif
                </td>

                {{-- Tasks --}}
                <td class="px-5 py-3.5">
                    <span class="text-sm [font-family:'JetBrains_Mono',monospace] text-gray-600">{{ $user->todos_count }}</span>
                </td>

                {{-- Joined --}}
                <td class="px-5 py-3.5">
                    <span class="text-xs [font-family:'JetBrains_Mono',monospace] text-gray-400">{{ $user->created_at->format('d M Y') }}</span>
                </td>

                {{-- Actions --}}
                <td class="px-5 py-3.5">
                    @if($user->id !== auth()->id())
                        @if($user->is_banned)
                            <form action="{{ route('admin.users.unban', $user->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="text-[10px] font-semibold px-2.5 py-1 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors">
                                    Unban
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.users.ban', $user->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="text-[10px] font-semibold px-2.5 py-1 rounded-lg border border-red-100 text-red-500 hover:bg-red-50 transition-colors">
                                    Ban
                                </button>
                            </form>
                        @endif
                    @else
                        <span class="text-xs text-gray-300">—</span>
                    @endif
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">
                    No users found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="px-5 py-3.5 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection