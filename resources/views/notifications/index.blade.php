@extends('layouts.app')
@section('title', 'Notifikasi')
@section('content')

<div style="display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;">
    <div>
        <h1 style="font-size: 22px;
                   font-weight: 700;
                   color: var(--color-text);
                   margin-bottom: 4px;">
            Notifikasi
        </h1>
        <p style="color: var(--color-text-muted);
                  font-size: 14px;">
            Riwayat aktivitas sistem
        </p>
    </div>

    @if($notifications->total() > 0)
        <form method="POST"
              action="{{ route('notifications.clear') }}">
            @csrf
            <button type="submit"
                    style="padding: 10px 20px;
                           border: 1px solid var(--color-danger);
                           border-radius: 10px;
                           background: var(--color-danger-soft);
                           color: var(--color-danger);
                           font-size: 13px;
                           font-weight: 600;
                           cursor: pointer;
                           display: flex;
                           align-items: center;
                           gap: 8px;
                           transition: all 0.2s ease;"
                    onmouseover="this.style.background=
                        'var(--color-danger)';
                        this.style.color='white'"
                    onmouseout="this.style.background=
                        'var(--color-danger-soft)';
                        this.style.color='var(--color-danger)'">
                <svg width="14" height="14"
                     viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14H6L5 6M9 6V4h6v2"/>
                </svg>
                Hapus Semua
            </button>
        </form>
    @endif
</div>

<div style="display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;">
    @php
        $filterType = request('type', 'all');
        $types = [
            'all' => 'Semua',
            'user_login' => 'Login',
            'customer_created' => 'Customer Baru',
            'customer_updated' => 'Customer Edit',
            'customer_deleted' => 'Customer Hapus',
            'olt_created' => 'OLT Baru',
            'olt_status_changed' => 'Status OLT',
            'olt_updated' => 'OLT Edit',
            'olt_deleted' => 'OLT Hapus',
            'user_created' => 'User Baru',
            'user_updated' => 'User Edit',
            'user_deleted' => 'User Hapus',
            'prediction_run' => 'Prediksi',
            'report_exported' => 'Export PDF',
        ];
    @endphp

    @foreach($types as $key => $label)
        @php
            $isActive = $filterType === $key;
            $tabClass = $isActive
                ? 'notif-tab--active'
                : 'notif-tab--inactive';
        @endphp
        <a href="{{ route('notifications.index') }}{{ $key !== 'all' ? '?type=' . $key : '' }}"
           class="notif-tab {{ $tabClass }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<div style="display: flex;
            flex-direction: column;
            gap: 10px;">

    @forelse($notifications as $notif)
        @php
            $iconMap = [
                'user'   => '<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>',
                'server' => '<rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/>',
                'edit'   => '<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>',
                'trash'  => '<polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6M9 6V4h6v2"/>',
                'bell'   => '<path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>',
                'file'   => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
                'chart'  => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/><line x1="2" y1="20" x2="22" y2="20"/>',
            ];
            $iconSvg  = $iconMap[$notif->icon] ?? $iconMap['bell'];
            $colorClass = in_array($notif->color, ['success', 'danger', 'warning', 'primary'], true)
                ? $notif->color
                : 'primary';
        @endphp

        @php
            $cardClass = $notif->is_read
                ? 'notif-card--read'
                : 'notif-card--unread';
        @endphp

        <div class="notif-card {{ $cardClass }}">

            <div class="notif-icon notif-icon--{{ $colorClass }}">
                <svg width="18" height="18"
                     viewBox="0 0 24 24" fill="none"
                     stroke="currentColor"
                     stroke-width="2" stroke-linecap="round">
                    {!! $iconSvg !!}
                </svg>
            </div>

            <div style="flex: 1; min-width: 0;">
                <div style="display: flex;
                            justify-content: space-between;
                            align-items: flex-start;
                            gap: 12px;
                            margin-bottom: 4px;">
                    <span style="font-size: 14px;
                                 font-weight: 600;
                                 color: var(--color-text);">
                        {{ $notif->title }}
                    </span>
                    <span style="font-size: 11px;
                                 color: var(--color-text-muted);
                                 white-space: nowrap;
                                 flex-shrink: 0;">
                        {{ $notif->created_at->diffForHumans() }}
                    </span>
                </div>
                <p style="font-size: 13px;
                          color: var(--color-text-muted);
                          margin: 0;
                          line-height: 1.5;">
                    {{ $notif->message }}
                </p>
                <div style="display: flex;
                            align-items: center;
                            gap: 8px;
                            margin-top: 6px;">
                    <span style="font-size: 11px;
                                 color: var(--color-text-muted);">
                        {{ $notif->created_at->format('d M Y, H:i') }}
                    </span>
                    @if(!$notif->is_read)
                        <span style="width: 6px; height: 6px;
                                     border-radius: 50%;
                                     background: var(--color-primary);
                                     display: inline-block;">
                        </span>
                        <span style="font-size: 11px;
                                     color: var(--color-primary);
                                     font-weight: 600;">
                            Baru
                        </span>
                    @endif
                </div>
            </div>

        </div>
    @empty
        <div style="background: var(--color-surface);
                    border: 1px solid var(--color-border);
                    border-radius: 16px;
                    padding: 60px 20px;
                    text-align: center;">
            <div style="width: 64px; height: 64px;
                        background: var(--color-surface-2);
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 16px;">
                <svg width="28" height="28"
                     viewBox="0 0 24 24" fill="none"
                     stroke="var(--color-text-muted)"
                     stroke-width="2">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
            </div>
            <p style="font-size: 15px;
                      font-weight: 600;
                      color: var(--color-text);
                      margin-bottom: 6px;">
                Tidak ada notifikasi
            </p>
            <p style="font-size: 13px;
                      color: var(--color-text-muted);">
                Aktivitas sistem akan muncul di sini
            </p>
        </div>
    @endforelse

</div>

@if($notifications->hasPages())
    <div style="margin-top: 20px;">
        {{ $notifications->links() }}
    </div>
@endif

@endsection
