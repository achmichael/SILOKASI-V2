@extends('layouts.app')

@section('title', 'AHP Pairwise Comparison')
@section('page-title', 'Criteria Comparison')
@section('page-subtitle', 'Establish the relative importance of evaluation criteria')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Comparison Matrix</h1>
            <p class="text-slate-500 mt-1">Compare criteria against each other using Saaty's 1-9 scale.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button id="btnReset" class="px-4 py-2.5 text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 font-medium rounded-lg transition-colors flex items-center gap-2">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>Reset</span>
            </button>
            <button id="btnSaveMatrix" class="btn-primary flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-lg shadow-primary-600/20 transition-all hover:shadow-primary-600/40 hover:-translate-y-0.5">
                <i data-lucide="save" class="w-5 h-5"></i>
                <span>Save & Calculate</span>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Saaty's Importance Scale</p>
        <div class="flex flex-wrap gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium border border-slate-200">
                <span class="font-bold text-slate-900">1</span> Equal
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-medium border border-blue-100">
                <span class="font-bold">3</span> Moderate
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-medium border border-indigo-100">
                <span class="font-bold">5</span> Strong
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-purple-50 text-purple-700 text-xs font-medium border border-purple-100">
                <span class="font-bold">7</span> Very Strong
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 text-xs font-medium border border-rose-100">
                <span class="font-bold">9</span> Extreme
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-dashed border-slate-300 text-slate-400 text-xs">
                2, 4, 6, 8 are intermediate values
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Pairwise Input</h3>
                    <span class="text-xs text-slate-500 italic">Blue cells are editable</span>
                </div>
                
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-center border-collapse" id="matrixContainer">
                        <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <tr>
                                <td class="p-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-10 h-10 border-4 border-primary-100 border-t-primary-600 rounded-full animate-spin mb-3"></div>
                                        <p class="text-slate-500 font-medium">Generating matrix...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm h-full flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800">Consistency Report</h3>
                </div>
                
                <div class="p-6 flex-1 flex flex-col justify-center">
                    <div id="consistencyPlaceholder" class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i data-lucide="calculator" class="w-8 h-8"></i>
                        </div>
                        <p class="text-slate-900 font-medium">Waiting for Calculation</p>
                        <p class="text-sm text-slate-500 mt-1">
                            Fill the matrix and click save to check consistency.
                        </p>
                    </div>

                    <div id="consistencyResult" class="hidden space-y-6">
                        
                        <div class="text-center">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Consistency Ratio (CR)</p>
                            <div class="flex items-center justify-center gap-2">
                                <span id="crValue" class="text-4xl font-bold text-slate-800">0.00</span>
                                <span id="crBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Valid</span>
                            </div>
                            <p id="crMessage" class="text-sm text-slate-500 mt-2">
                                The matrix is consistent (CR < 0.1).
                            </p>
                        </div>

                        <div class="relative pt-4">
                            <div class="flex justify-between text-xs text-slate-400 mb-1">
                                <span>0.0</span>
                                <span>Threshold (0.1)</span>
                                <span>0.2+</span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div id="crProgressBar" class="h-full bg-emerald-500 transition-all duration-1000" style="width: 0%"></div>
                            </div>
                            <div class="absolute top-4 left-1/2 -ml-px w-0.5 h-3 bg-slate-300 z-10" title="Threshold 0.1" style="left: 50%"></div> 
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                            <div>
                                <p class="text-xs text-slate-400">Consistency Index (CI)</p>
                                <p id="ciValue" class="font-mono font-semibold text-slate-700">0.00</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Random Index (RI)</p>
                                <p id="riValue" class="font-mono font-semibold text-slate-700">0.00</p>
                            </div>
                        </div>

                         <div id="alertBox" class="hidden p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-sm flex items-start gap-3">
                            <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <span class="font-bold block">Inconsistent Matrix!</span>
                                Please revise your comparisons. The judgments are contradictory.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@vite('resources/js/pages/pairwise.js')
@endsection