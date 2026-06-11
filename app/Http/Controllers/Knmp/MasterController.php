<?php

namespace App\Http\Controllers\Knmp;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;

class MasterController extends ProgramBaseController
{
    public function index(Request $request, $program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        
        if ($menu === 'komponen') {
            return view('programs.knmp.master.komponen', ['activeModule' => 'Master Data', 'activeProgram' => $activeProgram]);
        }
        if ($menu === 'vendor') {
            return view('programs.knmp.master.vendor', ['activeModule' => 'Master Data', 'activeProgram' => $activeProgram]);
        }
        
        return view('programs.knmp.master.index', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram,
            'stage' => $request->query('stage', 'pengajuan')
        ]);
    }
}
