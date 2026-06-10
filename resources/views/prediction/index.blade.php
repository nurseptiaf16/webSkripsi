@extends('layouts.app')

@section('title', 'Prediction')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Prediction</h1>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('prediction') }}" class="search-wrapper">
            <select name="olt_id" class="form-input" style="max-width: 200px;">
                <option value="">Pilih OLT</option>
                @foreach($olts as $olt)
                    <option value="{{ $olt->id }}" {{ request('olt_id') == $olt->id ? 'selected' : '' }}>{{ $olt->hostname }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Hitung Prediksi</button>
        </form>
    </div>

    <div class="stat-grid" style="margin-bottom: 24px;">
        <div class="stat-card card">
            <div class="stat-icon">
                <span class="icon">🎯</span>
            </div>
            <div class="stat-value">{{ isset($accuracy) ? $accuracy . '%' : '-' }}</div>
            <div class="stat-label">Skor Prediksi</div>
            <div class="stat-trend">Akurasi tinggi</div>
        </div>
        <div class="stat-card card">
            <div class="stat-icon">
                <span class="icon">🤖</span>
            </div>
            <div class="stat-value">{{ count($actual) > 0 ? 'Ready' : 'No Data' }}</div>
            <div class="stat-label">Status Model</div>
            <div class="stat-trend">Model aktif</div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <canvas id="predictionChart" style="max-height: 300px;"></canvas>
    </div>

    @if(count($futureLabels ?? []) > 0)
        @php
            $validForecasts = collect($futureForecasts ?? [])->filter(function ($v) {
                return $v !== null && $v !== '';
            })->map(fn($v) => (float) $v);
            $avgForecast = $validForecasts->count() ? round($validForecasts->avg(), 1) : 0;
            $maxForecast = $validForecasts->count() ? $validForecasts->max() : 0;
            $minForecast = $validForecasts->count() ? $validForecasts->min() : 0;
        @endphp

        <div class="card" style="margin-top: 24px;">
            <div style="display: flex;
                        align-items: center;
                        justify-content: space-between;
                        margin-bottom: 16px;
                        flex-wrap: wrap;
                        gap: 10px;">
                <div>
                    <h3 style="margin-bottom: 4px; color: var(--color-text);">
                        Hasil Prediksi Jumlah Pelanggan
                    </h3>
                    <div style="font-size: 12px; color: var(--color-text-muted);">
                        Estimasi total pelanggan FTTH untuk 1 bulan ke depan
                    </div>
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <span style="background: var(--color-primary-soft);
                                 color: var(--color-primary);
                                 padding: 6px 10px;
                                 border-radius: 999px;
                                 font-size: 12px;
                                 font-weight: 600;">
                        Avg {{ $avgForecast }}
                    </span>
                    <span style="background: rgba(34,197,94,0.12);
                                 color: rgb(34,197,94);
                                 padding: 6px 10px;
                                 border-radius: 999px;
                                 font-size: 12px;
                                 font-weight: 600;">
                        Max {{ $maxForecast }}
                    </span>
                    <span style="background: rgba(59,130,246,0.12);
                                 color: rgb(59,130,246);
                                 padding: 6px 10px;
                                 border-radius: 999px;
                                 font-size: 12px;
                                 font-weight: 600;">
                        Min {{ $minForecast }}
                    </span>
                </div>
            </div>

            <div style="display: grid; gap: 10px;">
                @foreach($futureLabels as $i => $fl)
                    @php $value = $futureForecasts[$i] ?? '-'; @endphp
                    <div style="display: grid;
                                grid-template-columns: 1.2fr 1fr auto;
                                align-items: center;
                                gap: 12px;
                                padding: 12px 14px;
                                border: 1px solid var(--color-border);
                                border-radius: 12px;
                                transition: all 0.2s ease;"
                         onmouseover="this.style.background='var(--color-surface-2)'; this.style.borderColor='transparent'"
                         onmouseout="this.style.background='transparent'; this.style.borderColor='var(--color-border)'">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="width: 34px; height: 34px;
                                         border-radius: 10px;
                                         background: var(--color-primary-soft);
                                         color: var(--color-primary);
                                         display: flex;
                                         align-items: center;
                                         justify-content: center;
                                         font-size: 14px;">
                                📅
                            </span>
                            <div>
                                <div style="font-size: 13px; font-weight: 600; color: var(--color-text);">
                                    {{ $fl }}
                                </div>
                                <div style="font-size: 11px; color: var(--color-text-muted);">
                                    Proyeksi pelanggan FTTH
                                </div>
                            </div>
                        </div>

                        <div style="text-align: left;">
                            <div style="font-size: 18px; font-weight: 700; color: var(--color-text);">
                                {{ $value }}
                            </div>
                            <div style="font-size: 11px; color: var(--color-text-muted);">
                                pelanggan (estimasi)
                            </div>
                        </div>

                        <div>
                            <span style="background: rgba(59,130,246,0.1);
                                         color: #3b82f6;
                                         padding: 6px 12px;
                                         border-radius: 999px;
                                         font-size: 12px;
                                         font-weight: 600;">
                                Prediksi
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const allLabels      = @json($allLabels ?? []);
            const actualData     = @json($actual ?? []);
            const forecastData   = @json($forecast ?? []);
            const futureData     = @json($futureForecasts ?? []);
            const futureLabels   = @json($futureLabels ?? []);
            const actualLen      = actualData.length;

            if (allLabels.length > 0) {
                // Pad actual & forecast dengan null 
                // agar panjang sama dengan allLabels
                const actualPadded   = [...actualData, 
                    ...Array(futureLabels.length).fill(null)];
                const forecastPadded = [...forecastData, 
                    ...Array(futureLabels.length).fill(null)];

                // Future dimulai dari index terakhir actual 
                // agar garis tersambung
                const futurePadded = [
                    ...Array(actualLen - 1).fill(null),
                    forecastData[forecastData.length - 1],
                    ...futureData
                ];

                const predictionChart = document.getElementById('predictionChart');
                new Chart(predictionChart, {
                    type: 'line',
                    data: {
                        labels: allLabels,
                        datasets: [
                            {
                                label: 'Actual',
                                data: actualPadded,
                                borderColor: 'rgba(225, 29, 72, 0.9)',
                                backgroundColor: 'rgba(225, 29, 72, 0.1)',
                                borderWidth: 2,
                                pointRadius: 4,
                                pointBackgroundColor: 'rgba(225, 29, 72, 0.9)',
                                tension: 0.3,
                                spanGaps: false,
                            },
                            {
                                label: 'Forecast (Historis)',
                                data: forecastPadded,
                                borderColor: 'rgba(34, 197, 94, 0.8)',
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                borderDash: [6, 3],
                                pointRadius: 3,
                                tension: 0.3,
                                spanGaps: false,
                            },
                            {
                                label: 'Forecast (Prediksi)',
                                data: futurePadded,
                                borderColor: 'rgba(59, 130, 246, 0.9)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2.5,
                                borderDash: [8, 4],
                                pointRadius: 5,
                                pointBackgroundColor: 'rgba(59, 130, 246, 0.9)',
                                pointStyle: 'triangle',
                                tension: 0.3,
                                spanGaps: true,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        if (context.parsed.y === null) return null;
                                        return context.dataset.label + ': ' 
                                            + context.parsed.y;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true },
                            x: {
                                ticks: { maxRotation: 45 }
                            }
                        }
                    }
                });
            } else {
                const predictionChart = document.getElementById('predictionChart');
                predictionChart.style.display = 'none';
                predictionChart.insertAdjacentHTML('afterend', '<p style="text-align: center; color: var(--color-text-muted); padding: 40px;">Pilih OLT lalu klik "Hitung Prediksi" untuk melihat hasil 1 bulan ke depan</p>');
            }
        </script>
    @endpush
@endsection
