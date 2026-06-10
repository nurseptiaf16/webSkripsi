<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Olt;
use App\Helpers\NotificationHelper;

class CustomerController extends Controller
{
    // ================= READ =================
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
            'desember'  => 12,
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

        // Ambil semua data
        $customers = $query->get();

        // Filter Search
        if ($request->search) {
            $search = strtolower($request->search);
            $customers = $customers->filter(function($c) use ($search) {
                return str_contains(strtolower($c->olt->hostname ?? ''), $search)
                    || str_contains(strtolower($c->month ?? ''), $search)
                    || str_contains(strtolower($c->year ?? ''), $search);
            })->values();
        }

        // Sort berdasarkan pilihan urutan
        $urutan = $request->urutan;

        $customers = $customers->sort(function($a, $b) use ($urutanBulan, $urutan) {
            if ($urutan === 'tahun_terbaru') {
                if ($a->year !== $b->year) {
                    return $b->year <=> $a->year;
                }
                $bulanA = $urutanBulan[strtolower($a->month)] ?? 0;
                $bulanB = $urutanBulan[strtolower($b->month)] ?? 0;
                return $bulanB <=> $bulanA;
            }

            if ($urutan === 'tahun_terlama') {
                if ($a->year !== $b->year) {
                    return $a->year <=> $b->year;
                }
                $bulanA = $urutanBulan[strtolower($a->month)] ?? 0;
                $bulanB = $urutanBulan[strtolower($b->month)] ?? 0;
                return $bulanA <=> $bulanB;
            }

            if ($urutan === 'total_terbanyak') {
                return $b->total_customers <=> $a->total_customers;
            }

            if ($urutan === 'total_tersedikit') {
                return $a->total_customers <=> $b->total_customers;
            }

            // Default: tahun terlama
            if ($a->year !== $b->year) {
                return $a->year <=> $b->year;
            }
            $bulanA = $urutanBulan[strtolower($a->month)] ?? 0;
            $bulanB = $urutanBulan[strtolower($b->month)] ?? 0;
            return $bulanA <=> $bulanB;
        })->values();

        // Ambil tahun unik untuk filter dropdown
        $years = Customer::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('customers.index', compact('customers', 'olts', 'years'));
    }

    // ================= CREATE FORM =================
    public function create()
    {
        $olts = \App\Models\Olt::orderBy('hostname')->get();
        return view('customers.create', compact('olts'));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        // Cek duplikat
        $duplikat = \App\Models\Customer::
            where('olt_id', $request->olt_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();

        if ($duplikat) {
            return back()
                ->withInput()
                ->withErrors([
                    'duplikat' => 'Data dengan OLT ' . 
                        $request->olt_id . 
                        ', bulan dan tahun yang sama 
                        sudah ada. Silakan gunakan 
                        data yang berbeda.'
                ]);
        }

        $request->validate([
            'olt_id' => 'required|exists:olts,id',
            'month' => 'required',
            'year' => 'required|integer|min:2000|max:2099',
            'b2c' => 'required|integer|min:0',
            'b2b' => 'required|integer|min:0',
        ]);

        Customer::create([
            'olt_id' => $request->olt_id,
            'month' => $request->month,
            'year' => $request->year,
            'b2c' => $request->b2c,
            'b2b' => $request->b2b,
            'total_customers' => $request->b2c + $request->b2b,
        ]);

        $olt = Olt::find($request->olt_id);
        $totalCustomers = $request->b2c + $request->b2b;

        NotificationHelper::send(
            'customer_created',
            'Data Customer Ditambahkan',
            'Data customer baru untuk OLT ' .
                ($olt->hostname ?? 'Unknown') .
                ' bulan ' . $request->month .
                ' ' . $request->year .
                ' berhasil ditambahkan. ' .
                'Total: ' . $totalCustomers .
                ' pelanggan.',
            'user',
            'success'
        );

        return redirect()->route('customers.index')
            ->with('success', 'Data customer berhasil ditambahkan');
    }

    // ================= EDIT FORM =================
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $olts = \App\Models\Olt::orderBy('hostname')->get();

        return view('customers.edit', compact('customer', 'olts'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        // Cek duplikat
        $duplikat = \App\Models\Customer::
            where('olt_id', $request->olt_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->where('id', '!=', $id)
            ->first();

        if ($duplikat) {
            return back()
                ->withInput()
                ->withErrors([
                    'duplikat' => 'Data dengan OLT ' . 
                        $request->olt_id . 
                        ', bulan dan tahun yang sama 
                        sudah ada. Silakan gunakan 
                        data yang berbeda.'
                ]);
        }

        $request->validate([
            'olt_id' => 'required|exists:olts,id',
            'month' => 'required',
            'year' => 'required|integer|min:2000|max:2099',
            'b2c' => 'required|integer|min:0',
            'b2b' => 'required|integer|min:0',
        ]);

        $customer = Customer::findOrFail($id);

        $customer->update([
            'olt_id' => $request->olt_id,
            'month' => $request->month,
            'year' => $request->year,
            'b2c' => $request->b2c,
            'b2b' => $request->b2b,
            'total_customers' => $request->b2c + $request->b2b,
        ]);

        $olt = Olt::find($customer->olt_id);

        NotificationHelper::send(
            'customer_updated',
            'Data Customer Diperbarui',
            'Data customer OLT ' .
                ($olt->hostname ?? 'Unknown') .
                ' bulan ' . $customer->month .
                ' ' . $customer->year .
                ' telah diperbarui.',
            'edit',
            'primary'
        );

        return redirect()->route('customers.index')
            ->with('success', 'Data customer berhasil diupdate');
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $olt = Olt::find($customer->olt_id);

        $customer->delete();

        NotificationHelper::send(
            'customer_deleted',
            'Data Customer Dihapus',
            'Data customer OLT ' .
                ($olt->hostname ?? 'Unknown') .
                ' bulan ' . $customer->month .
                ' ' . $customer->year .
                ' telah dihapus.',
            'trash',
            'danger'
        );

        return redirect()->route('customers.index')
            ->with('success', 'Data customer berhasil dihapus');
    }
}