@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
@php
    $user = $user ?? auth()->user();
@endphp

<div style="max-width: 900px;">
    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
        <div>
            <h1 style="font-size: 22px; font-weight: 700; color: var(--color-text); margin-bottom: 6px;">Ubah Password</h1>
            <div style="font-size: 13px; color: var(--color-text-muted);">Perbarui password untuk menjaga keamanan akun.</div>
        </div>
        <a href="{{ route('profile.index') }}"
           style="padding: 8px 12px; border-radius: 10px; border: 1px solid var(--color-border); background: #ffffff; font-size: 13px; color: var(--color-text); text-decoration: none;">
            Kembali ke Profil
        </a>
    </div>

    <div style="background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: 18px;
                padding: 20px 22px;
                box-shadow: var(--shadow);">
        @if(session('success_password'))
            <div style="margin-bottom: 16px; padding: 12px; background: var(--color-success-soft); color: var(--color-success); border-radius: 8px; border: 1px solid rgba(22, 163, 74, 0.3);">
                {{ session('success_password') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="margin-bottom: 16px; padding: 12px; background: var(--color-danger-soft); color: var(--color-danger); border-radius: 8px; border: 1px solid rgba(225, 29, 72, 0.3);">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.password') }}">
            @csrf
            @method('PATCH')

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">Password Lama</label>
                    <input type="password" name="current_password" class="form-input">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">Password Baru</label>
                    <input type="password" name="password" class="form-input">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-input">
                </div>
            </div>

            <div style="margin-top: 16px;">
                <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
        </form>
    </div>
</div>
@endsection
