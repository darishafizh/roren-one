<?php

namespace App\Http\Controllers\DefaultProgram;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;

class OperasionalController extends ProgramBaseController
{
    public function index($program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        
        return view('programs.default.operasional.index', [
            'activeModule' => 'Operasional',
            'activeProgram' => $activeProgram
        ]);
    }
}
