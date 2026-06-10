<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Olt;
use App\Helpers\NotificationHelper;

class OltController extends Controller
{
    public function index(Request $request)
    {
        $query = Olt::query();

        // search (FIXED)
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('hostname', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        // filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // filter lokasi
        if ($request->location) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        $olts = $query->get();

        return view('olts.index', compact('olts'));
    }

    public function create()
    {
        return view('olts.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'hostname' => 'required|unique:olts,hostname',
                'location' => 'required',
                'status' => 'required|in:active,maintenance,non-active',
            ],
            [
                'hostname.required' => 'Nama OLT wajib diisi.',
                'hostname.unique' => 'OLT dengan nama ini sudah ada, silakan gunakan nama lain.',
                'location.required' => 'Lokasi wajib diisi.',
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status tidak valid.',
            ]
        );

        Olt::create([
            'hostname' => $request->hostname,
            'location' => $request->location,
            'status' => $request->status,
        ]);

        NotificationHelper::send(
            'olt_created',
            'Perangkat OLT Ditambahkan',
            'Perangkat OLT baru telah ditambahkan.',
            'server',
            'success'
        );

        return redirect()->route('olts.index')->with('success', 'OLT berhasil ditambahkan');
    }

    public function edit($id)
    {
        $olt = Olt::findOrFail($id);
        return view('olts.edit', compact('olt'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'hostname' => 'required|unique:olts,hostname,' . $id,
                'location' => 'required',
                'status' => 'required|in:active,maintenance,non-active',
            ],
            [
                'hostname.required' => 'Nama OLT wajib diisi.',
                'hostname.unique' => 'OLT dengan nama ini sudah ada, silakan gunakan nama lain.',
                'location.required' => 'Lokasi wajib diisi.',
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status tidak valid.',
            ]
        );

        $olt = Olt::findOrFail($id);

        $statusLama = $olt->status;
        $statusBaru = $request->status;

        $olt->update([
            'hostname' => $request->hostname,
            'location' => $request->location,
            'status' => $request->status,
        ]);

        if ($statusLama !== $statusBaru) {
            $statusLabel = [
                'active' => 'Active',
                'maintenance' => 'Maintenance',
                'non-active' => 'Non-Active',
            ];

            $colorMap = [
                'active' => 'success',
                'maintenance' => 'warning',
                'non-active' => 'danger',
            ];

            NotificationHelper::send(
                'olt_status_changed',
                'Status OLT Berubah',
                'Status perangkat ' . $olt->hostname .
                    ' berubah dari ' .
                    ($statusLabel[$statusLama] ?? $statusLama) .
                    ' menjadi ' .
                    ($statusLabel[$statusBaru] ?? $statusBaru) . '.',
                'server',
                $colorMap[$statusBaru] ?? 'primary'
            );
        } else {
            NotificationHelper::send(
                'olt_updated',
                'Perangkat OLT Diperbarui',
                'Data perangkat ' . $olt->hostname .
                    ' telah diperbarui.',
                'edit',
                'primary'
            );
        }

        return redirect()->route('olts.index')->with('success', 'OLT berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Olt::findOrFail($id)->delete();

        NotificationHelper::send(
            'olt_deleted',
            'Perangkat OLT Dihapus',
            'Perangkat OLT telah dihapus dari sistem.',
            'trash',
            'danger'
        );

        return redirect()->route('olts.index')->with('success', 'OLT berhasil dihapus');
    }
}