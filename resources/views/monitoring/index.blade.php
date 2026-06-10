@extends('layouts.app')

@section('title', 'Monitoring')

@section('content')

<div class="page-header" 
     style="margin-bottom: 8px;">
    <h1 class="page-title">
        Monitoring Pertumbuhan Pelanggan
    </h1>
    <p style="color: var(--color-text-muted); 
              font-size: 14px; margin-top: 4px;">
        Pantau pertumbuhan pelanggan secara real-time
    </p>
</div>

{{-- FILTER --}}
<div class="card" style="margin-bottom: 24px;">
    <form method="GET" 
          action="{{ route('monitoring') }}"
          style="display: flex; 
                 gap: 12px; 
                 align-items: flex-end;
                 flex-wrap: wrap;">

        {{-- Filter Tahun --}}
        <div>
            <label style="display: block;
                          font-size: 12px;
                          font-weight: 600;
                          color: var(--color-text-muted);
                          margin-bottom: 6px;
                          text-transform: uppercase;
                          letter-spacing: 0.05em;">
                Periode Waktu
            </label>
            <input type="number"
                   name="year"
                   value="{{ request('year') }}"
                   placeholder="Contoh: 2024"
                   min="2000" max="2099"
                   style="padding: 10px 16px;
                          border: 1px solid var(--color-border);
                          border-radius: 10px;
                          background: var(--color-surface);
                          font-size: 14px;
                          color: var(--color-text);
                          width: 160px;">
        </div>

        {{-- Filter OLT --}}
        <div>
            <label style="display: block;
                          font-size: 12px;
                          font-weight: 600;
                          color: var(--color-text-muted);
                          margin-bottom: 6px;
                          text-transform: uppercase;
                          letter-spacing: 0.05em;">
                Perangkat OLT
            </label>
            <select name="olt_id"
                    style="padding: 10px 16px;
                           border: 1px solid var(--color-border);
                           border-radius: 10px;
                           background: var(--color-surface);
                           font-size: 14px;
                           color: var(--color-text);
                           cursor: pointer;
                           width: 220px;">
                <option value="">Semua OLT</option>
                @foreach($olts as $olt)
                    <option value="{{ $olt->id }}"
                        {{ request('olt_id') == $olt->id 
                            ? 'selected' : '' }}>
                        {{ $olt->hostname }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tombol --}}
        <button type="submit" class="btn btn-primary"
                style="padding: 10px 24px;">
            Terapkan Filter
        </button>

        @if(request('olt_id') || request('year'))
            <a href="{{ route('monitoring') }}"
               style="padding: 10px 20px;
                      border: 1px solid var(--color-border);
                      border-radius: 10px;
                      color: var(--color-text-muted);
                      text-decoration: none;
                      font-size: 14px;">
                Reset
            </a>
        @endif

    </form>
</div>

{{-- STAT CARDS --}}
<div class="stat-grid" style="margin-bottom: 24px;">

    <div class="stat-card card">
        <div style="display: flex; 
                    justify-content: space-between;
                    align-items: flex-start;">
            <div class="stat-icon">
                <span class="icon">📈</span>
            </div>
            <span class="{{ $totalGrowth >= 0 ? 'text-success' : 'text-danger' }}"
                  style="font-size: 12px;
                         font-weight: 600;">
                {{ $totalGrowth >= 0 ? '+' : '' }}
                {{ $totalGrowth }} pelanggan
            </span>
        </div>
        <div class="stat-value">
            {{ $totalGrowth >= 0 ? '+' : '' }}
            {{ $totalGrowth }}
        </div>
        <div class="stat-label">
            Pertumbuhan Total
            @if(request('year'))
                ({{ request('year') }})
            @endif
        </div>
    </div>

    <div class="stat-card card">
        <div style="display: flex; 
                    justify-content: space-between;
                    align-items: flex-start;">
            <div class="stat-icon">
                <span class="icon">📊</span>
            </div>
            <span class="{{ $avgGrowth >= 0 ? 'text-success' : 'text-danger' }}"
                  style="font-size: 12px;
                         font-weight: 600;">
                {{ $avgGrowth >= 0 ? '+' : '' }}
                {{ round($avgGrowth, 1) }}%
            </span>
        </div>
        <div class="stat-value">
            {{ round($avgGrowth, 1) }}%
        </div>
        <div class="stat-label">Rata-rata Pertumbuhan/Bulan</div>
    </div>

    <div class="stat-card card">
        <div style="display: flex; 
                    justify-content: space-between;
                    align-items: flex-start;">
            <div class="stat-icon">
                <span class="icon">🏆</span>
            </div>
            <span style="font-size: 12px;
                         font-weight: 600;
                         color: var(--color-success);">
                +{{ $maxGrowth }}%
            </span>
        </div>
        <div class="stat-value">+{{ $maxGrowth }}%</div>
        <div class="stat-label">
            Pertumbuhan Tertinggi
            @if($maxGrowthMonth)
                <br>
                <small style="font-size: 11px;
                              color: var(--color-text-muted);">
                    {{ $maxGrowthMonth }}
                </small>
            @endif
        </div>
    </div>

