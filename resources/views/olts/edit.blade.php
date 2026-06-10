@extends('layouts.app')

@section('title', 'Edit OLT')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Edit OLT</h1>
        <div class="page-actions">
            <a href="{{ route('olts.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card" style="max-width: 600px;">
        <h3 style="margin-bottom: 24px; color: var(--color-text);">Form Edit Perangkat OLT</h3>

        @if ($errors->any())
            <div style="margin-bottom: 16px; padding: 12px; background: var(--color-danger-soft); color: var(--color-danger); border-radius: 8px; border: 1px solid rgba(225, 29, 72, 0.3);">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('olts.update', $olt->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">Hostname OLT</label>
                <input type="text" 
                       name="hostname" 
                       value="{{ $olt->hostname }}" 
                       style="width: 100%;
                              padding: 12px 16px;
                              border: 1px solid {{ $errors->has('hostname') ? 'var(--color-danger)' : 'var(--color-border)' }};
                              border-radius: var(--radius);
                              box-sizing: border-box;" 
                       class="form-input">
                @error('hostname')
                    <p style="color: var(--color-danger);
                              font-size: 12px;
                              margin-top: 6px;
                              margin-bottom: 0;">
                        ⚠ {{ $message }}
                    </p>
                @enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">Lokasi</label>
                <input type="text" name="location" value="{{ $olt->location }}" class="form-input">
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">Status</label>
                <select name="status" class="form-input">
                    <option value="">-- Pilih Status --</option>
                    <option value="active" {{ $olt->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="maintenance" {{ $olt->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="non-active" {{ $olt->status === 'non-active' ? 'selected' : '' }}>Non-Active</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
