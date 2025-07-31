<?php

namespace App\Exports;

use App\Models\Peserta;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PesertaExport implements FromView, WithStyles, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function view(): View
    {
        // return Peserta::all();
        $data['peserta'] = Peserta::whereNotNull('created_at');

        $data['peserta'] = $data['peserta']->get();
        return view('admin.peserta.excel', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = Peserta::count() + 3; // +1 untuk header

        return [
            'A2:J' . $lastRow => [
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
            'A' => 5,   // No
            'B' => 20,  // Nama
            'C' => 20,  // NIK
            'D' => 10,  // Telepon
            'E' => 12,  // Alamat
        ];
    }


}
