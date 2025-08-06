<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class MasterKabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinsiKode = '64'; // Kaltim misalnya
        $response = Http::get("https://wilayah.id/api/regencies/{$provinsiKode}.json");

        $kabupatens = $response->json();

        foreach ($kabupatens['data'] as $kab) {
            $kabupatenCreate = Kabupaten::create([
                'code' => $kab['code'],
                'name' => $kab['name'],
            ]);

            $response1 = Http::get("https://wilayah.id/api/districts/{$kabupatenCreate->code}.json");
            $kecamatans = $response1->json();

            foreach($kecamatans['data'] as $kec) {
                $kecamatanCreate = Kecamatan::create([
                    'code' => $kec['code'],
                    'name' => $kec['name'],
                    'kabupaten_id' => $kabupatenCreate->id,
                ]);

                $response2 = Http::get("https://wilayah.id/api/villages/{$kecamatanCreate->code}.json");
                $kelurahans = $response2->json();

                foreach($kelurahans['data'] as $kel) {
                    $kelurahanCreate = Kelurahan::create([
                        'code' => $kel['code'],
                        'name' => $kel['name'],
                        'kecamatan_id' => $kecamatanCreate->id,
                    ]);
                }

            }




        }
    }
}
