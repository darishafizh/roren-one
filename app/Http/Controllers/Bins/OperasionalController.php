<?php

namespace App\Http\Controllers\Bins;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;

class OperasionalController extends ProgramBaseController
{
    public function index(Request $request, $program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        
        return view('programs.bins.operasional.index', [
            'activeModule' => 'Operasional',
            'activeProgram' => $activeProgram
        ]);
    }
}
