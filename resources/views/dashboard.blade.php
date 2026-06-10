@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

{{-- PAGE HEADER --}}
<div style="display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;">
    <div>
        <h1 style="font-size: 22px;
                   font-weight: 700;
                   color: var(--color-text);
                   margin-bottom: 4px;">
            Selamat datang, {{ auth()->user()->name }}! 👋
        </h1>
        <p style="color: var(--color-text-muted);
                  font-size: 14px;">
            Monitoring dan analisis data pelanggan FTTH
        </p>
    </div>

    {{-- Quick info tanggal --}}
    <div style="background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: 10px;
                padding: 10px 16px;
                font-size: 13px;
                color: var(--color-text-muted);">
        📅 {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>
</div>

{{-- STAT CARDS --}}
<div style="display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;">

    {{-- Card 1: Total Pelanggan --}}
    <div style="background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: 16px;
                padding: 20px;
                box-shadow: var(--shadow);
                transition: all 0.2s ease;
                cursor: default;"
         onmouseover="this.style.transform='translateY(-4px)';
                      this.style.boxShadow='var(--shadow-md)'"
         onmouseout="this.style.transform='translateY(0)';
                     this.style.boxShadow='var(--shadow)'">
        <div style="display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    margin-bottom: 16px;">
            <div style="width: 44px; height: 44px;
                        background: var(--color-primary-soft);
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;">
                <svg width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="var(--color-primary)"
                     stroke-width="2" stroke-linecap="round">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 
                             0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                    <path d="M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
            <span style="font-size: 11px;
                         background: var(--color-primary-soft);
                         color: var(--color-primary);
                         padding: 3px 8px;
                         border-radius: 20px;
                         font-weight: 600;">
                FTTH
            </span>
        </div>
        <div style="font-size: 30px;
                    font-weight: 700;
                    color: var(--color-text);
                    margin-bottom: 4px;
                    line-height: 1;">
            {{ number_format($totalPelanggan) }}
        </div>
        <div style="font-size: 13px;
                    font-weight: 600;
                    color: var(--color-text-muted);
                    margin-bottom: 4px;">
            Total Pelanggan
        </div>
        <div style="font-size: 11px;
                    color: var(--color-text-muted);">
            Per {{ $latestMonth }}
        </div>
    </div>

    {{-- Card 2: Jumlah OLT --}}
    <div style="background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: 16px;
                padding: 20px;
                box-shadow: var(--shadow);
                transition: all 0.2s ease;
                cursor: default;"
         onmouseover="this.style.transform='translateY(-4px)';
                      this.style.boxShadow='var(--shadow-md)'"
         onmouseout="this.style.transform='translateY(0)';
                     this.style.boxShadow='var(--shadow)'">
        <div style="display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    margin-bottom: 16px;">
            <div style="width: 44px; height: 44px;
                        background: var(--color-primary-soft);
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;">
                <svg width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="var(--color-primary)"
                     stroke-width="2" stroke-linecap="round">
                    <rect x="2" y="2" width="20" height="8" rx="2"/>
                    <rect x="2" y="14" width="20" height="8" rx="2"/>
                    <line x1="6" y1="6" x2="6.01" y2="6"/>
                    <line x1="6" y1="18" x2="6.01" y2="18"/>
                </svg>
            </div>
            <span style="font-size: 11px;
                         background: var(--color-success-soft);
                         color: var(--color-success);
                         padding: 3px 8px;
                         border-radius: 20px;
                         font-weight: 600;">
                ● Aktif
            </span>
        </div>
        <div style="font-size: 30px;
                    font-weight: 700;
                    color: var(--color-text);
                    margin-bottom: 4px;
                    line-height: 1;">
            {{ $jumlahOlt }}
        </div>
        <div style="font-size: 13px;
                    font-weight: 600;
                    color: var(--color-text-muted);
                    margin-bottom: 4px;">
            Jumlah Perangkat OLT
        </div>
        <div style="font-size: 11px;
                    color: var(--color-success);">
            Perangkat aktif
        </div>
    </div>

    {{-- Card 3: Pertumbuhan --}}
    <div style="background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: 16px;
                padding: 20px;
                box-shadow: var(--shadow);
                transition: all 0.2s ease;
                cursor: default;"
         onmouseover="this.style.transform='translateY(-4px)';
                      this.style.boxShadow='var(--shadow-md)'"
         onmouseout="this.style.transform='translateY(0)';
                     this.style.boxShadow='var(--shadow)'">
        <div style="display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    margin-bottom: 16px;">
            <div style="width: 44px; height: 44px;
                        background: var(--color-primary-soft);
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;">
                <svg width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="var(--color-primary)"
                     stroke-width="2" stroke-linecap="round">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                    <polyline points="17 6 23 6 23 12"/>
                </svg>
            </div>
            @if($pertumbuhanPersen >= 0)
                <span style="font-size: 11px;
                             background: var(--color-success-soft);
                             color: var(--color-success);
                             padding: 3px 8px;
                             border-radius: 20px;
                             font-weight: 600;">
                    ▲ {{ abs($pertumbuhanPersen) }}%
                </span>
            @else
                <span style="font-size: 11px;
                             background: var(--color-danger-soft);
                             color: var(--color-danger);
                             padding: 3px 8px;
                             border-radius: 20px;
                             font-weight: 600;">
                    ▼ {{ abs($pertumbuhanPersen) }}%
                </span>
            @endif
        </div>
        @if($pertumbuhanPersen >= 0)
            <div style="font-size: 30px;
                        font-weight: 700;
                        color: var(--color-success);
                        margin-bottom: 4px;
                        line-height: 1;">
                +{{ $pertumbuhanPersen }}%
            </div>
        @else
            <div style="font-size: 30px;
                        font-weight: 700;
                        color: var(--color-danger);
                        margin-bottom: 4px;
                        line-height: 1;">
                {{ $pertumbuhanPersen }}%
            </div>
        @endif
        <div style="font-size: 13px;
                    font-weight: 600;
                    color: var(--color-text-muted);
                    margin-bottom: 4px;">
            Pertumbuhan Bulan Terakhir
        </div>
        <div style="font-size: 11px;
                    color: var(--color-text-muted);">
            {{ $pertumbuhanBulan }}
        </div>
    </div>

    {{-- Card 4: Prediksi --}}
    <div style="background: var(--color-primary);
                border-radius: 16px;
                padding: 20px;
                box-shadow: 0 8px 24px rgba(225,29,72,0.25);
                transition: all 0.2s ease;
                cursor: default;"
         onmouseover="this.style.transform='translateY(-4px)'"
         onmouseout="this.style.transform='translateY(0)'">
        <div style="display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    margin-bottom: 16px;">
            <div style="width: 44px; height: 44px;
                        background: rgba(255,255,255,0.2);
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;">
                <svg width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="#ffffff"
                     stroke-width="2" stroke-linecap="round">
                    <line x1="18" y1="20" x2="18" y2="10"/>
                    <line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                    <line x1="2" y1="20" x2="22" y2="20"/>
                </svg>
            </div>
            <span style="font-size: 11px;
                         background: rgba(255,255,255,0.2);
                         color: #ffffff;
                         padding: 3px 8px;
                         border-radius: 20px;
                         font-weight: 600;">
                Prediksi
            </span>
        </div>
        <div style="font-size: 30px;
                    font-weight: 700;
                    color: #ffffff;
                    margin-bottom: 4px;
                    line-height: 1;">
            {{ number_format($prediksiVal) }}
        </div>
        <div style="font-size: 13px;
                    font-weight: 600;
                    color: rgba(255,255,255,0.8);
                    margin-bottom: 4px;">
            Prediksi Bulan Berikutnya
        </div>
        <div style="font-size: 11px;
                    color: rgba(255,255,255,0.7);">
            {{ $prediksiLabel }}
        </div>
    </div>

</div>

{{-- ROW 2: FILTER + GRAFIK --}}
<div style="display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 16px;
            margin-bottom: 24px;">

    {{-- Filter Panel --}}
    <div style="background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: 16px;
                padding: 20px;
                box-shadow: var(--shadow);">

        <h3 style="font-size: 14px;
                   font-weight: 700;
                   color: var(--color-text);
                   margin-bottom: 16px;
                   display: flex;
                   align-items: center;
                   gap: 8px;">
            <svg width="16" height="16" viewBox="0 0 24 24"
                 fill="none" stroke="var(--color-primary)"
                 stroke-width="2">
                <polygon points="22 3 2 3 10 12.46 
                                  10 19 14 21 14 12.46 22 3"/>
            </svg>
            Filter Data
        </h3>

        <form method="GET" action="{{ route('dashboard') }}">
            <div style="margin-bottom: 14px;">
                <label style="display: block;
                              font-size: 11px;
                              font-weight: 700;
                              color: var(--color-text-muted);
                              margin-bottom: 6px;
                              text-transform: uppercase;
                              letter-spacing: 0.05em;">
                    Periode (Tahun)
                </label>
                <select name="year"
                        style="width: 100%;
                               padding: 10px 14px;
                               border: 1px solid var(--color-border);
                               border-radius: 10px;
                               background: var(--color-surface-2);
                               font-size: 13px;
                               color: var(--color-text);
                               cursor: pointer;">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}"
                            {{ request('year') == $year 
                                ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block;
                              font-size: 11px;
                              font-weight: 700;
                              color: var(--color-text-muted);
                              margin-bottom: 6px;
                              text-transform: uppercase;
                              letter-spacing: 0.05em;">
                    Perangkat OLT
                </label>
                <select name="olt_id"
                        style="width: 100%;
                               padding: 10px 14px;
                               border: 1px solid var(--color-border);
                               border-radius: 10px;
                               background: var(--color-surface-2);
                               font-size: 13px;
                               color: var(--color-text);
                               cursor: pointer;">
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

            <button type="submit"
                    style="width: 100%;
                           padding: 11px;
                           background: var(--color-primary);
                           color: white;
                           border: none;
                           border-radius: 10px;
                           font-size: 14px;
                           font-weight: 600;
                           cursor: pointer;
                           transition: all 0.2s ease;"
                    onmouseover="this.style.background=
                        'var(--color-primary-hover)'"
                    onmouseout="this.style.background=
                        'var(--color-primary)'">
                Terapkan Filter
            </button>

            @if(request('year') || request('olt_id'))
                <a href="{{ route('dashboard') }}"
                   style="display: block;
                          text-align: center;
                          margin-top: 10px;
                          padding: 10px;
                          border: 1px solid var(--color-border);
                          border-radius: 10px;
                          color: var(--color-text-muted);
                          text-decoration: none;
                          font-size: 13px;
                          transition: all 0.2s ease;"
                   onmouseover="this.style.background=
                       'var(--color-surface-2)'"
                   onmouseout="this.style.background='transparent'">
                    Reset Filter
                </a>
            @endif
        </form>

        {{-- Info filter aktif --}}
        @if(request('olt_id'))
            <div style="margin-top: 16px;
                        padding: 12px;
                        background: var(--color-primary-soft);
                        border-radius: 10px;
                        font-size: 12px;
                        color: var(--color-primary);">
                <strong>Filter aktif:</strong><br>
                @if(request('olt_id'))
                    OLT: {{ $olts->find(request('olt_id'))
                        ->hostname ?? '' }}
                @endif
                @if(request('year'))
                    <br>Tahun: {{ request('year') }}
                @endif
            </div>
        @endif
    </div>

    {{-- Grafik Bar --}}
    <div style="background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: 16px;
                padding: 20px;
                box-shadow: var(--shadow);">

        <div style="display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 20px;">
            <h3 style="font-size: 14px;
                       font-weight: 700;
                       color: var(--color-text);">
                Total Pelanggan FTTH per Perangkat OLT
            </h3>

            {{-- Toggle chart type --}}
            <div style="display: flex;
                        gap: 6px;">
                <button onclick="switchChart('bar')"
                        id="btnBar"
                        style="padding: 6px 12px;
                               border-radius: 8px;
                               border: 1px solid var(--color-primary);
                               background: var(--color-primary);
                               color: white;
                               font-size: 12px;
                               cursor: pointer;
                               transition: all 0.2s;">
                    Bar
                </button>
                <button onclick="switchChart('horizontalBar')"
                        id="btnHBar"
                        style="padding: 6px 12px;
                               border-radius: 8px;
                               border: 1px solid var(--color-border);
                               background: white;
                               color: var(--color-text-muted);
                               font-size: 12px;
                               cursor: pointer;
                               transition: all 0.2s;">
                    Horizontal
                </button>
                <button onclick="switchChart('pie')"
                        id="btnPie"
                        style="padding: 6px 12px;
                               border-radius: 8px;
                               border: 1px solid var(--color-border);
                               background: white;
                               color: var(--color-text-muted);
                               font-size: 12px;
                               cursor: pointer;
                               transition: all 0.2s;">
                    Pie
                </button>
            </div>
        </div>

        <div style="position: relative; height: 280px;">
            <div id="dashboardChartData"
                 data-labels='@json($chartLabels)'
                 data-total='@json($chartData)'
                 data-b2c='@json($chartB2C)'
                 data-b2b='@json($chartB2B)'
                 style="display: none;"></div>
            <canvas id="dashboardChart"></canvas>
        </div>
    </div>

</div>

{{-- ROW 3: TABEL RINGKASAN + MINI STATS --}}
<div style="display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 16px;">

    {{-- Tabel Ringkasan --}}
    <div style="background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: 16px;
                padding: 20px;
                box-shadow: var(--shadow);">
        <h3 style="font-size: 14px;
                   font-weight: 700;
                   color: var(--color-text);
                   margin-bottom: 16px;">
            Tabel Ringkasan Data Pelanggan
        </h3>
        <table style="width: 100%;
                      border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align: left;
                               padding: 10px 12px;
                               font-size: 11px;
                               font-weight: 700;
                               color: var(--color-text-muted);
                               text-transform: uppercase;
                               letter-spacing: 0.05em;
                               border-bottom: 2px solid 
                                   var(--color-border);">
                        Hostname OLT
                    </th>
                    <th style="text-align: right;
                               padding: 10px 12px;
                               font-size: 11px;
                               font-weight: 700;
                               color: var(--color-text-muted);
                               text-transform: uppercase;
                               letter-spacing: 0.05em;
                               border-bottom: 2px solid 
                                   var(--color-border);">
                        B2C
                    </th>
                    <th style="text-align: right;
                               padding: 10px 12px;
                               font-size: 11px;
                               font-weight: 700;
                               color: var(--color-text-muted);
                               text-transform: uppercase;
                               letter-spacing: 0.05em;
                               border-bottom: 2px solid 
                                   var(--color-border);">
                        B2B
                    </th>
                    <th style="text-align: right;
                               padding: 10px 12px;
                               font-size: 11px;
                               font-weight: 700;
                               color: var(--color-text-muted);
                               text-transform: uppercase;
                               letter-spacing: 0.05em;
                               border-bottom: 2px solid 
                                   var(--color-border);">
                        Total
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($oltSummary as $i => $row)
                    <tr style="transition: background 0.15s ease;"
                        onmouseover="this.style.background=
                            'var(--color-primary-soft)'"
                        onmouseout="this.style.background=
                            'transparent'">
                        <td style="padding: 12px;
                                   border-bottom: 1px solid 
                                       var(--color-border);
                                   font-size: 13px;
                                   font-weight: 500;
                                   color: var(--color-text);
                                   display: flex;
                                   align-items: center;
                                   gap: 8px;">
                            <span style="width: 8px;
                                         height: 8px;
                                         border-radius: 50%;
                                         background: var(--color-primary);
                                         display: inline-block;
                                         flex-shrink: 0;">
                            </span>
                            {{ $row['hostname'] }}
                        </td>
                        <td style="padding: 12px;
                                   border-bottom: 1px solid 
                                       var(--color-border);
                                   font-size: 13px;
                                   text-align: right;
                                   color: var(--color-text-muted);">
                            {{ number_format($row['b2c']) }}
                        </td>
                        <td style="padding: 12px;
                                   border-bottom: 1px solid 
                                       var(--color-border);
                                   font-size: 13px;
                                   text-align: right;
                                   color: var(--color-text-muted);">
                            {{ number_format($row['b2b']) }}
                        </td>
                        <td style="padding: 12px;
                                   border-bottom: 1px solid 
                                       var(--color-border);
                                   font-size: 13px;
                                   text-align: right;
                                   font-weight: 700;
                                   color: var(--color-text);">
                            {{ number_format($row['total']) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            style="text-align: center;
                                   padding: 40px;
                                   color: var(--color-text-muted);
                                   font-size: 14px;">
                            Tidak ada data pelanggan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mini Stats Panel --}}
    <div style="display: flex;
                flex-direction: column;
                gap: 12px;">

        {{-- B2C vs B2B --}}
        <div style="background: var(--color-surface);
                    border: 1px solid var(--color-border);
                    border-radius: 16px;
                    padding: 20px;
                    box-shadow: var(--shadow);">
            <h3 style="font-size: 13px;
                       font-weight: 700;
                       color: var(--color-text);
                       margin-bottom: 14px;">
                Komposisi Pelanggan
            </h3>
            @php
                $totalB2C = $oltSummary->sum('b2c');
                $totalB2B = $oltSummary->sum('b2b');
                $grandTotal = $totalB2C + $totalB2B;
                $pctB2C = $grandTotal > 0 
                    ? round(($totalB2C/$grandTotal)*100) : 0;
                $pctB2B = $grandTotal > 0 
                    ? round(($totalB2B/$grandTotal)*100) : 0;
            @endphp

            {{-- Progress bar B2C --}}
            <div style="margin-bottom: 12px;">
                <div style="display: flex;
                            justify-content: space-between;
                            margin-bottom: 6px;">
                    <span style="font-size: 12px;
                                 color: var(--color-text-muted);">
                        B2C
                    </span>
                    <span style="font-size: 12px;
                                 font-weight: 600;
                                 color: var(--color-primary);">
                        {{ $pctB2C }}%
                    </span>
                </div>
                <div style="height: 8px;
                            background: var(--color-surface-2);
                            border-radius: 20px;
                            overflow: hidden;">
                    <div data-progress="{{ $pctB2C }}"
                         style="height: 100%;
                                width: 0;
                                background: var(--color-primary);
                                border-radius: 20px;
                                transition: width 1s ease;">
                    </div>
                </div>
                <div style="font-size: 11px;
                            color: var(--color-text-muted);
                            margin-top: 4px;">
                    {{ number_format($totalB2C) }} pelanggan
                </div>
            </div>

            {{-- Progress bar B2B --}}
            <div>
                <div style="display: flex;
                            justify-content: space-between;
                            margin-bottom: 6px;">
                    <span style="font-size: 12px;
                                 color: var(--color-text-muted);">
                        B2B
                    </span>
                    <span style="font-size: 12px;
                                 font-weight: 600;
                                 color: #3b82f6;">
                        {{ $pctB2B }}%
                    </span>
                </div>
                <div style="height: 8px;
                            background: var(--color-surface-2);
                            border-radius: 20px;
                            overflow: hidden;">
                    <div data-progress="{{ $pctB2B }}"
                         style="height: 100%;
                                width: 0;
                                background: #3b82f6;
                                border-radius: 20px;
                                transition: width 1s ease;">
                    </div>
                </div>
                <div style="font-size: 11px;
                            color: var(--color-text-muted);
                            margin-top: 4px;">
                    {{ number_format($totalB2B) }} pelanggan
                </div>
            </div>
        </div>

        {{-- Status OLT --}}
        <div style="background: var(--color-surface);
                    border: 1px solid var(--color-border);
                    border-radius: 16px;
                    padding: 20px;
                    box-shadow: var(--shadow);">
            <h3 style="font-size: 13px;
                       font-weight: 700;
                       color: var(--color-text);
                       margin-bottom: 14px;">
                Status Perangkat OLT
            </h3>
            @php
                $oltActive  = $olts->where('status','active')->count();
                $oltMaint   = $olts->where('status','maintenance')->count();
                $oltInactive = $olts->where('status','non-active')->count();
            @endphp
            <div style="display: flex;
                        flex-direction: column;
                        gap: 10px;">
                <div style="display: flex;
                            justify-content: space-between;
                            align-items: center;">
                    <div style="display: flex;
                                align-items: center;
                                gap: 8px;
                                font-size: 13px;
                                color: var(--color-text-muted);">
                        <span style="width: 10px; height: 10px;
                                     border-radius: 50%;
                                     background: var(--color-success);
                                     display: inline-block;">
                        </span>
                        Active
                    </div>
                    <strong style="font-size: 14px;
                                   color: var(--color-success);">
                        {{ $oltActive }}
                    </strong>
                </div>
                <div style="display: flex;
                            justify-content: space-between;
                            align-items: center;">
                    <div style="display: flex;
                                align-items: center;
                                gap: 8px;
                                font-size: 13px;
                                color: var(--color-text-muted);">
                        <span style="width: 10px; height: 10px;
                                     border-radius: 50%;
                                     background: var(--color-warning);
                                     display: inline-block;">
                        </span>
                        Maintenance
                    </div>
                    <strong style="font-size: 14px;
                                   color: var(--color-warning);">
                        {{ $oltMaint }}
                    </strong>
                </div>
                <div style="display: flex;
                            justify-content: space-between;
                            align-items: center;">
                    <div style="display: flex;
                                align-items: center;
                                gap: 8px;
                                font-size: 13px;
                                color: var(--color-text-muted);">
                        <span style="width: 10px; height: 10px;
                                     border-radius: 50%;
                                     background: var(--color-danger);
                                     display: inline-block;">
                        </span>
                        Non-Active
                    </div>
                    <strong style="font-size: 14px;
                                   color: var(--color-danger);">
                        {{ $oltInactive }}
                    </strong>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const dataEl = document.getElementById('dashboardChartData');
const chartLabels = JSON.parse(dataEl?.dataset.labels || '[]');
const chartData   = JSON.parse(dataEl?.dataset.total || '[]');
const chartB2C    = JSON.parse(dataEl?.dataset.b2c || '[]');
const chartB2B    = JSON.parse(dataEl?.dataset.b2b || '[]');

const colors = [
    'rgba(225,29,72,0.8)',
    'rgba(59,130,246,0.8)',
    'rgba(22,163,74,0.8)',
    'rgba(217,119,6,0.8)',
    'rgba(147,51,234,0.8)',
    'rgba(20,184,166,0.8)',
];

let currentChart = null;

function createChart(type) {
    const ctx = document.getElementById('dashboardChart');

    if (currentChart) currentChart.destroy();

    const isHorizontal = type === 'horizontalBar';
    const isPie        = type === 'pie';

    currentChart = new Chart(ctx, {
        type: isPie ? 'doughnut' : 'bar',
        data: {
            labels: chartLabels,
            datasets: isPie ? [
                {
                    data: chartData,
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverOffset: 8,
                }
            ] : [
                {
                    label: 'B2C',
                    data: chartB2C,
                    backgroundColor: 'rgba(225,29,72,0.8)',
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'B2B',
                    data: chartB2B,
                    backgroundColor: 'rgba(59,130,246,0.8)',
                    borderRadius: 6,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: isHorizontal ? 'y' : 'x',
            plugins: {
                legend: {
                    display: isPie,
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' ' + context.dataset.label + 
                                ': ' + 
                                context.parsed.y?.toLocaleString() ||
                                context.parsed.toLocaleString();
                        }
                    }
                }
            },
            scales: isPie ? {} : {
                x: {
                    grid: { display: false },
                    stacked: true,
                    ticks: { maxRotation: 30 }
                },
                y: {
                    beginAtZero: true,
                    stacked: true,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    title: {
                        display: !isHorizontal,
                        text: 'Jumlah Pelanggan',
                        color: '#64748b',
                        font: { size: 11 }
                    }
                }
            },
            animation: {
                duration: 600,
                easing: 'easeInOutQuart'
            }
        }
    });
}

function switchChart(type) {
    createChart(type);

    const btns = ['btnBar','btnHBar','btnPie'];
    const types = ['bar','horizontalBar','pie'];

    btns.forEach((btn, i) => {
        const el = document.getElementById(btn);
        if (types[i] === type) {
            el.style.background = 'var(--color-primary)';
            el.style.color = 'white';
            el.style.borderColor = 'var(--color-primary)';
        } else {
            el.style.background = 'white';
            el.style.color = 'var(--color-text-muted)';
            el.style.borderColor = 'var(--color-border)';
        }
    });
}

// Init
createChart('bar');

document.querySelectorAll('[data-progress]').forEach((el) => {
    const pct = Number(el.getAttribute('data-progress') || 0);
    el.style.width = pct + '%';
});
</script>
@endpush
