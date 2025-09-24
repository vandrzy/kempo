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



    public function __construct($kegiatan = null, $kategori = null, $pertandingan = null)
    {
        $this->kegiatan = $kegiatan;
        $this->kategori = $kategori;
        $this->pertandingan = $pertandingan;

    }


    public function view(): View
    {
        // return Peserta::all();
        $data['peserta'] = Peserta::whereNotNull('created_at');

        $kegiatan = Kegiatan::find($this->kegiatan);

        $kegiatan_id = $this->kegiatan;
        $kategori_id = $this->kategori;
        $pertandingan_id = $this->pertandingan;

        if($kategori_id != "") {
            $data['kategori_id'] = Kategori::find($kategori_id);
        }

        if($pertandingan_id != "") {
            $data['pertandingan_id'] = Pertandingan::find($pertandingan_id);
        }

        // $kegiatan_id = $kegiatan->id;
        $data['peserta'] = $data['peserta']->whereHas('PertandinganPesertaUtama', function ($q1) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
            $q1->WhereHas('list_kategori', function ($q2) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
                $q2 = $q2->where('kegiatan_id', $kegiatan_id);

                if(isset($kategori_id)  && $kategori_id != "") {
                    $q2 = $q2->where('kategori_id', $kategori_id);
                }

                if(isset($pertandingan_id)  && $pertandingan_id != "") {
                    $q2 = $q2->where('pertandingan_id', $pertandingan_id);
                }
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

        $kegiatan_id = $this->kegiatan;
        $kategori_id = $this->kategori;
        $pertandingan_id = $this->pertandingan;

        // $kegiatan_id = $kegiatan->id;
        $data['peserta'] = $data['peserta']->whereHas('PertandinganPesertaUtama', function ($q1) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
            $q1->WhereHas('list_kategori', function ($q2) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
                $q2 = $q2->where('kegiatan_id', $kegiatan_id);

                if(isset($kategori_id)  && $kategori_id != "") {
                    $q2 = $q2->where('kategori_id', $kategori_id);
                }

                if(isset($pertandingan_id)  && $pertandingan_id != "") {
                    $q2 = $q2->where('pertandingan_id', $pertandingan_id);
                }
            });
        });

        $lastRow = $data['peserta']->count() + 6;

        return [
            'A5:J' . $lastRow => [
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
