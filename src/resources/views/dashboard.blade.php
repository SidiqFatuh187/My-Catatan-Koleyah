@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Welcome --}}
    <div class="mb-6">
        <h3 class="text-xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}! 👋</h3>
        <p class="text-sm text-gray-400 mt-1">Here's what's going on with your tasks today.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Total Tasks</p>
                <p class="text-2xl font-bold text-gray-800 mt-0.5">{{ $totalTasks }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $category->count() }} kategori</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Completed</p>
                <p class="text-2xl font-bold text-gray-800 mt-0.5">{{ $completedTasks }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}% selesai
                </p>
            </div>
        </div>

        <div class="bg-blue-600 rounded-2xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-blue-200 font-medium">Pending</p>
                <p class="text-2xl font-bold text-white mt-0.5">{{ $pendingTasks }}</p>
                <p class="text-xs text-blue-200 mt-0.5">{{ $activeTasks }} sedang active</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-red-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Overdue</p>
                <p class="text-2xl font-bold text-red-500 mt-0.5">{{ $overdueTasks }}</p>
                <p class="text-xs text-gray-400 mt-0.5">task melewati deadline</p>
            </div>
        </div>

    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Todo List --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-semibold text-gray-700">Recent Tasks</h2>
                <a href="{{ route('todo.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-4 py-2 rounded-xl transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Task
                </a>
            </div>

            {{-- Todo Items --}}
            @forelse($recentTodos as $todo)
                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100 mb-2">

                    {{-- Status dot --}}
                    @php
                        $dotClass = match($todo->status) {
                            'completed' => 'bg-green-500',
                            'active'    => 'bg-blue-500',
                            default     => 'bg-yellow-400',
                        };
                    @endphp
                    <div class="w-2 h-2 rounded-full shrink-0 {{ $dotClass }}"></div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate {{ $todo->status === 'completed' ? 'line-through text-gray-400' : '' }}">
                            {{ $todo->title }}
                        </p>
                        <div class="flex items-center gap-2 mt-0.5">
                            @if($todo->category)
                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $todo->category->color }}"></span>
                                    {{ $todo->category->name }}
                                </span>
                            @endif
                            @if($todo->deadline)
                                <span class="text-xs {{ $todo->deadline->isPast() && $todo->status !== 'completed' ? 'text-red-400' : 'text-gray-400' }}">
                                    · {{ $todo->deadline->format('d M') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Priority --}}
                    @php
                        $priorityClass = match($todo->priority) {
                            'high'   => 'bg-red-50 text-red-500',
                            'medium' => 'bg-yellow-50 text-yellow-600',
                            'low'    => 'bg-green-50 text-green-600',
                            default  => 'bg-gray-50 text-gray-400',
                        };
                    @endphp
                    <span class="text-xs px-2 py-0.5 rounded-lg font-medium shrink-0 {{ $priorityClass }}">
                        {{ ucfirst($todo->priority) }}
                    </span>

                </div>
            @empty
                <div class="text-center py-10">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-gray-700 font-medium text-sm">No tasks yet</p>
                    <p class="text-gray-400 text-xs mt-1">Start by adding your first task!</p>
                </div>
            @endforelse

            @if($recentTodos->count() > 0)
                <a href="{{ route('todo.index') }}"
                    class="flex items-center justify-center gap-1.5 mt-3 text-xs text-blue-600 hover:text-blue-700 font-medium transition-colors">
                    Lihat semua tasks
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @endif
        </div>

        {{-- Right Panel --}}
        <div class="space-y-4">

            {{-- Calendar mini --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">📅 {{ now()->format('F Y') }}</h3>
                <p class="text-3xl font-bold text-gray-800">{{ now()->format('d') }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ now()->format('l') }}</p>
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

            {{-- Kategori --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700">🗂 Kategori</h3>
                    <a href="{{ route('category.index') }}" class="text-xs text-blue-600 hover:text-blue-700">Lihat semua</a>
                </div>
                @forelse($category as $cat)
                    <div class="flex items-center gap-2.5 py-2 border-b border-gray-50 last:border-0">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0"
                            style="background-color: {{ $cat->color }}">
                            {{ $cat->icon ?? strtoupper(substr($cat->name, 0, 1)) }}
                        </div>
                        <p class="text-sm text-gray-600 flex-1 truncate">{{ $cat->name }}</p>
                        <span class="text-xs text-gray-400">{{ $cat->todos_count }}</span>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-3">Belum ada kategori</p>
                @endforelse
            </div>

            {{-- Priority Summary --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">🎯 Priority</h3>
                <div class="space-y-3">
                    @php
                        $highCount   = $totalTasks > 0 ? $highTasks : 0;
                        $mediumCount = $totalTasks > 0 ? $mediumTasks : 0;
                        $lowCount    = $totalTasks > 0 ? $lowTasks : 0;
                        $maxVal      = max($highCount, $mediumCount, $lowCount, 1);
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-500 w-12">High</span>
                        <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-1.5 bg-red-500 rounded-full transition-all"
                                style="width: {{ ($highCount / $maxVal) * 100 }}%"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-700 w-4 text-right">{{ $highCount }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-500 w-12">Medium</span>
                        <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-1.5 bg-yellow-400 rounded-full transition-all"
                                style="width: {{ ($mediumCount / $maxVal) * 100 }}%"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-700 w-4 text-right">{{ $mediumCount }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-500 w-12">Low</span>
                        <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-1.5 bg-green-400 rounded-full transition-all"
                                style="width: {{ ($lowCount / $maxVal) * 100 }}%"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-700 w-4 text-right">{{ $lowCount }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection