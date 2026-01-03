<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function checkUid()
    {
        $data = Mahasiswa::select('uid_card', 'nama_mahasiswa')->get();

        return response()->json([
            'success'   => true,
            'data'      => $data,
        ]);
    }

    public function checkIn(Request $request)
    {
        $uid = $request->uid_card;
        $mahasiswa = Mahasiswa::where('uid_card', $uid)->first();

        if (!$mahasiswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mahasiswa tidak ditemukan'
            ], 404);
        }

        $now = Carbon::now();
        $tanggal = $now->toDateString();
        $waktu = $now->toTimeString();

        // Cek apakah sudah absen hari ini
        $absensiHariIni = Absensi::where('id_mahasiswa', $mahasiswa->id)
            ->where('tanggal', $tanggal)
            ->first();

        if ($absensiHariIni) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mahasiswa sudah melakukan absensi hari ini',
                'data' => [
                    'nama' => $mahasiswa->nama_mahasiswa,
                    'status' => $absensiHariIni->status,
                    'waktu' => $absensiHariIni->waktu,
                ]
            ], 409); // 409 Conflict cocok untuk ini
        }

        $jadwal = Jadwal::first();

        if (!$jadwal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada jadwal hari ini'
            ], 404);
        }

        $status = $now->lte(Carbon::parse($jadwal->jam_mulai)) ? 'hadir' : 'terlambat';

        Absensi::create([
            'uid_card' => $uid,
            'id_mahasiswa' => $mahasiswa->id,
            'tanggal' => $tanggal,
            'waktu' => $waktu,
            'status' => $status,
            'keterangan' => '-'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absensi berhasil dicatat',
            'data' => [
                'nama' => $mahasiswa->nama_mahasiswa,
                'status' => $status,
                'waktu' => $waktu
            ]
        ]);
    }
}
