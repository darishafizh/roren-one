<?php

namespace App\Http\Controllers\Knmp;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;
use App\Models\Knmp;
use App\Models\KonstruksiKnmp;

class OperasionalController extends ProgramBaseController
{
    public function index(Request $request, $program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        
        if ($menu === 'kendala') {
            return view('programs.knmp.operasional.kendala', ['activeModule' => 'Operasional', 'activeProgram' => $activeProgram]);
        }
        if ($menu === 'pencairan') {
            return view('programs.knmp.operasional.pencairan', ['activeModule' => 'Operasional', 'activeProgram' => $activeProgram]);
        }
        
        // Query data from database grouped by tahap_saat_ini
        $usulanData = Knmp::where('tahap_saat_ini', 'usulan')
            ->select('id', 'nama', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'status', 'latitude', 'longitude')
            ->get()->map(fn($k) => [
                'id' => $k->id,
                'lokasi' => $k->nama,
                'daerah' => $k->kabupaten . ', ' . $k->provinsi,
                'statusHub' => $k->status ?: 'Penyangga',
            ]);

        $surveiData = Knmp::where('tahap_saat_ini', 'survey')
            ->select('id', 'nama', 'provinsi', 'kabupaten', 'status', 'latitude', 'longitude')
            ->get()->map(fn($k) => [
                'id' => $k->id,
                'lokasi' => $k->nama,
                'statusHub' => $k->status ?: 'Penyangga',
                'koordinat' => $k->latitude && $k->longitude ? $k->latitude . ', ' . $k->longitude : '-',
            ]);

        $dedData = Knmp::where('tahap_saat_ini', 'ded')
            ->select('id', 'nama', 'status')
            ->get()->map(fn($k) => [
                'id' => $k->id,
                'lokasi' => $k->nama,
                'statusHub' => $k->status ?: 'Penyangga',
                'nilaiSkala' => '-',
            ]);

        $lelangData = Knmp::where('tahap_saat_ini', 'lelang')
            ->select('id', 'nama', 'status')
            ->get()->map(fn($k) => [
                'id' => $k->id,
                'lokasi' => $k->nama,
                'statusHub' => $k->status ?: 'Penyangga',
                'namaKonstruksi' => '-',
            ]);

        $konstruksiData = Knmp::where('tahap_saat_ini', 'konstruksi')
            ->select('id', 'nama', 'status')
            ->get()->map(function($k) {
                $kons = KonstruksiKnmp::where('knmp_id', $k->id)->first();
                $progres = 0;
                $konstruktor = '-';
                if ($kons) {
                    $konstruktor = $kons->penyediaJasa ? $kons->penyediaJasa->nama : '-';
                    $lastProgres = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
                        ->table('progres_harian')
                        ->where('knmp_konstruksi_id', $kons->id)
                        ->orderBy('tanggal', 'desc')
                        ->first();
                    $progres = $lastProgres ? round($lastProgres->progres, 1) : 0;
                }
                return [
                    'id' => $k->id,
                    'lokasi' => $k->nama,
                    'statusHub' => $k->status ?: 'Penyangga',
                    'konstruktor' => $konstruktor,
                    'progres' => $progres,
                ];
            });

        $serahTerimaData = Knmp::where('tahap_saat_ini', 'serah_terima')
            ->select('id', 'nama', 'status')
            ->get()->map(fn($k) => [
                'id' => $k->id,
                'lokasi' => $k->nama,
                'statusHub' => $k->status ?: 'Penyangga',
            ]);

        return view('programs.knmp.operasional.index', [
            'activeModule' => 'Operasional',
            'activeProgram' => $activeProgram,
            'stage' => $request->query('stage', 'usulan'),
            'usulanData' => $usulanData->values(),
            'surveiData' => $surveiData->values(),
            'dedData' => $dedData->values(),
            'lelangData' => $lelangData->values(),
            'konstruksiData' => $konstruksiData->values(),
            'serahTerimaData' => $serahTerimaData->values(),
        ]);
    }

    public function uploadFoto(Request $request, $program)
    {
        $this->checkAuth();

        $request->validate([
            'knmp_id' => 'required|exists:mysql_knmp.knmp,id',
            'foto_before' => 'nullable|array|max:2',
            'foto_before.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'foto_after' => 'nullable|array|max:2',
            'foto_after.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (!$request->hasFile('foto_before') && !$request->hasFile('foto_after')) {
            return back()->with('error', 'Silakan pilih setidaknya satu foto untuk diunggah.');
        }

        $now = now();
        $inserts = [];

        if ($request->hasFile('foto_before')) {
            foreach ($request->file('foto_before') as $file) {
                $path = $file->store('bukti_uploads', 'public');
                $inserts[] = [
                    'knmp_id' => $request->knmp_id,
                    'kondisi' => 'before',
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'tipe_file' => $file->getMimeType(),
                    'ukuran_file' => $file->getSize(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if ($request->hasFile('foto_after')) {
            foreach ($request->file('foto_after') as $file) {
                $path = $file->store('bukti_uploads', 'public');
                $inserts[] = [
                    'knmp_id' => $request->knmp_id,
                    'kondisi' => 'after',
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'tipe_file' => $file->getMimeType(),
                    'ukuran_file' => $file->getSize(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (count($inserts) > 0) {
            \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('bukti_uploads')->insert($inserts);
        }

        return back()->with('success', 'Foto berhasil diunggah dan disimpan.');
    }
}
