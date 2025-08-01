<?php

namespace App\Exports;

use App\Models\Kegiatan;
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

    protected $kegiatan;


    public function __construct($kegiatan = null)
    {
        $this->kegiatan = $kegiatan;
    }


    public function view(): View
    {
        // return Peserta::all();
        $data['peserta'] = Peserta::whereNotNull('created_at');

        $kegiatan = Kegiatan::find($this->kegiatan);

        $kegiatan_id = $kegiatan->id;
        $data['peserta'] = $data['peserta']->WhereHas('PertandinganPesertaUtama', function ($q1) use ($kegiatan_id) {
            $q1->WhereHas('Pertandingan', function ($q2) use ($kegiatan_id) {
                $q2->WhereHas('kategori', function ($q3) use ($kegiatan_id) {
                    $q3->where('kegiatan_id', $kegiatan_id);
                });
            });
        });

        $data['peserta'] = $data['peserta']->get();
        $data['kegiatan'] = $kegiatan;
        return view('admin.peserta.excel', $data);
    }

    public function styles(Worksheet $sheet)
    {

        $data['peserta'] = Peserta::whereNotNull('created_at');

        $kegiatan = Kegiatan::find($this->kegiatan);

        $kegiatan_id = $kegiatan->id;
        $data['peserta'] = $data['peserta']->WhereHas('PertandinganPesertaUtama', function ($q1) use ($kegiatan_id) {
            $q1->WhereHas('Pertandingan', function ($q2) use ($kegiatan_id) {
                $q2->WhereHas('kategori', function ($q3) use ($kegiatan_id) {
                    $q3->where('kegiatan_id', $kegiatan_id);
                });
            });
        });

        $lastRow = $data['peserta']->count() + 4;

        return [
            'A3:J' . $lastRow => [
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
