@extends('layouts.app')

@section('title', 'Reports')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Reports</h1>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <form id="reportFilterForm"
                  method="GET"
                  action="{{ route('reports') }}"
                  style="display: flex; align-items: center;">

                {{-- Filter OLT --}}
                <select name="olt_id"
                        class="form-input"
                        style="width: 100%; padding: 10px 16px; border: 1px solid var(--color-border); border-radius: 10px; background: var(--color-surface); font-size: 14px; color: var(--color-text); cursor: pointer;">
                    <option value="">-- Pilih OLT --</option>
                    @foreach($olts as $olt)
                        <option value="{{ $olt->id }}" {{ request('olt_id') == $olt->id ? 'selected' : '' }}>
                            {{ $olt->hostname }}
                        </option>
                    @endforeach
                </select>
            </form>

            <div style="display: flex; align-items: center; gap: 12px;">
                {{-- Tombol Tampilkan --}}
                <button type="submit" class="btn btn-primary" form="reportFilterForm">
                    Tampilkan Laporan
                </button>

                @if(request('olt_id') && count($data) > 0)
                    <form id="exportForm"
                          method="POST"
                          action="{{ route('reports.export') }}"
                          style="display: contents;">
                        @csrf
                        <input type="hidden" name="olt_id" value="{{ request('olt_id') }}">
                        <input type="hidden" id="chartImageInput" name="chart_image" value="">

                        <button type="button"
                                onclick="doExportPDF()"
                                class="btn btn-primary"
                                style="display: inline-flex; align-items: center; gap: 8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                            </svg>
                            Export PDF
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @if(request('olt_id'))
        @if(count($data) > 0)
            <div class="stat-grid" style="margin-bottom: 24px;">
                <div class="stat-card card">
                    <div class="stat-icon">
                        <span class="icon">📊</span>
                    </div>
                    <div class="stat-value">{{ $summary['total'] }}</div>
                    <div class="stat-label">Total Pelanggan</div>
                    <div class="stat-trend">Total data</div>
                </div>
                <div class="stat-card card">
                    <div class="stat-icon">
                        <span class="icon">📈</span>
                    </div>
                    <div class="stat-value">{{ $summary['avgGrowth'] }}%</div>
                    <div class="stat-label">Rata-rata Pertumbuhan</div>
                    <div class="stat-trend">Rata-rata pertumbuhan</div>
                </div>
                <div class="stat-card card">
                    <div class="stat-icon">
                        <span class="icon">🎯</span>
                    </div>
                    <div class="stat-value">{{ $summary['mape'] }}%</div>
                    <div class="stat-label">MAPE</div>
                    <div class="stat-trend">Mean Absolute Percentage Error</div>
                </div>
                <div class="stat-card card">
                    <div class="stat-icon">
                        <span class="icon">🏷️</span>
                    </div>
                    <div class="stat-value">{{ $summary['kategori'] }}</div>
                    <div class="stat-label">Kategori Akurasi</div>
                    <div class="stat-trend">Kategori akurasi</div>
                </div>
            </div>

            <div class="card" style="margin: 24px 0;">
                <h3 style="margin-bottom: 16px; color: var(--color-text);">
                    Grafik Pertumbuhan Pelanggan
                </h3>
                <canvas id="reportChart" style="max-height: 350px;"></canvas>
                <div id="reportData"
                     data-labels='@json(array_column($data, 'periode'))'
                     data-actual='@json(array_column($data, 'actual'))'
                     data-forecast='@json(array_column($data, 'forecast'))'
                     data-growth='@json(array_column($data, 'percent'))'
                     style="display: none;"></div>
            </div>

            <div class="card">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Periode</th>
                            <th>Actual</th>
                            <th>Forecast</th>
                            <th>Growth</th>
                            <th>Pertumbuhan (%)</th>
                            <th>Akurasi (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row['periode'] }}</td>
                                <td>{{ $row['actual'] }}</td>
                                <td>{{ $row['forecast'] }}</td>
                                <td>
                                    <span class="{{ $row['growth'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $row['growth'] >= 0 ? '+' : '' }}{{ $row['growth'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="{{ $row['percent'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $row['percent'] >= 0 ? '+' : '' }}{{ $row['percent'] }}%
                                    </span>
                                </td>
                                <td>{{ $row['akurasi'] }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card" style="text-align: center; padding: 60px 20px;">
                <p style="color: var(--color-text-muted); font-size: 16px;">
                    Tidak ada data untuk OLT yang dipilih.
                </p>
            </div>
        @endif
    @else
        <div class="card" style="text-align: center; padding: 60px 20px;">
            <p style="color: var(--color-text-muted); font-size: 16px;">
                Silakan pilih OLT untuk menampilkan laporan.
            </p>
        </div>
    @endif

    @push('scripts')
        @if(count($data) > 0)
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                let reportChartInstance = null;

                const reportDataEl = document.getElementById('reportData');
                const reportLabels = JSON.parse(reportDataEl?.dataset.labels || '[]');
                const reportActual = JSON.parse(reportDataEl?.dataset.actual || '[]');
                const reportForecast = JSON.parse(reportDataEl?.dataset.forecast || '[]');
                const reportGrowth = JSON.parse(reportDataEl?.dataset.growth || '[]');

                const reportCanvas = document.getElementById('reportChart');
                const exportDpr = 3;

                reportChartInstance = new Chart(reportCanvas, {
                    type: 'bar',
                    data: {
                        labels: reportLabels,
                        datasets: [
                            {
                                label: 'Actual',
                                data: reportActual,
                                backgroundColor: 'rgba(225, 29, 72, 0.8)',
                                borderRadius: 6,
                                order: 2,
                            },
                            {
                                label: 'Forecast',
                                data: reportForecast,
                                type: 'line',
                                borderColor: 'rgba(34, 197, 94, 0.9)',
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                borderDash: [6, 3],
                                pointRadius: 4,
                                tension: 0.3,
                                order: 1,
                            },
                            {
                                label: 'Pertumbuhan (%)',
                                data: reportGrowth,
                                type: 'line',
                                borderColor: 'rgba(59, 130, 246, 0.9)',
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                pointRadius: 4,
                                tension: 0.3,
                                yAxisID: 'y1',
                                order: 0,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        devicePixelRatio: exportDpr,
                        animation: {
                            duration: 500,
                            onComplete: function() {
                                if (reportCanvas) {
                                    document.getElementById(
                                        'chartImageInput'
                                    ).value = reportCanvas.toDataURL('image/png', 1.0);
                                }
                            }
                        },
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label + ': ';
                                        if (context.dataset.yAxisID === 'y1') {
                                            label += context.parsed.y + '%';
                                        } else {
                                            label += context.parsed.y;
                                        }
                                        return label;
                                    }
                                }
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

                function doExportPDF() {
                    const canvas = document.getElementById('reportChart');
                    const form = document.getElementById('exportForm');
                    const input = document.getElementById('chartImageInput');

                    if (!form || !input) {
                        alert('Form export belum tersedia. ' +
                              'Silakan refresh halaman.');
                        return;
                    }

                    if (!canvas) {
                        alert('Grafik belum tersedia. ' +
                              'Silakan tunggu grafik selesai dimuat.');
                        return;
                    }

                    setTimeout(function() {
                        const imageData = canvas.toDataURL('image/png', 1.0);
                        input.value = imageData;

                        if (!imageData || imageData === 'data:,') {
                            alert('Gagal mengambil gambar grafik. ' +
                                  'Coba refresh halaman.');
                            return;
                        }

                        form.submit();
                    }, 300);
                }
            </script>
        @endif
    @endpush
@endsection

