@extends('layouts.app')

@section('title', 'ANP Interdependency')
@section('page-title', 'Network Matrix')
@section('page-subtitle', 'Define influence relationships between criteria')

@section('content')
<div class="space-y-6">
    
    <!-- 1. Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Interdependency Matrix</h1>
            <p class="text-slate-500 mt-1">Map the directional influence between criteria (Network Process).</p>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- Auto Normalize Button (Optional Future Feature) -->
            <button id="btnReset" class="px-4 py-2.5 text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 font-medium rounded-lg transition-colors flex items-center gap-2">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>Reset</span>
            </button>
            
            <button id="btnSaveMatrix" class="btn-primary flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-lg shadow-primary-600/20 transition-all hover:shadow-primary-600/40 hover:-translate-y-0.5">
                <i data-lucide="save" class="w-5 h-5"></i>
                <span>Save Matrix</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- 2. Main Matrix Area (Span 3) -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col">
                
                <!-- Matrix Toolbar -->
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-indigo-100 text-indigo-600 rounded-md">
                            <i data-lucide="grid" class="w-4 h-4"></i>
                        </div>
                        <h3 class="font-bold text-slate-800">Influence Input</h3>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-white border border-slate-300"></span> Editable
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-slate-100 border border-slate-200"></span> Locked
                        </span>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="w-full text-center border-collapse" id="matrixContainer">
                        <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <!-- Headers injected by JS -->
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            <tr>
                                <td class="p-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-10 h-10 border-4 border-primary-100 border-t-primary-600 rounded-full animate-spin mb-3"></div>
                                        <p class="text-slate-500 font-medium">Loading network structure...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <!-- Footer for Column Sums -->
                        <tfoot class="bg-slate-50 border-t border-slate-200 font-bold text-xs text-slate-600">
                            <!-- Footers injected by JS -->
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- 3. Sidebar Guidelines (Span 1) -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Legend Card -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-primary-500"></i>
                    Value Guide
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">No Influence</span>
                        <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">0.0</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Weak</span>
                        <span class="font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">0.1 - 0.3</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Moderate</span>
                        <span class="font-mono font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">0.4 - 0.6</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Strong</span>
                        <span class="font-mono font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded">0.7 - 0.9</span>
                    </div>
                    <div class="w-full h-px bg-slate-100 my-2"></div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        <strong>Rule:</strong> Values indicate how much the row criterion influences the column criterion.
                    </p>
                </div>
            </div>

            <!-- Validation Status -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl shadow-lg p-5 text-white">
                <h3 class="font-bold mb-2 flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                    Validation
                </h3>
                <p class="text-xs text-slate-300 mb-4">
                    For accurate results, the sum of each column should ideally be 1.0 (Normalized).
                </p>
                
                <div id="validationList" class="space-y-2 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                    <!-- JS will populate this -->
                    <div class="flex items-center gap-2 text-xs opacity-50">
                        <div class="w-2 h-2 rounded-full bg-slate-500"></div>
                        <span>Waiting for input...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@vite('resources/js/pages/anp.js')
@endsection