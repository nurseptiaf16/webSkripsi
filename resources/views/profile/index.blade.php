@extends('layouts.app')

@section('title', 'Profile')

@section('content')
@php
    $user = $user ?? auth()->user();
    $roleLabel = ucfirst($user->role ?? 'user');
    $loginAt = $user->last_login_at ?? null;
    $activities = $activities ?? [];
    $activities = count($activities) > 0
        ? $activities
        : [
            ['label' => 'Belum ada aktivitas', 'time' => '-'],
        ];
@endphp

<div style="max-width: 1120px;">
    <div style="margin-bottom: 16px;">
        <h1 style="font-size: 22px; font-weight: 700; color: var(--color-text); margin-bottom: 6px;">Profil Pengguna</h1>
        <div style="font-size: 13px; color: var(--color-text-muted);">Kelola informasi profil dan keamanan akun Anda</div>
    </div>

    <div style="background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: 18px;
                padding: 20px 22px;
                box-shadow: var(--shadow);
                margin-bottom: 16px;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 56px; height: 56px; border-radius: 50%;
                            background: var(--color-primary-soft); color: var(--color-primary);
                            display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 18px;">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div style="font-size: 15px; font-weight: 700; color: var(--color-text);">
                        {{ $user->name ?? '-' }}
                    </div>
                    <div style="font-size: 12px; color: var(--color-text-muted);">{{ $roleLabel }}</div>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}"
               style="display: inline-flex; align-items: center; gap: 8px;
                      padding: 10px 14px; border-radius: 10px; border: 1px solid var(--color-border);
                      background: #ffffff; font-size: 13px; color: var(--color-text); text-decoration: none;">
                Edit Profil
            </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; margin-top: 16px;">
            <div style="padding: 12px 14px; border-radius: 14px; background: var(--color-surface-2);">
                <div style="font-size: 12px; color: var(--color-text-muted);">Nama Lengkap</div>
                <div style="font-size: 14px; font-weight: 600; color: var(--color-text);">{{ $user->name ?? '-' }}</div>
            </div>
            <div style="padding: 12px 14px; border-radius: 14px; background: var(--color-surface-2);">
                <div style="font-size: 12px; color: var(--color-text-muted);">Email</div>
                <div style="font-size: 14px; font-weight: 600; color: var(--color-text);">{{ $user->email ?? '-' }}</div>
            </div>
            <div style="padding: 12px 14px; border-radius: 14px; background: var(--color-surface-2);">
                <div style="font-size: 12px; color: var(--color-text-muted);">Role</div>
                <div style="font-size: 12px; font-weight: 700; color: #ffffff; background: var(--color-primary); padding: 4px 10px; border-radius: 999px; display: inline-block;">
                    {{ $roleLabel }}
                </div>
            </div>
            <div style="padding: 12px 14px; border-radius: 14px; background: var(--color-surface-2);">
                <div style="font-size: 12px; color: var(--color-text-muted);">Bergabung Sejak</div>
                <div style="font-size: 14px; font-weight: 600; color: var(--color-text);">
                    {{ optional($user->created_at)->format('d M Y') ?? '-' }}
                </div>
            </div>
            <div style="padding: 12px 14px; border-radius: 14px; background: var(--color-surface-2);">
                <div style="font-size: 12px; color: var(--color-text-muted);">Login Terakhir</div>
                <div style="font-size: 14px; font-weight: 600; color: var(--color-text);">
                    {{ $loginAt ? $loginAt->format('d M Y, H:i') : '-' }}
                </div>
            </div>
        </div>
    </div>

    <div style="background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: 18px;
                padding: 20px 22px;
                box-shadow: var(--shadow);
                margin-bottom: 16px;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
            <div>
                <div style="font-size: 15px; font-weight: 700; color: var(--color-text);">Keamanan Akun</div>
                <div style="font-size: 13px; color: var(--color-text-muted);">Perbarui password untuk menjaga keamanan akun.</div>
            </div>
            <a href="{{ route('profile.password.edit') }}"
               style="display: inline-flex; align-items: center; gap: 8px;
                      padding: 10px 14px; border-radius: 10px; border: 1px solid var(--color-border);
                      background: var(--color-primary); color: #ffffff; font-size: 13px; text-decoration: none;">
                Ubah Password
            </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 12px; margin-top: 14px;">
            <div style="padding: 12px 14px; border-radius: 12px; border: 1px solid var(--color-border); background: #ffffff;">
                <div style="font-size: 12px; color: var(--color-text-muted);">Password terakhir diubah</div>
                <div style="font-size: 13px; font-weight: 600; color: var(--color-text);">-</div>
            </div>
            <div style="padding: 12px 14px; border-radius: 12px; border: 1px solid var(--color-border); background: #ffffff;">
                <div style="font-size: 12px; color: var(--color-text-muted);">Status Keamanan</div>
                <div style="font-size: 12px; color: var(--color-text-muted);">Akun Anda aman. Password terakhir diubah beberapa waktu lalu.</div>
            </div>
        </div>
    </div>

    <div style="background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: 18px;
                padding: 20px 22px;
                box-shadow: var(--shadow);">
        <div style="font-size: 15px; font-weight: 700; color: var(--color-text); margin-bottom: 10px;">Aktivitas Terakhir</div>
        <div style="border-top: 1px solid var(--color-border);">
            @foreach($activities as $activity)
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--color-border);">
                    <div style="font-size: 13px; color: var(--color-text);">{{ $activity['label'] }}</div>
                    <div style="font-size: 12px; color: var(--color-text-muted);">{{ $activity['time'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
