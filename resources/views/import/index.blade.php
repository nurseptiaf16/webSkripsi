@extends('layouts.app')

@section('title', 'Import Data')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Import Data OLT</h1>
        <div class="page-actions">
            <a href="{{ route('olts.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    @if(session('success'))
        <div class="card" style="margin-bottom: 24px; background: var(--color-success-soft); color: var(--color-success); border-color: rgba(22, 163, 74, 0.3);">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="card" style="margin-bottom: 24px; background: var(--color-danger-soft); color: var(--color-danger); border-color: rgba(225, 29, 72, 0.3);">
            {{ session('error') }}
        </div>
    @endif

    <div class="card" style="max-width: 640px;">
        <h3 style="margin-bottom: 20px; color: var(--color-text);">Upload File Excel</h3>

        <form action="{{ route('import.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">File Excel (.xlsx / .xls)</label>
                <input type="file" name="file" accept=".xlsx,.xls" class="form-input">
                @error('file')
                    <p style="color: var(--color-danger); font-size: 12px; margin-top: 6px; margin-bottom: 0;">
                        ⚠ {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Import</button>
        </form>
    </div>
@endsection
