@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="max-w-2xl mx-auto">

    <x-modal-delete />

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Notifications</h2>
            <p class="text-sm text-gray-400 mt-0.5">{{ $notifications->total() }} total notifikasi</p>
        </div>

        <div class="flex items-center gap-2">
            @if(auth()->user()->unreadNotifications()->count() > 0)
            <form id="markAllReadForm" action="{{ route('notification.markAllRead') }}" method="POST" class="hidden">
                @csrf
            </form>
            <button onclick="document.getElementById('markAllReadForm').submit();"
                class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-600 border border-indigo-200 rounded-xl hover:bg-indigo-50 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Mark all as read
            </button>
            @endif

            @if($notifications->total() > 0)
            <button type="button" onclick="openDeleteModal('{{ route('notification.destroyAll') }}', 'semua notifikasi')"
                class="w-8 h-8 flex items-center justify-center text-gray-400 border border-gray-200 rounded-xl hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
            @endif
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- List --}}
    <div class="flex flex-col gap-3">

        @forelse($notifications as $notif)
        @php $data = $notif->data; @endphp

        <div class="relative bg-white rounded-2xl border overflow-hidden transition-all
            {{ is_null($notif->read_at) ? 'border-indigo-200 shadow-sm shadow-indigo-100' : 'border-gray-100' }}">

            {{-- Unread accent bar --}}
            @if(is_null($notif->read_at))
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-2xl
                {{ ($data['type'] ?? '') === 'overdue' ? 'bg-red-400' : (($data['type'] ?? '') === 'due_today' ? 'bg-amber-400' : 'bg-indigo-400') }}">
            </div>
            @endif

            <div class="flex items-start gap-4 px-5 py-4 {{ is_null($notif->read_at) ? 'pl-6' : '' }}">

                {{-- Icon --}}
                <div class="w-10 h-10 rounded-xl shrink-0 flex items-center justify-center
                    {{ ($data['type'] ?? '') === 'overdue'
                        ? 'bg-red-50 text-red-500'
                        : (($data['type'] ?? '') === 'due_today'
                            ? 'bg-amber-50 text-amber-500'
                            : 'bg-indigo-50 text-indigo-500') }}">
                    @if(($data['type'] ?? '') === 'overdue')
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    @elseif(($data['type'] ?? '') === 'due_today')
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    @if(isset($data['task_id']))
                        <a href="{{ route('todo.show', $data['task_id']) }}"
                            class="text-sm font-semibold {{ is_null($notif->read_at) ? 'text-gray-800' : 'text-gray-600' }} hover:text-indigo-600 transition-colors leading-snug block">
                            {{ $data['message'] ?? '' }}
                        </a>
                    @else
                        <p class="text-sm font-semibold {{ is_null($notif->read_at) ? 'text-gray-800' : 'text-gray-600' }} leading-snug">
                            {{ $data['message'] ?? '' }}
                        </p>
                    @endif

                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                        {{-- Type badge --}}
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-lg
                            {{ ($data['type'] ?? '') === 'overdue'
                                ? 'bg-red-50 text-red-500'
                                : (($data['type'] ?? '') === 'due_today'
                                    ? 'bg-amber-50 text-amber-600'
                                    : 'bg-indigo-50 text-indigo-600') }}">
                            {{ ($data['type'] ?? '') === 'overdue' ? 'Overdue' : (($data['type'] ?? '') === 'due_today' ? 'Due Today' : 'Reminder') }}
                        </span>

                        <span class="text-gray-300 text-xs">·</span>
                        <p class="text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</p>
                        <span class="text-gray-300 text-xs">·</span>
                        <p class="text-xs text-gray-400">{{ $notif->created_at->format('d M Y, H:i') }}</p>

                        @if(!is_null($notif->read_at))
                            <span class="text-gray-300 text-xs">·</span>
                            <span class="text-[10px] text-gray-400">Dibaca</span>
                        @endif
                    </div>
                </div>

                {{-- Delete --}}
                <button onclick="openDeleteModal('{{ route('notification.destroy', $notif->id) }}', 'notifikasi ini')"
                    class="shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 hover:text-red-400 hover:bg-red-50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

            </div>
        </div>

        @empty
        <div class="bg-white rounded-2xl border border-gray-100 py-16 text-center">
            <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-gray-500">Belum ada notifikasi</p>
            <p class="text-xs text-gray-400 mt-1">Notifikasi akan muncul saat ada task yang mendekati deadline.</p>
        </div>
        @endforelse

    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
    @endif

</div>
@endsection