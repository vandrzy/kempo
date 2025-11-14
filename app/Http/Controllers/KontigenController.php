<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KontigenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['kabupatens'] = Kabupaten::get();
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
        $data['kabupaten'] = Kabupaten::find($id);
        $data['peserta'] = $data['kabupaten']->peserta()
            ->whereHas('PertandinganPeserta', function($q) {
                $q->whereHas('list_kategori');
            })
            ->paginate(10);
        return view('admin.kontigen.peserta', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $data['kabupaten'] = Kabupaten::find($id);
        $data['peserta'] = $data['kabupaten']->peserta()
            ->whereHas('PertandinganPeserta', function($q) {
                $q->whereHas('list_kategori');
            })
            ->get();
        $pdf = Pdf::loadView('admin.kontigen.print', $data);

        // 🟢 Tampilkan langsung di browser
        return $pdf->stream('Data_Peserta_Kontigen_'.$data['kabupaten']->name.'.pdf');
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