</div>

{{-- GRAFIK --}}
<div class="card" style="margin-bottom: 24px;">
    <div style="display: flex; 
                justify-content: space-between;
                align-items: center;
                margin-bottom: 16px;">
        <h3 style="color: var(--color-text); 
                   font-size: 16px;">
            Grafik Pertumbuhan Pelanggan
        </h3>
    </div>
    <canvas id="monitoringChart" 
            style="max-height: 320px;">
    </canvas>
</div>

{{-- TABEL DETAIL --}}
<div class="card">
    <h3 style="margin-bottom: 16px; 
               color: var(--color-text);
               font-size: 16px;">
        Detail Pertumbuhan Per Bulan
    </h3>
    <table class="table-modern">
        <thead>
            <tr>
                <th>Periode</th>
                <th>Jumlah Pelanggan</th>
                <th>Pertumbuhan</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            @forelse($labels as $i => $l)
                <tr>
                    <td>{{ $l }}</td>
                    <td>{{ number_format($values[$i]) }}</td>
                    <td>
                        <span class="{{ ($growth[$i] ?? 0) >= 0 ? 'text-success' : 'text-danger' }} font-medium">
                            {{ ($growth[$i] ?? 0) >= 0 ? '+' : '' }}
                            {{ $growth[$i] ?? 0 }}
                        </span>
                    </td>
                    <td>
                        <span class="{{ ($percent[$i] ?? 0) >= 0 ? 'text-success' : 'text-danger' }} font-medium">
                            {{ ($percent[$i] ?? 0) >= 0 ? '+' : '' }}
                            {{ $percent[$i] ?? 0 }}%
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" 
                        style="text-align: center;
                               padding: 40px;
                               color: var(--color-text-muted);">
                        Tidak ada data. Silakan pilih filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const monitoringChart  = document.getElementById('monitoringChart');
const monitoringLabels = JSON.parse('{!! json_encode($labels) !!}');
const monitoringData   = JSON.parse('{!! json_encode($values) !!}');
const monitoringGrowth = JSON.parse('{!! json_encode($percent) !!}');

if (monitoringLabels && monitoringLabels.length > 0) {
    new Chart(monitoringChart, {
        type: 'line',
        data: {
            labels: monitoringLabels,
            datasets: [
                {
                    label: 'Jumlah Pelanggan',
                    data: monitoringData,
                    borderColor: 'rgba(225, 29, 72, 0.9)',
                    backgroundColor: 'rgba(225, 29, 72, 0.08)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(225, 29, 72, 0.9)',
                    yAxisID: 'y',
                },
                {
                    label: 'Pertumbuhan (%)',
                    data: monitoringGrowth,
                    borderColor: 'rgba(59, 130, 246, 0.8)',
                    backgroundColor: 'transparent',
                    borderDash: [5, 3],
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 3,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: { 
                    position: 'top',
                    labels: { usePointStyle: true }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Jumlah Pelanggan'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Pertumbuhan (%)'
                    },
                    grid: { drawOnChartArea: false }
                },
                x: {
                    ticks: { maxRotation: 45 }
                }
            }
        }
    });
} else {
    monitoringChart.style.display = 'none';
    monitoringChart.insertAdjacentHTML('afterend', 
        '<p style="text-align:center; color:var(--color-text-muted); padding:60px;">'+
        'Tidak ada data untuk ditampilkan.</p>'
    );
}
</script>
@endpush
