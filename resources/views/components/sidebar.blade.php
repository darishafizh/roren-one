@props(['activeProgram' => 'Bioflok', 'activeModule' => 'Dashboard'])

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
       class="fixed lg:relative z-40 w-64 h-full bg-bgSurface-light dark:bg-bgSurface-dark border-r border-gray-200 dark:border-gray-800 transition-transform duration-300 flex flex-col lg:translate-x-0">
    
    <!-- Context Header -->
    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 shrink-0 bg-gray-50/50 dark:bg-gray-800/20">
        <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest mb-1">
            Modul {{ $activeModule }}
        </div>
        <div class="font-semibold text-teal-light dark:text-teal-dark flex items-center gap-2">
            <i class="fa-solid fa-layer-group"></i>
            {{ $activeProgram }}
        </div>
    </div>

    <!-- Navigation Items -->
    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        
        @if($activeModule === 'Dashboard')
            <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Ringkasan Eksekutif</div>
            
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                <i class="fa-solid fa-person-digging w-5 text-center text-white"></i>
                <span class="font-medium text-sm">Progres Fisik</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>
                <span class="font-medium text-sm">Produksi</span>
            </a>
            
        @elseif($activeModule === 'Operasional')
            <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Manajemen Data</div>
            
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                <i class="fa-solid fa-location-dot w-5 text-center text-white"></i>
                <span class="font-medium text-sm">Data Lokasi / Titik</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                <i class="fa-solid fa-clipboard-list w-5 text-center"></i>
                <span class="font-medium text-sm">Input Progres Fisik</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                <i class="fa-solid fa-truck-ramp-box w-5 text-center"></i>
                <span class="font-medium text-sm">Input Data Produksi</span>
            </a>
            
        @elseif($activeModule === 'Master Data')
            <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Referensi Program</div>
            
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                <i class="fa-solid fa-tags w-5 text-center text-white"></i>
                <span class="font-medium text-sm">Kategori Indikator</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                <i class="fa-solid fa-users-gear w-5 text-center"></i>
                <span class="font-medium text-sm">Penyedia / Vendor</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-blue-400">
                <i class="fa-solid fa-map-pin w-5 text-center"></i>
                <span class="font-medium text-sm">Wilayah Tugas</span>
            </a>
            
        @elseif($activeModule === 'Evaluasi')
            <div class="text-[0.65rem] font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-widest px-3 mb-2">Pelaporan & Audit</div>
            
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md">
                <i class="fa-solid fa-file-pdf w-5 text-center text-white"></i>
                <span class="font-medium text-sm">Ekspor Laporan</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-teal-light">
                <i class="fa-solid fa-check-double w-5 text-center"></i>
                <span class="font-medium text-sm">Validasi Evaluasi</span>
            </a>
        @endif
        
    </div>
</aside>
