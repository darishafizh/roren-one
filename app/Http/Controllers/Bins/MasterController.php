<?php

namespace App\Http\Controllers\Bins;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;

class MasterController extends ProgramBaseController
{
    public function index(Request $request, $program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        
        return view('programs.bins.master.index', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram,
            'type' => $request->query('type', 'petak')
        ]);
    }
}
