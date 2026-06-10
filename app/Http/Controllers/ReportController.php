<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Olt;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\NotificationHelper;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $olts = Olt::all();

        $data = [];
        $summary = [
            'total' => 0,
            'avgGrowth' => 0,
            'mape' => 0,
            'kategori' => '-'
        ];

        if ($request->olt_id) {

            $urutanBulan = [
                'januari'   => 1,
                'februari'  => 2,
                'febuari'   => 2,
                'maret'     => 3,
                'april'     => 4,
                'mei'       => 5,
                'juni'      => 6,
                'juli'      => 7,
                'agustus'   => 8,
                'september' => 9,
                'oktober'   => 10,
                'november'  => 11,
                'desember'  => 12,
            ];

            $customers = Customer::where('olt_id', $request->olt_id)
                ->orderBy('year', 'asc')
                ->get()
                ->sort(function ($a, $b) use ($urutanBulan) {
                    if ($a->year !== $b->year) {
                        return $a->year <=> $b->year;
                    }

                    $bulanA = $urutanBulan[strtolower($a->month)] ?? 0;
                    $bulanB = $urutanBulan[strtolower($b->month)] ?? 0;

                    return $bulanA <=> $bulanB;
                })->values();

            $actual = [];
            $labels = [];

            foreach ($customers as $c) {
                $labels[] = $c->month . ' ' . $c->year;
                $actual[] = $c->total_customers;
            }

            // ================= PREDIKSI =================
            $alpha = 0.9;
            $forecast = [];
            $growth = [];
            $percent = [];
            $mapeList = [];

            if (count($actual) > 0) {

                $forecast[0] = $actual[0];
                $prev = null;

                for ($i = 0; $i < count($actual); $i++) {

                    if ($i > 0) {
                        $forecast[$i] = $alpha * $actual[$i-1] + (1 - $alpha) * $forecast[$i-1];
                    }

                    // growth
                    if ($prev !== null) {
                        $g = $actual[$i] - $prev;
                        $p = ($prev != 0) ? ($g / $prev) * 100 : 0;
                    } else {
                        $g = 0;
                        $p = 0;
                    }

                    $growth[] = $g;
                    $percent[] = round($p, 1);

                    // MAPE
                    if ($i > 0 && $actual[$i] != 0) {
                        $err = abs(($actual[$i] - $forecast[$i]) / $actual[$i]) * 100;
                        $mapeList[] = $err;
                    }

                    $prev = $actual[$i];

                    $data[] = [
                        'periode' => $labels[$i],
                        'actual' => $actual[$i],
                        'growth' => $growth[$i],
                        'percent' => $percent[$i],
                        'forecast' => round($forecast[$i]),
                        'akurasi' => isset($mapeList[$i-1]) ? round(100 - $mapeList[$i-1], 1) : '-'
                    ];
                }

                // ================= SUMMARY =================
                $summary['total'] = end($actual);
                $summary['avgGrowth'] = count($percent) ? round(array_sum($percent)/count($percent),1) : 0;

                $mape = count($mapeList) ? array_sum($mapeList)/count($mapeList) : 0;
                $summary['mape'] = round($mape,2);

                // kategori
                if ($mape < 10) $summary['kategori'] = 'Sangat Baik';
                elseif ($mape < 20) $summary['kategori'] = 'Baik';
                elseif ($mape < 50) $summary['kategori'] = 'Cukup';
                else $summary['kategori'] = 'Buruk';
            }
        }

        return view('reports.index', compact('olts','data','summary'));
    }

    public function exportPdf(Request $request)
    {
        $olts = Olt::all();
        $data = [];
        $summary = [
            'total'     => 0,
            'avgGrowth' => 0,
            'mape'      => 0,
            'kategori'  => '-'
        ];
        $oltName = '';
        $chartImage = $request->chart_image ?? null;
        $logoBase64 = null;

        $urutanBulan = [
            'januari'   => 1, 'februari'  => 2,
            'febuari'   => 2, 'maret'     => 3,
            'april'     => 4, 'mei'       => 5,
            'juni'      => 6, 'juli'      => 7,
            'agustus'   => 8, 'september' => 9,
            'oktober'   => 10,'november'  => 11,
            'desember'  => 12,
        ];

        if ($request->olt_id) {
            $olt = Olt::find($request->olt_id);
            $oltName = $olt ? $olt->hostname : '';

            $customers = Customer::where('olt_id', $request->olt_id)
                ->orderBy('year', 'asc')
                ->get()
                ->sort(function ($a, $b) use ($urutanBulan) {
                    if ($a->year !== $b->year) {
                        return $a->year <=> $b->year;
                    }
                    $bulanA = $urutanBulan[strtolower($a->month)] ?? 0;
                    $bulanB = $urutanBulan[strtolower($b->month)] ?? 0;
                    return $bulanA <=> $bulanB;
                })->values();

            $actual = [];
            $labels = [];
            $forecast = [];
            $growth = [];
            $percent = [];
            $mapeList = [];

            foreach ($customers as $c) {
                $labels[] = $c->month . ' ' . $c->year;
                $actual[] = $c->total_customers;
            }

            $alpha = 0.9;

            if (count($actual) > 0) {
                $forecast[0] = $actual[0];
                $prev = null;

                for ($i = 0; $i < count($actual); $i++) {
                    if ($i > 0) {
                        $forecast[$i] = $alpha * $actual[$i-1] + (1 - $alpha) * $forecast[$i-1];
                    }

                    if ($prev !== null) {
                        $g = $actual[$i] - $prev;
                        $p = ($prev != 0) ? ($g / $prev) * 100 : 0;
                    } else {
                        $g = 0;
                        $p = 0;
                    }

                    $growth[] = $g;
                    $percent[] = round($p, 1);

                    if ($i > 0 && $actual[$i] != 0) {
                        $err = abs(($actual[$i] - $forecast[$i]) / $actual[$i]) * 100;
                        $mapeList[] = $err;
                    }

                    $prev = $actual[$i];

                    $data[] = [
                        'periode'  => $labels[$i],
                        'actual'   => $actual[$i],
                        'forecast' => round($forecast[$i]),
                        'growth'   => $g,
                        'percent'  => round($p, 1),
                        'akurasi'  => isset($mapeList[$i-1]) ? round(100 - $mapeList[$i-1], 1) : '-'
                    ];
                }

                $summary['total']     = end($actual);
                $summary['avgGrowth'] = count($percent) ? round(array_sum($percent)/count($percent), 1) : 0;
                $mape = count($mapeList) ? array_sum($mapeList)/count($mapeList) : 0;
                $summary['mape']      = round($mape, 2);

                if ($mape < 10) {
                    $summary['kategori'] = 'Sangat Baik';
                } elseif ($mape < 20) {
                    $summary['kategori'] = 'Baik';
                } elseif ($mape < 50) {
                    $summary['kategori'] = 'Cukup';
                } else {
                    $summary['kategori'] = 'Buruk';
                }
            }
        }

        $logoPath = public_path('images/logo.png');
        if (is_file($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(
                file_get_contents($logoPath)
            );
        }

        $pdf = Pdf::loadView(
            'reports.pdf',
            compact('data', 'summary', 'oltName', 'chartImage', 'logoBase64')
        )->setPaper('a4', 'portrait');

        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
        $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
        $pdf->getDomPDF()->set_option('dpi', 150);
        $pdf->getDomPDF()->set_option('defaultFont', 'DejaVu Sans');

        NotificationHelper::send(
            'report_exported',
            'Laporan Diekspor',
            'Laporan pertumbuhan pelanggan OLT ' .
                ($oltName ?: 'Unknown') .
                ' berhasil diekspor ke PDF pada ' .
                now()->format('d M Y, H:i') . ' WIB.',
            'file',
            'success'
        );

        return $pdf->download(
            'laporan-' .
            str_replace(' ', '-', $oltName) .
            '-' . date('Y-m-d') . '.pdf'
        );
    }
}