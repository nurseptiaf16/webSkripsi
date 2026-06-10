<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Olt;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Throwable;

class OltImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsOnError, WithMultipleSheets
{
    use SkipsErrors;

    private int $importedCount = 0;
    private int $skippedCount = 0;
    private string $lastHostname = '';
    private string $lastBulan = '';
    private int $lastTahun = 0;

    public function sheets(): array
    {
        return [
            'Data OLT 2021-2025' => $this,
        ];
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $hostname = trim((string) ($row['hostname'] ?? ''));
            $bulanRaw = trim((string) ($row['bulan'] ?? ''));
            $bulan = $bulanRaw !== ''
                ? ucfirst(strtolower($bulanRaw))
                : '';
            $tahun = (int) ($row['tahun'] ?? 0);

            if ($hostname === '' && $this->lastHostname !== '') {
                $hostname = $this->lastHostname;
            }

            if ($bulan === '' && $this->lastBulan !== '') {
                $bulan = $this->lastBulan;
            }

            if ($tahun === 0 && $this->lastTahun !== 0) {
                $tahun = $this->lastTahun;
            }

            if ($hostname === '' || $bulan === '' || $tahun === 0) {
                $this->skippedCount++;
                continue;
            }

            $b2c = (int) ($row['pelanggan_b2c'] ?? 0);
            $b2b = (int) ($row['pelanggan_b2b'] ?? 0);
            $total = $b2c + $b2b;
            $olt = Olt::where('hostname', $hostname)->first();

            if (!$olt) {
                $this->skippedCount++;
                continue;
            }

            Customer::firstOrCreate(
                [
                    'olt_id' => $olt->id,
                    'year' => $tahun,
                    'month' => $bulan,
                ],
                [
                    'b2c' => $b2c,
                    'b2b' => $b2b,
                    'total_customers' => $total,
                ]
            );

            $this->importedCount++;
            $this->lastHostname = $hostname;
            $this->lastBulan = $bulan;
            $this->lastTahun = $tahun;
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }
}
