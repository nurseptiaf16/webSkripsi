<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Olt;
use App\Helpers\NotificationHelper;

class PredictionController extends Controller
{
    public function index(Request $request)
    {
        $olts = Olt::all();

        $labels = [];
        $actual = [];
        $forecast = [];
        $future = [];
        $futureLabels = [];
        $futureForecasts = [];
        $allLabels = [];
        $accuracy = null;

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

            $data = Customer::where('olt_id', $request->olt_id)
                ->orderBy('year', 'asc')
                ->get()
                ->sort(function($a, $b) use ($urutanBulan) {
                    if ($a->year !== $b->year) {
                        return $a->year <=> $b->year;
                    }
                    $bulanA = $urutanBulan[strtolower($a->month)] ?? 0;
                    $bulanB = $urutanBulan[strtolower($b->month)] ?? 0;
                    return $bulanA <=> $bulanB;
                })->values();

            // ambil data aktual
            foreach ($data as $d) {
                $labels[] = $d->month . ' ' . $d->year;
                $actual[] = $d->total_customers;
            }

            // ================= EXPONENTIAL SMOOTHING =================
            $alpha = 0.9;

            if (count($actual) > 0) {
                $forecast[0] = $actual[0];

                for ($i = 1; $i < count($actual); $i++) {
                    $forecast[$i] = $alpha * $actual[$i-1] + (1 - $alpha) * $forecast[$i-1];
                }

                // ================= PREDIKSI 1 BULAN KE DEPAN =================
                $lastForecast = end($forecast);

                // Ambil bulan dan tahun terakhir dari data
                $lastData  = $data->last();
                $namaBulan = [
                    1 => 'Januari',   2 => 'Februari',
                    3 => 'Maret',     4 => 'April',
                    5 => 'Mei',       6 => 'Juni',
                    7 => 'Juli',      8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober',
                    11 => 'November', 12 => 'Desember',
                ];

                $currentMonth = $urutanBulan[strtolower($lastData->month)] ?? 12;
                $currentYear  = (int) $lastData->year;

                $currentMonth++;
                if ($currentMonth > 12) {
                    $currentMonth = 1;
                    $currentYear++;
                }

                $nextForecast = round(
                    $alpha * end($actual) + (1 - $alpha) * $lastForecast
                );

                $futureLabels[]    = $namaBulan[$currentMonth] . ' ' . $currentYear;
                $futureForecasts[] = $nextForecast;
                $future[]          = $nextForecast;

                // Gabungkan label aktual + label future
                $allLabels = array_merge($labels, $futureLabels);

                // ================= MAPE / AKURASI =================
                $mapeList = [];
                $mape = 0;
                $accuracy = null;

                for ($i = 1; $i < count($actual); $i++) {
                    if ($actual[$i] != 0) {
                        $err = abs(($actual[$i] - $forecast[$i]) / $actual[$i]) * 100;
                    } else {
                        $err = 0;
                    }

                    $mapeList[] = $err;
                }

                if (count($mapeList) > 0) {
                    $mape = array_sum($mapeList) / count($mapeList);
                    $accuracy = round(100 - $mape, 1);
                }

                $oltForNotif = Olt::find($request->olt_id);
                $oltNameNotif = $oltForNotif
                    ? $oltForNotif->hostname
                    : 'Unknown';

                NotificationHelper::send(
                    'prediction_run',
                    'Prediksi Dijalankan',
                    'Prediksi pertumbuhan pelanggan untuk OLT ' .
                        $oltNameNotif . ' berhasil dijalankan. ' .
                        'Hasil prediksi: ' .
                        (end($future) ?: '-') . ' pelanggan.',
                    'chart',
                    'primary'
                );
            }
        }

        return view('prediction.index', compact(
            'olts',
            'labels',
            'actual',
            'forecast',
            'future',
            'futureLabels',
            'futureForecasts',
            'allLabels',
            'accuracy'
        ));
    }
}