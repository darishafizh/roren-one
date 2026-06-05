<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilih Program - Program Prioritas</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-kkp.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Theme Initialization -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body x-data="{ darkMode: document.documentElement.classList.contains('dark') }" class="antialiased bg-bgBody-light dark:bg-bgBody-dark text-textMain-light dark:text-textMain-dark min-h-screen flex flex-col">

    <!-- Topbar (Minimal) -->
    <header class="h-16 w-full bg-bgSurface-light dark:bg-bgSurface-dark border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-6 z-50">
        <div class="flex items-center gap-3">
            <img src="{{ asset('assets/images/logo-kkp.png') }}" alt="Logo KKP" class="w-8 h-8 object-contain dark:bg-white dark:rounded-full dark:p-0.5 transition-all">
            <h1 class="font-bold text-lg hidden sm:block bg-clip-text text-transparent bg-gradient-to-r from-navy-light to-teal-light dark:from-teal-dark dark:to-teal-light">
                Program Prioritas
            </h1>
        </div>

        <div class="flex items-center gap-4">
            @if(session('username') === 'admin')
            <a href="/users" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg border border-teal-light/20 bg-teal-light/5 hover:bg-teal-light/10 dark:border-teal-dark/30 dark:bg-teal-dark/10 dark:hover:bg-teal-dark/20 text-teal-light dark:text-teal-400 text-xs font-bold transition-colors">
                <i class="fa-solid fa-users-gear"></i> Pengguna
            </a>
            @endif
            
            <button @click="darkMode = !darkMode; if(darkMode) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }" 
                    class="w-9 h-9 rounded-full flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-textMuted-light dark:text-textMuted-dark hover:text-teal-light dark:hover:text-teal-dark transition-all">
                <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
            </button>
            <div x-data="{ open: false }" class="relative" @click.away="open = false">
                <button @click="open = !open" class="flex items-center gap-3 focus:outline-none text-left rounded-lg p-1 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="text-right hidden sm:block">
                        <div class="font-semibold text-xs text-textMain-light dark:text-textMain-dark">{{ session('username') === 'admin' ? 'Administrator' : 'Pengguna' }}</div>
                        <div class="text-[0.65rem] text-textMuted-light dark:text-textMuted-dark">Sistem Terpadu</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-info to-teal-light text-white flex items-center justify-center font-bold shadow-sm text-sm">
                            {{ session('username') === 'admin' ? 'A' : 'P' }}
                        </div>
                        <i class="fa-solid fa-chevron-down text-[0.6rem] text-textMuted-light dark:text-textMuted-dark"></i>
                    </div>
                </button>
                <div x-show="open" x-transition class="absolute top-full right-0 mt-2 w-48 bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-200 dark:border-gray-800 rounded-xl shadow-lg py-2">
                    <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-800 mb-1">
                        <p class="text-xs text-textMuted-light dark:text-textMuted-dark">Login sebagai:</p>
                        <p class="text-sm font-bold truncate">{{ session('username') ?? 'User' }}</p>
                    </div>
                    <a href="/logout" class="block px-4 py-2 text-sm text-danger hover:bg-red-50 dark:hover:bg-red-900/10 font-bold transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Keluar
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-6xl w-full mx-auto px-6 py-12">
        
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-extrabold mb-4 tracking-tight">Selamat Datang, Administrator</h2>
            <p class="text-textMuted-light dark:text-textMuted-dark max-w-2xl mx-auto">Silakan pilih Program Prioritas yang ingin Anda kelola. Setiap program memiliki ruang lingkup pelaporan, pengawasan, dan analisis datanya sendiri.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($programs as $prog)
            <a href="/dashboard/{{ strtolower(str_replace(' ', '-', $prog['name'])) }}" 
               class="group block bg-bgSurface-light dark:bg-bgSurface-dark rounded-3xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-xl hover:border-teal-light/50 dark:hover:border-teal-dark/50 transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-8">
                    <div class="w-14 h-14 rounded-2xl {{ $prog['color'] }} bg-opacity-10 dark:bg-opacity-20 flex items-center justify-center">
                        <i class="fa-solid {{ $prog['icon'] }} text-2xl {{ str_replace('bg-', 'text-', $prog['color']) }} dark:text-white"></i>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-400 group-hover:text-teal-light dark:group-hover:text-teal-dark group-hover:bg-teal-light/10 transition-colors">
                        <i class="fa-solid fa-arrow-right -rotate-45 group-hover:rotate-0 transition-transform"></i>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-textMain-light dark:text-textMain-dark group-hover:text-teal-light dark:group-hover:text-teal-400 transition-colors mb-2">
                    {{ in_array(strtolower($prog['name']), ['knmp', 'bins']) ? strtoupper($prog['name']) : $prog['name'] }}
                </h3>
                <p class="text-xs text-textMuted-light dark:text-textMuted-dark line-clamp-2">
                    Kelola data operasional, pantau progres fisik, dan buat evaluasi kinerja untuk program {{ $prog['name'] }}.
                </p>
            </a>
            @endforeach
        </div>

    </main>

    <footer class="py-6 text-center text-xs text-textMuted-light dark:text-textMuted-dark border-t border-gray-200 dark:border-gray-800 bg-bgSurface-light dark:bg-bgSurface-dark">
        &copy; 2026 Program Prioritas - Kementerian Kelautan dan Perikanan
    </footer>

</body>
</html>
