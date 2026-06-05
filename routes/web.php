<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    if (session('logged_in')) {
        return redirect('/greetings');
    }
    
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    session(['captcha_answer' => $num1 + $num2]);
    
    return view('login', compact('num1', 'num2'));
});

Route::post('/login', function (Request $request) {
    if ((int)$request->captcha !== session('captcha_answer')) {
        return back()->with('error', 'Jawaban perhitungan matematika salah.');
    }

    if ($request->username === 'admin' && $request->password === 'admin123') {
        session(['logged_in' => true, 'username' => 'admin']);
        return redirect('/greetings');
    }
    
    return back()->with('error', 'Username atau password salah.');
});

Route::get('/users', function () {
    if (!session('logged_in') || session('username') !== 'admin') {
        return redirect('/login');
    }
    return view('users', ['activeModule' => 'Pengguna', 'activeProgram' => 'Manajemen Sistem']);
});

Route::get('/greetings', function () {
    if (!session('logged_in')) {
        return redirect('/login');
    }
    
    // Seamlessly upgrade existing users to admin
    if (!session()->has('username') || session('username') !== 'admin') {
        session(['username' => 'admin']);
    }
    
    $programs = [
        ['name' => 'KNMP', 'icon' => 'fa-ship', 'color' => 'bg-info'],
        ['name' => 'Bioflok', 'icon' => 'fa-water', 'color' => 'bg-teal-light'],
        ['name' => 'Minapadi', 'icon' => 'fa-seedling', 'color' => 'bg-success'],
        ['name' => 'BINS', 'icon' => 'fa-box', 'color' => 'bg-warning'],
        ['name' => 'Swasembada Garam', 'icon' => 'fa-cubes', 'color' => 'bg-navy-light'],
        ['name' => 'Revitalisasi Pantura', 'icon' => 'fa-anchor', 'color' => 'bg-blue-500'],
        ['name' => 'Modernisasi Kapal', 'icon' => 'fa-ferry', 'color' => 'bg-indigo-500'],
        ['name' => 'ISF Waingapu', 'icon' => 'fa-map-location-dot', 'color' => 'bg-purple-500'],
        ['name' => 'Sarpras Pendidikan KP', 'icon' => 'fa-school', 'color' => 'bg-orange-500']
    ];
    
    return view('greetings', compact('programs'));
});

Route::get('/logout', function () {
    session()->forget('logged_in');
    return redirect('/login');
});

// Helper for auth check
$authCheck = function() {
    if (!session('logged_in')) {
        abort(redirect('/login'));
    }
};

// Helper for formatting program names
$formatProgram = function($program) {
    $name = str_replace('-', ' ', $program);
    if (in_array(strtolower($name), ['knmp', 'bins'])) {
        return strtoupper($name);
    }
    return ucwords($name);
};

Route::get('/dashboard/{program}', function ($program) use ($authCheck, $formatProgram) {
    $authCheck();
    $activeProgram = $formatProgram($program);
    return view('welcome', [
        'activeModule' => 'Dashboard',
        'activeProgram' => $activeProgram
    ]);
});

Route::get('/master/{program}', function ($program) use ($authCheck, $formatProgram) {
    $authCheck();
    $activeProgram = $formatProgram($program);
    return view('master', [
        'activeModule' => 'Master Data',
        'activeProgram' => $activeProgram
    ]);
});

Route::get('/operasional/{program}', function ($program) use ($authCheck, $formatProgram) {
    $authCheck();
    $activeProgram = $formatProgram($program);
    return view('operasional', [
        'activeModule' => 'Operasional',
        'activeProgram' => $activeProgram
    ]);
});

Route::get('/evaluasi/{program}', function ($program) use ($authCheck, $formatProgram) {
    $authCheck();
    $activeProgram = $formatProgram($program);
    return view('evaluasi', [
        'activeModule' => 'Evaluasi',
        'activeProgram' => $activeProgram
    ]);
});
