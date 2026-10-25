<?php

namespace App\Exports;

use App\Models\Kegiatan;
use App\Models\Official;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OfficialExport implements FromView, WithStyles, WithColumnWidths
{
    protected $kegiatan;
    protected $nama;

    public function __construct($kegiatan = null, $nama = null)
    {
        $this->kegiatan = $kegiatan;
        $this->nama = $nama;
    }

    public function view(): View
    {
        $kegiatan = $this->kegiatan ? Kegiatan::find($this->kegiatan) : Kegiatan::orderBy('created_at', 'DESC')->first();
        $kegiatan_id = $kegiatan ? $kegiatan->id : null;

        $data['peserta'] = Official::with('kabupaten')->whereNotNull('created_at');

        if ($kegiatan_id) {
            $data['peserta'] = $data['peserta']->where('kegiatan_id', $kegiatan_id);
        }

        if (!empty($this->nama)) {
            $data['peserta'] = $data['peserta']->where('nama', 'like', '%' . $this->nama . '%');
        }

        $data['peserta'] = $data['peserta']->orderBy('nama', 'ASC')->get();
        $data['kegiatan'] = $kegiatan;

        return view('admin.official.excel', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        return [
            'A4:K' . $lastRow => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,   // No
            'B' => 20,  // Kontingen
            'C' => 28,  // Nama
            'D' => 22,  // NIK
            'E' => 14,  // Usia
            'F' => 6,   // L
            'G' => 6,   // P
            'H' => 6,   // K
            'I' => 6,   // D
            'J' => 6,   // P
            'K' => 6,   // O
        ];
    }
}
