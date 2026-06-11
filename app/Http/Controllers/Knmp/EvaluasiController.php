<?php

namespace App\Http\Controllers\Knmp;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;

class EvaluasiController extends ProgramBaseController
{
    public function index($program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        
        return view("programs.knmp.evaluasi.index", [
            'activeModule' => 'Evaluasi',
            'activeProgram' => $activeProgram
        ]);
    }
}
