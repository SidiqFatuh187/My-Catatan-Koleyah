@extends('layouts.app')

@section('page-title', 'Admin Panel')

@section('content')
<div class="flex h-screen bg-gray-50">

    {{-- Sidebar --}}
    <aside class="w-52 bg-white border-r border-gray-100 flex flex-col py-4 px-3 shrink-0">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mb-3">Admin</p>

        <nav class="flex flex-col gap-1">
            <a href="{{ route('admin.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium
                      {{ request()->routeIs('admin.index') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Overview
            </a>

            <a href="{{ route('admin.users') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium
                      {{ request()->routeIs('admin.users') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Users
            </a>

            <a href="{{ route('admin.tasks') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium
                      {{ request()->routeIs('admin.tasks') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                All Tasks
            </a>

            <a href="{{ route('admin.category') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium
                      {{ request()->routeIs('admin.category') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                Categories
            </a>

            <a href="{{ route('admin.activity') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium
                      {{ request()->routeIs('admin.activity') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Activity Log
            </a>

            <div class="my-2 border-t border-gray-100"></div>

            <a href="{{ route('admin.export') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export CSV
            </a>
        </nav>

        <div class="mt-auto px-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-xs text-gray-400 hover:text-gray-600">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to app
            </a>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 overflow-y-auto p-6">

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-gray-100 p-4">
                <p class="text-xs text-gray-400 mb-1">Total users</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $stats['total_users'] }}</p>
                <p class="text-xs text-green-500 mt-1">+{{ $stats['new_users_this_week'] }} this week</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4">
                <p class="text-xs text-gray-400 mb-1">Total tasks</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $stats['total_tasks'] }}</p>
                <p class="text-xs text-gray-400 mt-1">across all users</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4">
                <p class="text-xs text-gray-400 mb-1">Completed</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $stats['completed_tasks'] }}</p>
                <p class="text-xs text-green-500 mt-1">{{ $stats['completion_rate'] }}% completion rate</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4">
                <p class="text-xs text-gray-400 mb-1">Overdue</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $stats['overdue_tasks'] }}</p>
                <p class="text-xs text-red-400 mt-1">needs attention</p>
            </div>
        </div>

        {{-- User Management & Activity Log --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- User Management --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">User management</h3>
                    <a href="{{ route('admin.users') }}" class="text-xs text-indigo-500 hover:underline">View all</a>
                </div>

                <div class="divide-y divide-gray-50">
                    @forelse($recent_users as $user)
                    <div class="flex items-center justify-between px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-semibold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            {{-- Role badge --}}
                            @if($user->role === 'admin')
                                <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 font-medium">admin</span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">user</span>
                            @endif

                            {{-- Status badge --}}
                            @if($user->is_banned)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-red-50 text-red-500">banned</span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full bg-green-50 text-green-600">active</span>
                            @endif

                            {{-- Actions --}}
                            @if($user->id !== auth()->id())
                                @if($user->is_banned)
                                    <form action="{{ route('admin.users.unban', $user->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs text-gray-500 border border-gray-200 rounded px-2 py-0.5 hover:bg-gray-50">
                                            Unban
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.ban', $user->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs text-red-400 border border-red-100 rounded px-2 py-0.5 hover:bg-red-50">
                                            Ban
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-400">No users found.</div>
                    @endforelse
                </div>
            </div>

            {{-- Activity Log --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Activity log</h3>
                    <a href="{{ route('admin.activity') }}" class="text-xs text-indigo-500 hover:underline">View all</a>
                </div>

                <div class="divide-y divide-gray-50">
                    @forelse($recent_activity as $log)
                    <div class="flex items-start gap-3 px-5 py-3">
                        <div class="mt-1.5 w-2 h-2 rounded-full shrink-0
                            @if($log->type === 'ban') bg-red-400
                            @elseif($log->type === 'create') bg-green-400
                            @elseif($log->type === 'complete') bg-blue-400
                            @else bg-gray-300
                            @endif">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">{{ $log->user->name ?? 'Unknown' }}</span>
                                {{ $log->description }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-400">No activity yet.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </main>
</div>
@endsection