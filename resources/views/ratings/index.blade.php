@extends('layouts.app')

@section('title', 'My Ratings')
@section('page-title', 'Assessment Matrix')
@section('page-subtitle', 'Input your qualitative scores for alternatives')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">My Performance Ratings</h1>
            <p class="text-slate-500 mt-1">Evaluate housing alternatives based on your expertise.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button id="btnReset" class="px-4 py-2.5 text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 font-medium rounded-lg transition-colors flex items-center gap-2">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>Reset Form</span>
            </button>
            
            <button id="btnSaveRatings" class="btn-primary flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-lg shadow-primary-600/20 transition-all hover:shadow-primary-600/40 hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                <i data-lucide="save" class="w-5 h-5"></i>
                <span>Save My Ratings</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-4 space-y-6">
            
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden relative group">
                <div class="h-2 bg-gradient-to-r from-primary-500 to-indigo-600"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider border border-slate-200 rounded-full px-2 py-1">
                            Logged in as
                        </span>
                        <div id="loadingIndicator" class="w-4 h-4 border-2 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
                    </div>

                    <div id="dmDetails" class="space-y-6 opacity-50 transition-opacity duration-500">
                        <div class="flex items-center gap-4">
                            <div id="dmAvatar" class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xl font-bold border border-slate-200 shadow-inner">
                                --
                            </div>
                            <div>
                                <h3 id="dmName" class="font-bold text-lg text-slate-800 leading-tight">Loading...</h3>
                                <p id="dmEmail" class="text-xs text-slate-500 font-medium">Please wait</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-[10px] uppercase text-slate-400 font-bold mb-1">Role</p>
                                <p id="dmRole" class="text-sm font-semibold text-slate-700 truncate">--</p>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 relative overflow-hidden">
                                <p class="text-[10px] uppercase text-slate-400 font-bold mb-1">Voting Weight</p>
                                <p id="dmWeight" class="text-lg font-bold text-primary-600">--%</p>
                                <div class="absolute bottom-0 left-0 h-1 bg-primary-500 transition-all duration-1000" id="dmWeightBar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i data-lucide="bar-chart-horizontal" class="w-4 h-4 text-slate-400"></i>
                    Rating Scale Guide
                </h3>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Excellent</span>
                        <span class="w-6 h-6 rounded bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center font-bold text-xs">5</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Good</span>
                        <span class="w-6 h-6 rounded bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center font-bold text-xs">4</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Fair</span>
                        <span class="w-6 h-6 rounded bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center font-bold text-xs">3</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Poor</span>
                        <span class="w-6 h-6 rounded bg-orange-50 text-orange-600 border border-orange-100 flex items-center justify-center font-bold text-xs">2</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Very Poor</span>
                        <span class="w-6 h-6 rounded bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center font-bold text-xs">1</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8" id="ratingsContainer">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col relative min-h-[400px]">
                
                <div id="matrixOverlay" class="absolute inset-0 z-20 bg-white/90 backdrop-blur-sm flex flex-col items-center justify-center rounded-xl transition-opacity duration-500">
                    <div class="p-4 bg-slate-50 rounded-full mb-4 border border-slate-100 shadow-sm">
                        <i data-lucide="lock" class="w-10 h-10 text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Authenticating...</h3>
                    <p class="text-slate-500 text-sm max-w-xs text-center mt-1">Fetching your profile and permissions.</p>
                </div>

                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <i data-lucide="grid" class="w-4 h-4 text-primary-600"></i>
                        <h3 class="font-bold text-slate-800">Evaluation Matrix</h3>
                    </div>
                    <span id="matrixStatus" class="text-xs font-medium text-slate-400">Initializing...</span>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse" id="ratingsTable">
                        <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@vite('resources/js/pages/ratings.js')
@endsection