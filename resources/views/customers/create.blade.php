@extends('layouts.app')

@section('title', 'Tambah Customer')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Tambah Customer</h1>
        <div class="page-actions">
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card" style="max-width: 600px;">
        <h3 style="margin-bottom: 24px; color: var(--color-text);">Form Tambah Customer</h3>

        <form action="{{ route('customers.store') }}" method="POST">
            @csrf

            @if($errors->any())
                <div style="background: var(--color-danger-soft);
                            border: 1px solid var(--color-danger);
                            border-radius: var(--radius);
                            padding: 12px 16px;
                            margin-bottom: 20px;
                            color: var(--color-danger);
                            font-size: 14px;">
                    ⚠ {{ $errors->first() }}
                </div>
            @endif
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">OLT</label>
                <select name="olt_id"
                        style="width: 100%;
                               padding: 12px 16px;
                               border: 1px solid {{ $errors->has('olt_id') 
                                 ? 'var(--color-danger)' 
                                 : 'var(--color-border)' }};
                               border-radius: var(--radius);
                               background: var(--color-surface);
                               font-size: 14px;
                               color: var(--color-text);
                               cursor: pointer;">
                    <option value="">-- Pilih OLT --</option>
                    @foreach($olts as $olt)
                        <option value="{{ $olt->id }}"
                            {{ old('olt_id') == $olt->id 
                                ? 'selected' : '' }}>
                            {{ $olt->hostname }}
                        </option>
                    @endforeach
                </select>
                @error('olt_id')
                    <p style="color: var(--color-danger);
                              font-size: 12px;
                              margin-top: 6px;">
                        ⚠ {{ $message }}
                    </p>
                @enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block;
                              font-size: 14px;
                              font-weight: 500;
                              color: var(--color-text);
                              margin-bottom: 6px;">
                    Bulan
                </label>
                <select name="month"
                        class="form-input {{ $errors->has('month') 
                            ? 'input-error' : '' }}"
                        style="width: 100%;
                               padding: 12px 16px;
                               border: 1px solid var(--color-border);
                               border-radius: var(--radius);
                               background: var(--color-surface);
                               font-size: 14px;
                               color: var(--color-text);
                               cursor: pointer;">
                    <option value="">-- Pilih Bulan --</option>
                    <option value="januari"
                        {{ old('month') === 'januari' 
                            ? 'selected' : '' }}>
                        Januari
                    </option>
                    <option value="februari"
                        {{ old('month') === 'februari' 
                            ? 'selected' : '' }}>
                        Februari
                    </option>
                    <option value="maret"
                        {{ old('month') === 'maret' 
                            ? 'selected' : '' }}>
                        Maret
                    </option>
                    <option value="april"
                        {{ old('month') === 'april' 
                            ? 'selected' : '' }}>
                        April
                    </option>
                    <option value="mei"
                        {{ old('month') === 'mei' 
                            ? 'selected' : '' }}>
                        Mei
                    </option>
                    <option value="juni"
                        {{ old('month') === 'juni' 
                            ? 'selected' : '' }}>
                        Juni
                    </option>
                    <option value="juli"
                        {{ old('month') === 'juli' 
                            ? 'selected' : '' }}>
                        Juli
                    </option>
                    <option value="agustus"
                        {{ old('month') === 'agustus' 
                            ? 'selected' : '' }}>
                        Agustus
                    </option>
                    <option value="september"
                        {{ old('month') === 'september' 
                            ? 'selected' : '' }}>
                        September
                    </option>
                    <option value="oktober"
                        {{ old('month') === 'oktober' 
                            ? 'selected' : '' }}>
                        Oktober
                    </option>
                    <option value="november"
                        {{ old('month') === 'november' 
                            ? 'selected' : '' }}>
                        November
                    </option>
                    <option value="desember"
                        {{ old('month') === 'desember' 
                            ? 'selected' : '' }}>
                        Desember
                    </option>
                </select>
                @error('month')
                    <p style="color: var(--color-danger);
                              font-size: 12px;
                              margin-top: 6px;">
                        ⚠ {{ $message }}
                    </p>
                @enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">Tahun</label>
                <input type="number" name="year" value="{{ old('year') }}" placeholder="2026" class="form-input">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">B2C</label>
                <input type="number" name="b2c" value="{{ old('b2c') }}" placeholder="Jumlah B2C" class="form-input">
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--color-text);">B2B</label>
                <input type="number" name="b2b" value="{{ old('b2b') }}" placeholder="Jumlah B2B" class="form-input">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
