@extends('layouts.app')

@section('title', 'Notifications')

@php
    $header = 'Notifications';
    $headerActions = '<form action="' . route('notifications.read-all') . '" method="POST" class="d-inline"><input type="hidden" name="_token" value="' . csrf_token() . '"><input type="hidden" name="_method" value="PATCH"><button type="submit" class="btn btn-outline-secondary">Tout marquer comme lu</button></form>';
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            @forelse($notifications as $notification)
                <div class="d-flex justify-content-between align-items-start border-bottom py-3 {{ $notification->read_at ? '' : 'bg-light' }}">
                    <div class="{{ $notification->read_at ? 'text-muted' : 'fw-semibold' }}">
                        <p class="mb-1">
                            @if(!empty($notification->data['url']))
                                <a href="{{ $notification->data['url'] }}" class="text-decoration-none">{{ $notification->data['message'] ?? 'Notification' }}</a>
                            @else
                                {{ $notification->data['message'] ?? 'Notification' }}
                            @endif
                        </p>
                        <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                        @if($notification->read_at)
                            <br><small class="text-success">Lu le {{ $notification->read_at->format('d/m/Y H:i') }}</small>
                        @endif
                    </div>
                    @unless($notification->read_at)
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-primary">Marquer comme lue</button>
                        </form>
                    @endunless
                </div>
            @empty
                <p class="text-muted mb-0 text-center py-4">Aucune notification.</p>
            @endforelse

        </div>
    </div>
@endsection
