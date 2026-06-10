@extends('layouts.app')

@section('title', 'Evaluation')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Evaluation</h1>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <div class="search-wrapper">
            <form method="GET" action="{{ route('evaluation') }}" style="display: flex; gap: 8px;">
                <select name="olt_id" class="form-input" style="max-width: 200px;">
                    <option value="">Pilih OLT</option>
                    @foreach($olts as $olt)
                        <option value="{{ $olt->id }}" {{ request('olt_id') == $olt->id ? 'selected' : '' }}>
                            {{ $olt->hostname }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Hitung</button>
            </form>
        </div>
    </div>

    <div class="stat-grid" style="margin-bottom: 24px;">
        <div class="stat-card card">
            <div class="stat-icon">
                <span class="icon">📊</span>
            </div>
            <div class="stat-value">{{ request('olt_id') ? $mape . '%' : '-' }}</div>
            <div class="stat-label">MAPE</div>
            <div class="stat-trend">Mean Absolute Percentage Error</div>
        </div>
        <div class="stat-card card">
            <div class="stat-icon">
                <span class="icon">✅</span>
            </div>
            <div class="stat-value">
                {{ request('olt_id') ? ($mape <= 10 ? 'Bagus' : ($mape <= 20 ? 'Sedang' : 'Perlu Perbaikan')) : 'Pilih OLT' }}
            </div>
            <div class="stat-label">Status Evaluasi</div>
            <div class="stat-trend">Evaluasi model</div>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 16px; color: var(--color-text);">Data Pendukung</h3>
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Actual</th>
                    <th>Forecast</th>
                    <th>Error (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($labels as $i => $l)
                    <tr>
                        <td>{{ $l }}</td>
                        <td>{{ $actual[$i] ?? '-' }}</td>
                        <td>{{ $forecast[$i] ?? '-' }}</td>
                        <td>{{ $mapeList[$i-1] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
