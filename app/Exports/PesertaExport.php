<?php

namespace App\Exports;

use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Models\Pertandingan;
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
    protected $kategori;
    protected $pertandingan;
    protected $nama;

    public function __construct($kegiatan = null, $kategori = null, $pertandingan = null, $nama = null)
    {
        $this->kegiatan = $kegiatan;
        $this->kategori = $kategori;
        $this->pertandingan = $pertandingan;
        $this->nama = $nama;
    }

    public function view(): View
    {
        $data['peserta'] = Peserta::where('status', 'P')->whereNotNull('created_at');

        if (!empty($this->nama)) {
            $data['peserta'] = $data['peserta']->where('nama', 'like', '%' . $this->nama . '%');
        }

        $kegiatan = $this->kegiatan ? Kegiatan::find($this->kegiatan) : Kegiatan::orderBy('created_at', 'DESC')->first();
        $kegiatan_id = $kegiatan ? $kegiatan->id : null;
        $kategori_id = $this->kategori;
        $pertandingan_id = $this->pertandingan;

        if (!empty($kategori_id)) {
            $data['kategori_id'] = Kategori::find($kategori_id);
        }

        if (!empty($pertandingan_id)) {
            $data['pertandingan_id'] = Pertandingan::find($pertandingan_id);
        }

        if ($kegiatan_id) {
            $data['peserta'] = $data['peserta']->whereHas('PertandinganPesertaUtama', function ($q1) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
                $q1->whereHas('list_kategori', function ($q2) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
                    $q2->where('kegiatan_id', $kegiatan_id);

                    if (!empty($kategori_id)) {
                        $q2->where('kategori_id', $kategori_id);
                    }

                    if (!empty($pertandingan_id)) {
                        $q2->where('pertandingan_id', $pertandingan_id);
                    }
                });
            });
        }

        $data['peserta'] = $data['peserta']->orderBy('nama', 'ASC')->get();
        $data['kegiatan'] = $kegiatan;
        return view('admin.peserta.excel', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        return [
            'A4:J' . $lastRow => [
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
            'B' => 28,  // Nama
            'C' => 22,  // NIK
            'D' => 14,  // Usia
            'E' => 6,   // L
            'F' => 6,   // P
            'G' => 6,   // K
            'H' => 6,   // D
            'I' => 6,   // P
            'J' => 6,   // O
        ];
    }


}
