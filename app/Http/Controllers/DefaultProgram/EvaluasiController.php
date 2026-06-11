<?php

namespace App\Http\Controllers\DefaultProgram;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;

class EvaluasiController extends ProgramBaseController
{
    public function index($program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        
        return view('programs.default.evaluasi.index', [
            'activeModule' => 'Evaluasi',
            'activeProgram' => $activeProgram
        ]);
    }
}
