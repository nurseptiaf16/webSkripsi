<?php

namespace App\Http\Controllers;

use App\Imports\OltImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ImportController extends Controller
{
    public function index(): View
    {
        return view('import.index');
    }

    public function import(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $import = new OltImport();

        try {
            Excel::import($import, $validated['file']);
        } catch (Throwable $exception) {
            return back()->with('error', 'Gagal import: ' . $exception->getMessage());
        }

        return back()->with(
            'success',
            'Import selesai. Berhasil: ' . $import->getImportedCount() . ' baris, dilewati: ' . $import->getSkippedCount() . ' baris.'
        );
    }
}
