<?php

namespace App\Http\Controllers\Knmp;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;
use App\Models\Knmp;

class DashboardController extends ProgramBaseController
{
    public function index($program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        
        $totalLokasi = \App\Models\Knmp::count();
        $totalSelesai = \App\Models\Knmp::where('tahap_saat_ini', 'serah_terima')->count();
        $dalamPembangunan = \App\Models\Knmp::where('tahap_saat_ini', 'konstruksi')->count();
        
        $pipeline = [
            'usulan' => \App\Models\Knmp::where('tahap_saat_ini', 'usulan')->count(),
            'survei' => \App\Models\Knmp::where('tahap_saat_ini', 'survey')->count(),
            'ded' => \App\Models\Knmp::where('tahap_saat_ini', 'ded')->count(),
            'lelang' => \App\Models\Knmp::where('tahap_saat_ini', 'lelang')->count(),
            'konstruksi' => $dalamPembangunan,
            'serah_terima' => $totalSelesai,
        ];
        
        $avgProgres = 0;
        $konstruksiDetails = [];
        $kesehatan = ['sesuai' => 0, 'ringan' => 0, 'kritis' => 0];

        if ($dalamPembangunan > 0) {
            $konstruksis = \App\Models\Knmp::where('tahap_saat_ini', 'konstruksi')
                ->with('konstruksiKnmp.penyediaJasa', 'konstruksiKnmp.tahapKonstruksi')
                ->get();
            
            $totalProgres = 0;
            $countWithProgres = 0;

            foreach($konstruksis as $k) {
                $kons = $k->konstruksiKnmp;
                if (!$kons) continue;

                // Latest Progres
                $latestProgres = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
                    ->table('progres_harian')
                    ->where('knmp_konstruksi_id', $kons->id)
                    ->orderBy('tanggal', 'desc')
                    ->first();
                $progres = $latestProgres ? (float)$latestProgres->progres : 0;
                
                // Latest Rencana
                $rencana = 0;
                if ($kons->tanggal_mulai) {
                    $tanggalMulai = \Carbon\Carbon::parse($kons->tanggal_mulai);
                    $daysDiff = $tanggalMulai->diffInDays(now(), false);
                    $currentWeek = $daysDiff < 0 ? 1 : floor($daysDiff / 7) + 1;
                    
                    $tahapKonstruksi = \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('tahap_konstruksi')
                        ->where('knmp_konstruksi_id', $kons->id)
                        ->where('periode_mingguan', '<=', $currentWeek)
                        ->orderBy('periode_mingguan', 'desc')
                        ->first();
                        
                    if ($tahapKonstruksi) {
                        $val = (float)$tahapKonstruksi->bobot_rencana_kumulatif;
                        // Normalisasi: jika nilai > 100 (contoh: 94625), artinya formatnya dikali 1000
                        if ($val > 100) {
                            $val = $val / 1000;
                        }
                        // Format to max 2 decimal places
                        $rencana = round($val, 2);
                    }
                }
                
                $deviasi = round($progres - $rencana, 2);

                // Hitung Kesehatan
                if ($deviasi >= 0) $kesehatan['sesuai']++;
                elseif ($deviasi >= -5) $kesehatan['ringan']++;
                else $kesehatan['kritis']++;

                if ($latestProgres) {
                    $totalProgres += $progres;
                    $countWithProgres++;
                }

                $konstruksiDetails[] = [
                    'lokasi' => $k->nama,
                    'daerah' => $k->status ?: 'Penyangga',
                    'konstruktor' => $kons->penyediaJasa ? $kons->penyediaJasa->nama : '-',
                    'progres' => round($progres, 1),
                    'rencana' => round($rencana, 1),
                    'deviasi' => round($deviasi, 1),
                ];
            }

            if ($countWithProgres > 0) {
                $avgProgres = round($totalProgres / $countWithProgres, 1);
            }
        }

        // Sort for Top 10 and Bottom 10
        $sortedByProgres = collect($konstruksiDetails)->sortByDesc('progres')->values();
        $top10 = $sortedByProgres->take(10);
        $bottom10 = collect($konstruksiDetails)->sortBy('progres')->values()->take(10);

        // Map Locations and Regions
        $mapLocations = \App\Models\Knmp::select('nama', 'latitude', 'longitude', 'status')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
            
        $regionBarat = 0;
        $regionTengah = 0;
        $regionTimur = 0;
        
        foreach ($mapLocations as $loc) {
            if ($loc->longitude < 116) {
                $regionBarat++;
            } elseif ($loc->longitude >= 116 && $loc->longitude < 124) {
                $regionTengah++;
            } else {
                $regionTimur++;
            }
        }

        // Dynamic Narrative
        $narasi = "Sejauh ini, progres program Kampung Nelayan Merah Putih (KNMP) mencatatkan perkembangan yang terukur. Dari total <span class='text-teal-light dark:text-teal-400 font-bold'>{$totalLokasi} lokasi</span> yang terdaftar, terdapat <span class='text-warning dark:text-amber-500 font-bold'>{$dalamPembangunan} lokasi</span> yang saat ini sedang dalam tahap konstruksi aktif dengan rata-rata progres fisik mencapai <span class='font-bold'>{$avgProgres}%</span>. Selain itu, <span class='text-success font-bold'>{$totalSelesai} lokasi</span> telah berhasil diselesaikan dan diserahterimakan. Sebaran pembangunan mencakup {$regionBarat} lokasi di Wilayah Barat, {$regionTengah} di Tengah, dan {$regionTimur} di Timur Indonesia, menunjukkan komitmen pemerataan infrastruktur pesisir.";

        return view("programs.knmp.dashboard.index", [
            'activeModule' => 'Dashboard',
            'activeProgram' => $activeProgram,
            'stats' => [
                'total_lokasi' => $totalLokasi,
                'rata_progres' => $avgProgres,
                'total_selesai' => $totalSelesai,
                'dalam_pembangunan' => $dalamPembangunan,
                'pipeline' => $pipeline,
                'kesehatan' => $kesehatan,
                'top10' => $top10,
                'bottom10' => $bottom10,
                'map_locations' => $mapLocations,
                'all_konstruksi' => $sortedByProgres->values()->all(),
                'regions' => [
                    'barat' => $regionBarat,
                    'tengah' => $regionTengah,
                    'timur' => $regionTimur,
                ],
                'narasi' => $narasi
            ]
        ]);
    }
}
