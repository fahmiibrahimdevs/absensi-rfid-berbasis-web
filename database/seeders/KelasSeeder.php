<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_kelas'          => 'EC A',
            ],

            [
                'nama_kelas'          => 'EC B',
            ],

            [
                'nama_kelas'          => 'EC C',
            ],

            [
                'nama_kelas'          => 'EC D',
            ],
        ];

        Kelas::insert($data);
    }
}
