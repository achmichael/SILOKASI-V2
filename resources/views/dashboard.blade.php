@extends('layouts.app')

@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard Overview')
@section('page-subtitle', 'Monitor progress and quick access to decision tools')

@section('content')
<div class="space-y-8">
    
    <div class="relative bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 md:p-10 text-white overflow-hidden shadow-xl">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="max-w-2xl">
                <h2 class="text-3xl font-bold mb-3">Decision Support System Ready 🚀</h2>
                <p class="text-slate-300 text-lg leading-relaxed">
                    Sistem siap digunakan. Saat ini menggunakan metode hibrida <span class="text-white font-semibold">ANP-WP-BORDA</span> untuk akurasi pemilihan lokasi yang maksimal.
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('pairwise.index') }}" class="bg-primary-600 hover:bg-primary-500 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-primary-900/50 flex items-center gap-2">
                    <i data-lucide="play-circle" class="w-5 h-5"></i>
                    Start Calculation
                </a>
            </div>
        </div>
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/20 rounded-full blur-3xl -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl -ml-16 -mb-16"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-primary-200 transition-colors">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Total Criteria</p>
                <h3 class="text-3xl font-bold text-slate-800" id="criteriaCount">0</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                <i data-lucide="sliders-horizontal" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-emerald-200 transition-colors">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Total Alternatives</p>
                <h3 class="text-3xl font-bold text-slate-800" id="alternativesCount">0</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                <i data-lucide="map-pin" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-violet-200 transition-colors">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Decision Makers</p>
                <h3 class="text-3xl font-bold text-slate-800" id="dmCount">0</h3>
            </div>
            <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center text-violet-600 group-hover:scale-110 transition-transform">
                <i data-lucide="users-2" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i data-lucide="git-commit" class="w-5 h-5 text-slate-400"></i>
                        Process Status
                    </h3>
                    <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-bold uppercase tracking-wide">In Progress</span>
                </div>

                <div class="relative flex justify-between">
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 -translate-y-1/2 rounded-full -z-10"></div>
                    
                    <div class="flex flex-col items-center gap-3 group cursor-pointer">
                        <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-200 ring-4 ring-white">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-semibold text-emerald-600">Data Setup</span>
                    </div>

                    <div class="flex flex-col items-center gap-3 group cursor-pointer">
                        <div class="w-10 h-10 rounded-full bg-primary-600 text-white flex items-center justify-center shadow-lg shadow-primary-200 ring-4 ring-white animate-pulse">
                            <span class="font-bold">2</span>
                        </div>
                        <span class="text-xs font-bold text-primary-700">AHP Matrix</span>
                    </div>

                    <div class="flex flex-col items-center gap-3 group cursor-pointer opacity-50">
                        <div class="w-10 h-10 rounded-full bg-white border-2 border-slate-200 text-slate-400 flex items-center justify-center ring-4 ring-white">
                            <span class="font-bold">3</span>
                        </div>
                        <span class="text-xs font-medium text-slate-500">ANP Network</span>
                    </div>

                    <div class="flex flex-col items-center gap-3 group cursor-pointer opacity-50">
                        <div class="w-10 h-10 rounded-full bg-white border-2 border-slate-200 text-slate-400 flex items-center justify-center ring-4 ring-white">
                            <span class="font-bold">4</span>
                        </div>
                        <span class="text-xs font-medium text-slate-500">Results</span>
                    </div>
                </div>
            </div>

            <h3 class="font-bold text-slate-800 mt-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('criteria.index') }}" class="group p-4 bg-white border border-slate-200 rounded-xl hover:shadow-md transition-all hover:border-primary-200 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="font-semibold text-slate-700 block">Add Criteria</span>
                        <span class="text-xs text-slate-500">Manage evaluation parameters</span>
                    </div>
                </a>

                <a href="{{ route('alternatives.index') }}" class="group p-4 bg-white border border-slate-200 rounded-xl hover:shadow-md transition-all hover:border-emerald-200 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="font-semibold text-slate-700 block">Add Location</span>
                        <span class="text-xs text-slate-500">Insert new alternative</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm h-full flex flex-col">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Top Candidates</h3>
                    <a href="{{ route('results.index') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700">View All</a>
                </div>
                
                <div class="p-6 flex-1 flex flex-col justify-center items-center text-center" id="rankingContainer">
                    <div class="py-8">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i data-lucide="bar-chart-3" class="w-10 h-10"></i>
                        </div>
                        <p class="text-slate-900 font-medium mb-1">No Rankings Yet</p>
                        <p class="text-sm text-slate-500 max-w-[200px] mx-auto">
                            Complete the calculation steps to generate the leaderboard.
                        </p>
                    </div>
                </div>
                
                <div class="p-4 border-t border-slate-100 bg-slate-50 rounded-b-2xl">
                    <button class="w-full py-2.5 rounded-lg border border-slate-300 text-slate-600 font-semibold text-sm hover:bg-white hover:shadow-sm transition-all">
                        Generate Report PDF
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                // Anggap ini data dari database
                document.getElementById('criteriaCount').innerText = '5';
                document.getElementById('alternativesCount').innerText = '12';
                document.getElementById('dmCount').innerText = '3';
            }, 500);
        });
    </script>
@endpush