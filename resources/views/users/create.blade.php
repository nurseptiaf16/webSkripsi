@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Tambah User</h1>
        <div class="page-actions">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card" style="max-width: 600px;">
        <h3 style="margin-bottom: 24px; color: var(--color-text);">Form Tambah User</h3>

        @if ($errors->any())
            <div style="margin-bottom: 16px; padding: 12px; background: var(--color-danger-soft); color: var(--color-danger); border-radius: 8px; border: 1px solid rgba(225, 29, 72, 0.3);">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">Nama</label>
                <input type="text" name="name" placeholder="Nama lengkap" class="form-input" value="{{ old('name') }}">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">Email</label>
                <input type="email" name="email" placeholder="email@example.com" class="form-input" value="{{ old('email') }}">
                @error('email')
                    <div style="margin-top: 6px; color: var(--color-danger); font-size: 12px;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">Password</label>
                <input type="password" name="password" placeholder="Password" class="form-input">
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">Role</label>
                <select name="role" class="form-input">
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="manajer" {{ old('role') === 'manajer' ? 'selected' : '' }}>Manajer</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
