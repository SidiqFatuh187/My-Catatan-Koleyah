@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Welcome --}}
    <div class="mb-6">
        <h3 class="text-xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}! 👋</h3>
        <p class="text-sm text-gray-400 mt-1">Here's what's going on with your tasks today.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Total Tasks</p>
                <p class="text-2xl font-bold text-gray-800 mt-0.5">0</p>
                <p class="text-xs text-gray-400 mt-0.5">Since last month</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Completed</p>
                <p class="text-2xl font-bold text-gray-800 mt-0.5">0</p>
                <p class="text-xs text-gray-400 mt-0.5">Since last month</p>
            </div>
        </div>

        <div class="bg-blue-600 rounded-2xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-blue-200 font-medium">Pending</p>
                <p class="text-2xl font-bold text-white mt-0.5">0</p>
                <p class="text-xs text-blue-200 mt-0.5">Since last month</p>
            </div>
        </div>

    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Todo List --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-semibold text-gray-700">My Tasks</h2>
                <button class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-4 py-2 rounded-xl transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Task
                </button>
            </div>

            {{-- Filter Tabs --}}
            <div class="flex gap-2 mb-5">
                <button class="px-3 py-1.5 rounded-lg text-xs font-medium bg-blue-600 text-white">All</button>
                <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-500 hover:bg-gray-100">Active</button>
                <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-500 hover:bg-gray-100">Completed</button>
            </div>

            {{-- Empty State --}}
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium text-sm">No tasks yet</p>
                <p class="text-gray-400 text-xs mt-1">Start by adding your first task!</p>
            </div>
        </div>

        {{-- Right Panel --}}
        <div class="space-y-4">

            {{-- Calendar mini --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">📅 Today</h3>
                <p class="text-2xl font-bold text-gray-800">{{ now()->format('d') }}</p>
                <p class="text-sm text-gray-400">{{ now()->format('F Y') }}</p>
                <div class="grid grid-cols-7 gap-1 mt-4">
                    @foreach(['M','T','W','T','F','S','S'] as $day)
                        <div class="text-center text-xs text-gray-400 font-medium">{{ $day }}</div>
                    @endforeach
                    @for($i = 1; $i <= now()->daysInMonth; $i++)
                        <div class="text-center text-xs py-1 rounded-lg {{ $i == now()->day ? 'bg-blue-600 text-white font-bold' : 'text-gray-500 hover:bg-gray-50' }}">
                            {{ $i }}
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Priority Summary --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">🎯 Priority</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">High</span>
                        <div class="flex-1 mx-3 h-1.5 bg-gray-100 rounded-full">
                            <div class="h-1.5 bg-red-500 rounded-full w-0"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-700">0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Medium</span>
                        <div class="flex-1 mx-3 h-1.5 bg-gray-100 rounded-full">
                            <div class="h-1.5 bg-yellow-400 rounded-full w-0"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-700">0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Low</span>
                        <div class="flex-1 mx-3 h-1.5 bg-gray-100 rounded-full">
                            <div class="h-1.5 bg-green-400 rounded-full w-0"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-700">0</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection