<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Olt;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $olts = Olt::all();

        $labels = [];
        $actual = [];
        $forecast = [];
        $mapeList = [];
        $mape = 0;

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

            foreach ($data as $d) {
                $labels[] = $d->month . ' ' . $d->year;
                $actual[] = $d->total_customers;
            }

            // ================= PREDIKSI =================
            $alpha = 0.9;

            if (count($actual) > 0) {
                $forecast[0] = $actual[0];

                for ($i = 1; $i < count($actual); $i++) {
                    $forecast[$i] = $alpha * $actual[$i-1] + (1 - $alpha) * $forecast[$i-1];
                }

                // ================= MAPE =================
                for ($i = 1; $i < count($actual); $i++) {

                    if ($actual[$i] != 0) {
                        $error = abs(($actual[$i] - $forecast[$i]) / $actual[$i]) * 100;
                    } else {
                        $error = 0;
                    }

                    $mapeList[] = round($error, 2);
                }

                if (count($mapeList) > 0) {
                    $mape = round(array_sum($mapeList) / count($mapeList), 2);
                }
            }
        }

        return view('evaluation.index', compact(
            'olts',
            'labels',
            'actual',
            'forecast',
            'mapeList',
            'mape'
        ));
    }
}