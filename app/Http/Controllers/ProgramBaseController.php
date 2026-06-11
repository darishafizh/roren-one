<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgramBaseController extends Controller
{
    protected function checkAuth()
    {
        if (!session('logged_in')) {
            abort(redirect('/login'));
        }
    }

    protected function formatProgramName($program)
    {
        $name = str_replace('-', ' ', $program);
        if (in_array(strtolower($name), ['knmp', 'bins'])) {
            return strtoupper($name);
        }
        return ucwords($name);
    }
}
