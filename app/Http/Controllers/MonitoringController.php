<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Olt;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $olts = Olt::all();

        $urutanBulan = [
            'januari'   => 1, 'februari'  => 2,
            'febuari'   => 2, 'maret'     => 3,
            'april'     => 4, 'mei'       => 5,
            'juni'      => 6, 'juli'      => 7,
            'agustus'   => 8, 'september' => 9,
            'oktober'   => 10,'november'  => 11,
            'desember'   => 12,
        ];

        $query = Customer::with('olt');

        // Filter OLT
        if ($request->olt_id) {
            $query->where('olt_id', $request->olt_id);
        }

        // Filter Tahun
        if ($request->year) {
            $query->where('year', $request->year);
        }

        // Sort berdasarkan tahun dulu
        $data = $query->orderBy('year', 'asc')->get()
            ->sort(function($a, $b) use ($urutanBulan) {
                if ($a->year !== $b->year) {
                    return $a->year <=> $b->year;
                }
                $bulanA = $urutanBulan[strtolower($a->month)] ?? 0;
                $bulanB = $urutanBulan[strtolower($b->month)] ?? 0;
                return $bulanA <=> $bulanB;
            })->values();

        // Data per bulan
        $monthly = [];
        foreach ($data as $item) {
            $key = $item->month . ' ' . $item->year;
            if (!isset($monthly[$key])) {
                $monthly[$key] = 0;
            }
            $monthly[$key] += $item->total_customers;
        }

        // Hitung pertumbuhan
        $labels  = [];
        $values  = [];
        $growth  = [];
        $percent = [];
        $prev    = null;

        foreach ($monthly as $month => $total) {
            $labels[] = $month;
            $values[] = $total;

            if ($prev !== null) {
                $g = $total - $prev;
                $p = ($prev != 0) ? ($g / $prev) * 100 : 0;
            } else {
                $g = 0;
                $p = 0;
            }

            $growth[]  = $g;
            $percent[] = round($p, 1);
            $prev      = $total;
        }

        // Summary cards
        $totalGrowth = array_sum($growth);
        $avgGrowth   = count($percent) 
            ? array_sum($percent) / count($percent) : 0;
        $maxGrowth   = count($percent) ? max($percent) : 0;

        // Cari bulan dengan pertumbuhan tertinggi
        $maxGrowthMonth = null;
        if (count($percent) > 0) {
            $maxIndex = array_search(max($percent), $percent);
            $maxGrowthMonth = $labels[$maxIndex] ?? null;
        }

        return view('monitoring.index', compact(
            'labels',
            'values',
            'growth',
            'percent',
            'totalGrowth',
            'avgGrowth',
            'maxGrowth',
            'maxGrowthMonth',
            'olts'
        ));
    }
}