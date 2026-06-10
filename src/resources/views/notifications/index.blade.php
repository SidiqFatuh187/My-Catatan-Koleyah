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

        @if(auth()->user()->unreadNotifications()->count() > 0)
        <form id="markAllReadForm" action="{{ route('notification.markAllRead') }}" method="POST" class="hidden">
            @csrf
        </form>
        <button onclick="document.getElementById('markAllReadForm').submit();"
            class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-600 border border-blue-200 rounded-xl hover:bg-blue-50 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Mark all as read
        </button>
        @endif
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
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        @forelse($notifications as $notif)
        @php $data = $notif->data; @endphp

        <div class="flex items-start gap-4 px-5 py-4 border-b border-gray-50 last:border-b-0 hover:bg-gray-50 transition-colors {{ is_null($notif->read_at) ? 'bg-blue-50/30' : '' }}">

            {{-- Dot --}}
            <div class="mt-2 shrink-0">
                @if(is_null($notif->read_at))
                    <div class="w-2 h-2 rounded-full
                        {{ ($data['type'] ?? '') === 'overdue'   ? 'bg-red-400'   :
                          (($data['type'] ?? '') === 'due_today' ? 'bg-amber-400' : 'bg-blue-400') }}">
                    </div>
                @else
                    <div class="w-2 h-2 rounded-full bg-gray-200"></div>
                @endif
            </div>

            {{-- Icon --}}
            <div class="w-9 h-9 rounded-xl shrink-0 flex items-center justify-center
                {{ ($data['type'] ?? '') === 'overdue'   ? 'bg-red-50 text-red-400'     :
                  (($data['type'] ?? '') === 'due_today' ? 'bg-amber-50 text-amber-400' : 'bg-blue-50 text-blue-400') }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                {{-- Klik pesan → ke todo.show kalau ada task_id --}}
                @if(isset($data['task_id']))
                    <a href="{{ route('todo.show', $data['task_id']) }}"
                        class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors leading-snug block">
                        {{ $data['message'] ?? '' }}
                    </a>
                @else
                    <p class="text-sm font-medium text-gray-700 leading-snug">{{ $data['message'] ?? '' }}</p>
                @endif

                <div class="flex items-center gap-3 mt-1">
                    <p class="text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</p>
                    <p class="text-xs text-gray-300">·</p>
                    <p class="text-xs text-gray-400">{{ $notif->created_at->format('d M Y, H:i') }}</p>

                    {{-- Type badge --}}
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-lg
                        {{ ($data['type'] ?? '') === 'overdue'   ? 'bg-red-50 text-red-500'     :
                          (($data['type'] ?? '') === 'due_today' ? 'bg-amber-50 text-amber-500' : 'bg-blue-50 text-blue-500') }}">
                        {{ $data['type'] ?? '' }}
                    </span>
                </div>
            </div>

            {{-- Delete --}}
            <button onclick="openDeleteModal('{{ route('notification.destroy', $notif->id) }}', 'notifikasi ini')"
                class="shrink-0 text-gray-300 hover:text-red-400 transition-colors mt-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

        </div>
        @empty
        <div class="py-16 text-center">
            <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-500">Belum ada notifikasi</p>
            <p class="text-xs text-gray-400 mt-1">Notifikasi akan muncul saat ada task yang mendekati deadline.</p>
        </div>
        @endforelse

        {{-- Pagination --}}
        @if($notifications->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100">
            {{ $notifications->links() }}
        </div>
        @endif

    </div>
</div>
@endsection