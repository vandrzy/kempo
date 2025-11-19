<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Kegiatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KontigenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $kegiatan_id = $_GET['kegiatan_id'] ?? 0;
        $data['kabupatens'] = Kabupaten::with(['peserta' => function($q) use ($kegiatan_id) {
            $q->whereHas('PertandinganPeserta', function($qq) {
                $qq->select('peserta_id', DB::raw('MIN(id) as id'))
                ->groupBy('peserta_id')
                ->whereHas('list_kategori');
            })
            ->whereHas('PertandinganPesertaUtama', function($qq1) use ($kegiatan_id) {
                $qq1->whereHas('list_kategori', function($qq2) use ($kegiatan_id) {
                    $qq2->where('kegiatan_id', $kegiatan_id);
                });
            });
        }, 'official' => function($s) use ($kegiatan_id) {
            $s->where('kegiatan_id',$kegiatan_id);
        }])->get();
        $data['kegiatan'] = Kegiatan::orderBy('created_at', 'DESC')->get();

        return view('admin.kontigen.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kegiatan_id = $_GET['kegiatan_id'] ?? 0;
        $data['kegiatan'] = Kegiatan::find($kegiatan_id);
        $data['kabupaten'] = Kabupaten::find($id);

        $status = $_GET['status'] ?? 'peserta';
        if($status == 'peserta') {
            $data['peserta'] = $data['kabupaten']->peserta()
                ->whereHas('PertandinganPeserta', function($q) {
                    $q->whereHas('list_kategori');
                })->whereHas('PertandinganPesertaUtama', function($qq1) use ($kegiatan_id) {
                    $qq1->whereHas('list_kategori', function($qq2) use ($kegiatan_id) {
                        $qq2->where('kegiatan_id', $kegiatan_id);
                    });
                })
                ->paginate(10);
        }

        if($status == 'official') {
            $data['peserta'] = $data['kabupaten']->official()->where('kegiatan_id',$kegiatan_id)->paginate(10);
        }
        return view('admin.kontigen.peserta', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $data['kabupaten'] = Kabupaten::find($id);

        $kegiatan_id = $_GET['kegiatan_id'] ?? 0;
        $data['kegiatan'] = Kegiatan::find($kegiatan_id);
        if($_GET['print'] == 'peserta') {
            $data['peserta'] = $data['kabupaten']->peserta()
                ->whereHas('PertandinganPeserta', function($q) {
                    $q->whereHas('list_kategori');
                })->whereHas('PertandinganPesertaUtama', function($qq1) use ($kegiatan_id) {
                    $qq1->whereHas('list_kategori', function($qq2) use ($kegiatan_id) {
                        $qq2->where('kegiatan_id', $kegiatan_id);
                    });
                })
                ->get();
        }

        if($_GET['print'] == 'official') {
            $data['peserta'] = $data['kabupaten']->official()->where('kegiatan_id',$kegiatan_id)->get();
        }
        $pdf = Pdf::loadView('admin.kontigen.print', $data);

        // 🟢 Tampilkan langsung di browser
        return $pdf->download('Data_Peserta_Kontigen_'.$data['kabupaten']->name.'.pdf');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
