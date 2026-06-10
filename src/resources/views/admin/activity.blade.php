@extends('layouts.app')

@section('title', 'Admin - Activity Log')
@section('page-title', 'Activity Log')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
@endpush

@section('content')

<x-modal-delete />

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

    {{-- Section Header --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="text-[11px] font-semibold tracking-widest uppercase text-gray-400">Activity Log</p>
            <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $activity->total() }} events recorded</p>
        </div>
        @if($activity->total() > 0)
        <button onclick="openDeleteModal('{{ route('admin.activity.clearAll') }}', 'semua activity log')"
            class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-500 border border-red-100 rounded-xl hover:bg-red-50 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Clear All
        </button>
        @endif
    </div>

{{-- Activity List --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

    @forelse($activity as $log)
    <div class="flex items-start gap-4 px-5 py-4 border-b border-gray-50 last:border-b-0 hover:bg-gray-50 transition-colors">

        {{-- Dot --}}
        <div class="mt-1.5 shrink-0">
            <div class="w-2 h-2 rounded-full
                @if($log->type === 'login')    bg-blue-400
                @elseif($log->type === 'logout')   bg-gray-300
                @elseif($log->type === 'create')   bg-emerald-400
                @elseif($log->type === 'complete') bg-emerald-600
                @elseif($log->type === 'update')   bg-amber-400
                @elseif($log->type === 'ban')      bg-red-400
                @elseif($log->type === 'unban')    bg-gray-400
                @else                              bg-gray-300
                @endif">
            </div>
        </div>

        {{-- Avatar --}}
        <div class="w-7 h-7 rounded-lg shrink-0 flex items-center justify-center text-[10px] font-bold bg-gray-900 text-white [font-family:'JetBrains_Mono',monospace]">
            {{ strtoupper(substr($log->user->name ?? '?', 0, 2)) }}
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            <p class="text-sm text-gray-700">
                <span class="font-semibold">{{ $log->user->name ?? 'Unknown' }}</span>
                {{ $log->description }}
            </p>
            <p class="text-[11px] text-gray-400 mt-0.5 [font-family:'JetBrains_Mono',monospace]">
                {{ $log->created_at->diffForHumans() }} · {{ $log->created_at->format('d M Y, H:i') }}
            </p>
        </div>

        {{-- Type Badge --}}
        <div class="shrink-0 flex items-center gap-2">
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-lg tracking-wide
                @if($log->type === 'login')    bg-blue-50 text-blue-600
                @elseif($log->type === 'logout')   bg-gray-100 text-gray-500
                @elseif($log->type === 'create')   bg-emerald-50 text-emerald-600
                @elseif($log->type === 'complete') bg-emerald-50 text-emerald-700
                @elseif($log->type === 'update')   bg-amber-50 text-amber-600
                @elseif($log->type === 'ban')      bg-red-50 text-red-500
                @elseif($log->type === 'unban')    bg-gray-100 text-gray-500
                @else                              bg-gray-100 text-gray-400
                @endif">
                {{ $log->type }}
            </span>

            {{-- Delete --}}
            <button onclick="openDeleteModal('{{ route('admin.activity.destroy', $log->id) }}', 'log ini')"
                class="text-gray-300 hover:text-red-400 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>

    </div>
    @empty
    <div class="py-16 text-center">
        <p class="text-sm text-gray-400">No activity recorded yet.</p>
        <p class="text-xs text-gray-300 mt-1">Activity will appear here once users start interacting.</p>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($activity->hasPages())
    <div class="px-5 py-3.5 border-t border-gray-100">
        {{ $activity->links() }}
    </div>
    @endif
</div>

@endsection