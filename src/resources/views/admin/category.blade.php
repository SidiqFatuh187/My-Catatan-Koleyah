@extends('layouts.app')

@section('title', 'Admin - Categories')
@section('page-title', 'Category Overview')

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

{{-- Section Header --}}
<div class="flex items-center justify-between mb-4">
    <div>
        <p class="text-[11px] font-semibold tracking-widest uppercase text-gray-400">All Category</p>
        <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $category->total() }} category total</p>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-5 py-3">Category</th>
                <th class="text-left text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-5 py-3">Owner</th>
                <th class="text-left text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-5 py-3">Total Tasks</th>
                <th class="text-left text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-5 py-3">Created</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($category as $cat)
            <tr class="hover:bg-gray-50 transition-colors">

                {{-- Category --}}
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-2.5">
                        @if($cat->icon)
                            <span class="text-base">{{ $cat->icon }}</span>
                        @endif
                        <div>
                            <p class="text-sm font-semibold text-gray-700">{{ $cat->name }}</p>
                            @if($cat->color)
                                <div class="flex items-center gap-1 mt-0.5">
                                    <div class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $cat->color }}"></div>
                                    <span class="text-[10px] [font-family:'JetBrains_Mono',monospace] text-gray-400">{{ $cat->color }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </td>

                {{-- Owner --}}
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg shrink-0 flex items-center justify-center text-[10px] font-bold bg-gray-900 text-white [font-family:'JetBrains_Mono',monospace]">
                            {{ strtoupper(substr($cat->user->name ?? '?', 0, 2)) }}
                        </div>
                        <p class="text-sm text-gray-600">{{ $cat->user->name ?? '—' }}</p>
                    </div>
                </td>

                {{-- Total Tasks --}}
                <td class="px-5 py-3.5">
                    <span class="text-sm [font-family:'JetBrains_Mono',monospace] text-gray-600">
                        {{ $cat->todos_count }}
                    </span>
                </td>

                {{-- Created --}}
                <td class="px-5 py-3.5">
                    <span class="text-xs [font-family:'JetBrains_Mono',monospace] text-gray-400">
                        {{ $cat->created_at->format('d M Y') }}
                    </span>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-400">
                    No category found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($category->hasPages())
    <div class="px-5 py-3.5 border-t border-gray-100">
        {{ $category->links() }}
    </div>
    @endif
</div>

@endsection