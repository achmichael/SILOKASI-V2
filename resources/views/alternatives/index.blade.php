@extends('layouts.app')

@section('title', 'Alternatives Management')
@section('page-title', 'Locations Setup')
@section('page-subtitle', 'Manage housing alternatives and location data')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Housing Alternatives</h1>
            <p class="text-slate-500 mt-1">Define the location candidates to be evaluated by the system.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="relative hidden sm:block">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" placeholder="Search locations..." class="pl-9 pr-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-primary-100 focus:border-primary-500 w-64">
            </div>
            
            <button id="btnAdd" class="btn-primary flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-lg shadow-primary-600/20 transition-all hover:shadow-primary-600/40 hover:-translate-y-0.5">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>Add Alternative</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Locations</p>
                <p class="text-xl font-bold text-slate-800 mt-1" id="statTotal">--</p>
            </div>
            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg"><i data-lucide="map" class="w-5 h-5"></i></div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Evaluated</p>
                <p class="text-xl font-bold text-emerald-600 mt-1" id="statEvaluated">--</p>
            </div>
            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg"><i data-lucide="check-circle-2" class="w-5 h-5"></i></div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Top Candidate</p>
                <p class="text-lg font-bold text-amber-600 mt-1 truncate max-w-[120px]" id="statTop">--</p>
            </div>
            <div class="p-2 bg-amber-50 text-amber-600 rounded-lg"><i data-lucide="trophy" class="w-5 h-5"></i></div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-28">Code</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Name & Description</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-36 text-right">WP Score</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-28 text-center">Rank</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-32 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="alternativesTableBody" class="divide-y divide-slate-100">
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-10 h-10 border-4 border-primary-100 border-t-primary-600 rounded-full animate-spin mb-3"></div>
                                <p class="text-slate-500 font-medium">Loading locations data...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="alternativeModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modalPanel">
                
                <div class="bg-white px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900" id="modalTitle">Add Alternative</h3>
                    <button type="button" id="btnCloseModal" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form id="alternativeForm">
                    <div class="px-6 py-6 space-y-5">
                        <input type="hidden" id="alternativeId">
                        
                        <div class="grid grid-cols-3 gap-5">
                            <div class="col-span-1">
                                <label for="code" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Code <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-mono text-xs">#</span>
                                    </div>
                                    <input type="text" id="code" class="block w-full pl-8 pr-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-900 placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all sm:text-sm" placeholder="A1" required>
                                </div>
                            </div>

                            <div class="col-span-2">
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Location Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" class="block w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-900 placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all sm:text-sm" placeholder="e.g., Perumahan Griya Asri" required>
                            </div>
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Address / Area
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i>
                                </div>
                                <input type="text" id="location" class="block w-full pl-10 pr-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-900 placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all sm:text-sm" placeholder="e.g., Jl. Soekarno Hatta No. 12">
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Description
                            </label>
                            <textarea id="description" rows="3" class="block w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-900 placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all sm:text-sm resize-none" placeholder="Additional details about this alternative..."></textarea>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 sm:w-auto transition-all">
                            <span id="btnSubmitText">Save Alternative</span>
                        </button>
                        <button type="button" id="btnCancel" class="inline-flex w-full justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:w-auto transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<template id="rowTemplate">
    <tr class="hover:bg-slate-50 group transition-colors">
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center justify-center h-8 w-10 rounded-md bg-slate-100 text-slate-700 font-mono font-bold text-sm code-cell shadow-sm border border-slate-200"></span>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm font-bold text-slate-900 name-cell"></div>
            <div class="text-xs text-slate-500 truncate max-w-[200px] desc-cell mt-0.5"></div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right font-mono text-sm font-medium text-slate-700 score-cell">
            -
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="rank-badge inline-flex items-center justify-center w-8 h-8 rounded-full text-xs font-bold"></span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="btn-edit p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition-colors" title="Edit">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                </button>
                <button class="btn-delete p-1.5 text-rose-600 hover:bg-rose-50 rounded-md transition-colors" title="Delete">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        </td>
    </tr>
</template>
@endsection

@section('page-scripts')
@vite('resources/js/pages/alternatives.js')
@endsection