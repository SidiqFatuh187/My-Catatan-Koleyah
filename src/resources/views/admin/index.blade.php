@extends('layouts.app')

@section('page-title', 'Admin Panel')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
@endpush

@section('content')

{{-- ── Page Header ── --}}
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

{{-- ── Horizontal Admin Nav ── --}}
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

{{-- ── Stat Cards ── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">

    {{-- Total Users --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 relative overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-[3px] rounded-t-xl bg-blue-600"></div>
        <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400 mb-2">Total Users</p>
        <p class="[font-family:'JetBrains_Mono',monospace] text-3xl font-medium text-gray-800 leading-none">
            {{ number_format($stats['total_users']) }}
        </p>
        <p class="text-xs text-emerald-500 mt-2 font-medium">↑ {{ $stats['new_users_this_week'] }} this week</p>
    </div>

    {{-- Total Tasks --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 relative overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-[3px] rounded-t-xl bg-amber-400"></div>
        <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400 mb-2">Total Tasks</p>
        <p class="[font-family:'JetBrains_Mono',monospace] text-3xl font-medium text-gray-800 leading-none">
            {{ number_format($stats['total_tasks']) }}
        </p>
        <p class="text-xs text-gray-400 mt-2">across all users</p>
    </div>

    {{-- Completed --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 relative overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-[3px] rounded-t-xl bg-emerald-500"></div>
        <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400 mb-2">Completed</p>
        <p class="[font-family:'JetBrains_Mono',monospace] text-3xl font-medium text-gray-800 leading-none">
            {{ number_format($stats['completed_tasks']) }}
        </p>
        <p class="text-xs text-emerald-500 mt-2 font-medium">{{ $stats['completion_rate'] }}% rate</p>
    </div>

    {{-- Overdue --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 relative overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-[3px] rounded-t-xl bg-red-500"></div>
        <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400 mb-2">Overdue</p>
        <p class="[font-family:'JetBrains_Mono',monospace] text-3xl font-medium text-gray-800 leading-none">
            {{ number_format($stats['overdue_tasks']) }}
        </p>
        <p class="text-xs text-red-400 mt-2 font-medium">needs attention</p>
    </div>

</div>

{{-- ── Content Grid ── --}}
<div class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-4">

    {{-- ── User Management ── --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
            <p class="text-[11px] font-semibold tracking-widest uppercase text-gray-400">User Management</p>
            <a href="{{ route('admin.users') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                View all →
            </a>
        </div>

        @forelse($recent_users as $user)
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-50 last:border-b-0 hover:bg-gray-50 transition-colors">

            {{-- Avatar + Info --}}
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg shrink-0 flex items-center justify-center text-xs font-bold
                            [font-family:'JetBrains_Mono',monospace]
                            {{ $user->role === 'admin' ? 'bg-blue-600 text-white' : 'bg-gray-900 text-white' }}">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400 [font-family:'JetBrains_Mono',monospace]">{{ $user->email }}</p>
                </div>
            </div>

            {{-- Badges + Actions --}}
            <div class="flex items-center gap-2">

                {{-- Role --}}
                @if($user->role === 'admin')
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 tracking-wide">
                        admin
                    </span>
                @else
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-lg bg-gray-100 text-gray-500 tracking-wide">
                        user
                    </span>
                @endif

                {{-- Status --}}
                @if($user->is_banned)
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-lg bg-red-50 text-red-500 tracking-wide">
                        banned
                    </span>
                @else
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-600 tracking-wide">
                        active
                    </span>
                @endif

                {{-- Action Button --}}
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
                @endif

            </div>
        </div>
        @empty
        <div class="py-10 text-center text-sm text-gray-400">No users found.</div>
        @endforelse
    </div>

    {{-- ── Activity Log ── --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
            <p class="text-[11px] font-semibold tracking-widest uppercase text-gray-400">Activity Log</p>
            <a href="{{ route('admin.activity') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                View all →
            </a>
        </div>

        @forelse($recent_activity as $log)
        <div class="flex items-start gap-3 px-5 py-3.5 border-b border-gray-50 last:border-b-0">

            {{-- Dot --}}
            <div class="mt-[5px] shrink-0">
                <div class="w-2 h-2 rounded-full
                    @if($log->type === 'ban')      bg-red-400
                    @elseif($log->type === 'create')   bg-emerald-400
                    @elseif($log->type === 'complete') bg-blue-500
                    @else                              bg-gray-300
                    @endif">
                </div>
            </div>

            {{-- Text --}}
            <div class="min-w-0">
                <p class="text-sm text-gray-700 leading-snug">
                    <span class="font-semibold">{{ $log->user->name ?? 'Unknown' }}</span>
                    {{ $log->description }}
                </p>
                <p class="text-[11px] text-gray-400 mt-0.5 [font-family:'JetBrains_Mono',monospace]">
                    {{ $log->created_at->diffForHumans() }}
                </p>
            </div>
        </div>

        @empty
        <div class="py-10 text-center text-sm text-gray-400">No activity yet.</div>
        @endforelse

        {{-- View all --}}
        @if($recent_activity->count() >= 8)
        <div class="px-5 py-3 border-t border-gray-100">
            <a href="{{ route('admin.activity') }}"
               class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                View all activity →
            </a>
        </div>
        @endif
        
    </div>

</div>

@endsection