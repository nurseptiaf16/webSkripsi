<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Olt;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $urutanBulan = [
            'januari'   => 1, 'februari'  => 2,
            'febuari'   => 2, 'maret'     => 3,
            'april'     => 4, 'mei'       => 5,
            'juni'      => 6, 'juli'      => 7,
            'agustus'   => 8, 'september' => 9,
            'oktober'   => 10,'november'  => 11,
            'desember'  => 12,
        ];

        $namaBulan = [
            1 => 'Januari',   2 => 'Februari',
            3 => 'Maret',     4 => 'April',
            5 => 'Mei',       6 => 'Juni',
            7 => 'Juli',      8 => 'Agustus',
            9 => 'September', 10 => 'Oktober',
            11 => 'November', 12 => 'Desember',
        ];

        $olts = Olt::all();

        // ============ FILTER ============
        $query = Customer::with('olt');

        if ($request->year) {
            $query->where('year', $request->year);
        }
        if ($request->olt_id) {
            $query->where('olt_id', $request->olt_id);
        }

        $allCustomers = $query->get();

        // ============ STAT CARD 1: TOTAL PELANGGAN ============
        // Total pelanggan bulan terakhir
        $latestData = Customer::orderBy('year', 'desc')
            ->get()
            ->sort(function($a, $b) use ($urutanBulan) {
                if ($a->year !== $b->year) 
                    return $b->year <=> $a->year;
                return ($urutanBulan[strtolower($b->month)] ?? 0) 
                    <=> ($urutanBulan[strtolower($a->month)] ?? 0);
            })->first();

        $latestMonth = $latestData 
            ? ($namaBulan[$urutanBulan[strtolower($latestData->month)] ?? 1] ?? '') 
              . ' ' . $latestData->year 
            : '-';

        $totalPelanggan = Customer::where('year', $latestData->year ?? 0)
            ->where('month', $latestData->month ?? '')
            ->sum('total_customers');

        // ============ STAT CARD 2: JUMLAH OLT AKTIF ============
        $jumlahOlt = Olt::where('status', 'active')->count();

        // ============ STAT CARD 3: PERTUMBUHAN BULAN TERAKHIR ============
        // Ambil 2 bulan terakhir untuk hitung pertumbuhan
        $lastTwoMonths = Customer::orderBy('year', 'desc')
            ->get()
            ->sort(function($a, $b) use ($urutanBulan) {
                if ($a->year !== $b->year) 
                    return $b->year <=> $a->year;
                return ($urutanBulan[strtolower($b->month)] ?? 0) 
                    <=> ($urutanBulan[strtolower($a->month)] ?? 0);
            });

        $bulanList = $lastTwoMonths
            ->map(fn($c) => $c->month . ' ' . $c->year)
            ->unique()->values();

        $pertumbuhanPersen = 0;
        $pertumbuhanBulan  = '-';

        if ($bulanList->count() >= 2) {
            $bulanTerakhir  = $bulanList[0];
            $bulanSebelumnya = $bulanList[1];

            $totalTerakhir  = Customer::whereRaw(
                "CONCAT(month, ' ', year) = ?", [$bulanTerakhir]
            )->sum('total_customers');

            $totalSebelumnya = Customer::whereRaw(
                "CONCAT(month, ' ', year) = ?", [$bulanSebelumnya]
            )->sum('total_customers');

            if ($totalSebelumnya > 0) {
                $pertumbuhanPersen = round(
                    (($totalTerakhir - $totalSebelumnya) 
                    / $totalSebelumnya) * 100, 1
                );
            }
            $pertumbuhanBulan = $bulanTerakhir;
        }

        // ============ STAT CARD 4: PREDIKSI BULAN DEPAN ============
        $alpha       = 0.9;
        $prediksiVal = 0;
        $prediksiLabel = '-';

        $dataUntukPrediksi = Customer::orderBy('year', 'asc')
            ->get()
            ->sort(function($a, $b) use ($urutanBulan) {
                if ($a->year !== $b->year) 
                    return $a->year <=> $b->year;
                return ($urutanBulan[strtolower($a->month)] ?? 0) 
                    <=> ($urutanBulan[strtolower($b->month)] ?? 0);
            })->values();

        $monthlyTotals = [];
        foreach ($dataUntukPrediksi as $d) {
            $key = $d->month . '|' . $d->year;
            $monthlyTotals[$key] = ($monthlyTotals[$key] ?? 0) 
                + $d->total_customers;
        }

        $monthlyValues = array_values($monthlyTotals);
        $monthlyKeys   = array_keys($monthlyTotals);

        if (count($monthlyValues) > 0) {
            $forecast = [$monthlyValues[0]];
            for ($i = 1; $i < count($monthlyValues); $i++) {
                $forecast[$i] = $alpha * $monthlyValues[$i-1] 
                    + (1 - $alpha) * $forecast[$i-1];
            }
            $lastForecast = end($forecast);
            $prediksiVal  = round(
                $alpha * end($monthlyValues) 
                + (1 - $alpha) * $lastForecast
            );

            // Label bulan prediksi
            $lastKey   = end($monthlyKeys);
            [$lastMonth, $lastYear] = explode('|', $lastKey);
            $lastMonthNum = $urutanBulan[strtolower($lastMonth)] ?? 12;
            $nextMonthNum = $lastMonthNum + 1;
            $nextYear     = (int) $lastYear;
            if ($nextMonthNum > 12) { 
                $nextMonthNum = 1; 
                $nextYear++; 
            }
            $prediksiLabel = ($namaBulan[$nextMonthNum] ?? '') 
                . ' ' . $nextYear;
        }

        // ============ GRAFIK: TOTAL PER OLT ============
        $oltSummary = Customer::with('olt')
            ->when($request->year, fn($q) => 
                $q->where('year', $request->year))
            ->when($request->olt_id, fn($q) => 
                $q->where('olt_id', $request->olt_id))
            ->get()
            ->groupBy('olt_id')
            ->map(function($items) {
                return [
                    'hostname' => $items->first()->olt->hostname 
                        ?? 'Unknown',
                    'b2c'      => $items->sum('b2c'),
                    'b2b'      => $items->sum('b2b'),
                    'total'    => $items->sum('total_customers'),
                ];
            })
            ->sortByDesc('total')
            ->values();

        $chartLabels = $oltSummary->pluck('hostname');
        $chartData   = $oltSummary->pluck('total');
        $chartB2C    = $oltSummary->pluck('b2c');
        $chartB2B    = $oltSummary->pluck('b2b');

        // ============ TAHUN UNTUK FILTER ============
        $years = Customer::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('dashboard', compact(
            'olts',
            'years',
            'totalPelanggan',
            'latestMonth',
            'jumlahOlt',
            'pertumbuhanPersen',
            'pertumbuhanBulan',
            'prediksiVal',
            'prediksiLabel',
            'oltSummary',
            'chartLabels',
            'chartData',
            'chartB2C',
            'chartB2B'
        ));
    }
}