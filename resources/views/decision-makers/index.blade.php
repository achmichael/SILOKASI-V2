@extends('layouts.app')

@section('title', 'Decision Makers Management')
@section('page-title', 'Stakeholder Management')
@section('page-subtitle', 'Configure decision makers and their voting weights')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Decision Makers</h1>
            <p class="text-slate-500 mt-1">Manage individuals participating in the group decision process.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button id="btnAdd" class="btn-primary flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                <span>Add Stakeholder</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Stakeholders</p>
                <p class="text-xl font-bold text-slate-800 mt-1" id="statTotal">--</p>
            </div>
            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i data-lucide="users" class="w-5 h-5"></i></div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Weight Allocation</p>
                <div class="flex items-end gap-2 mt-1">
                    <p class="text-xl font-bold text-slate-800" id="statWeight">--</p>
                    <span class="text-xs font-medium text-slate-500 mb-1">/ 1.00</span>
                </div>
            </div>
            <div class="p-2 bg-purple-50 text-purple-600 rounded-lg relative z-10"><i data-lucide="scale" class="w-5 h-5"></i></div>
            
            <div class="absolute bottom-0 left-0 h-1 bg-purple-500 transition-all duration-1000" id="weightProgressBar" style="width: 0%"></div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Configuration Status</p>
                <p class="text-sm font-bold mt-1" id="statStatus">Checking...</p>
            </div>
            <div class="p-2 bg-slate-50 text-slate-600 rounded-lg"><i data-lucide="activity" class="w-5 h-5"></i></div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Name & Role</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Influence (Weight)</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center w-32">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center w-24">Actions</th>
                    </tr>
                </thead>
                <tbody id="dmTableBody" class="divide-y divide-slate-100">
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-10 h-10 border-4 border-primary-100 border-t-primary-600 rounded-full animate-spin mb-3"></div>
                                <p class="text-slate-500 font-medium">Loading stakeholders...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="dmModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modalPanel">
                
                <div class="bg-white px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900" id="modalTitle">Add Decision Maker</h3>
                    <button type="button" id="btnCloseModal" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form id="dmForm">
                    <div class="px-6 py-6 space-y-5">
                        <input type="hidden" id="dmId">
                        
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                                </div>
                                <input type="text" id="name" class="block w-full pl-10 pr-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-900 placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all sm:text-sm" placeholder="e.g., Dr. Budi Santoso" required>
                            </div>
                        </div>

                        <div>
                            <label for="position" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Position / Role
                            </label>
                            <input type="text" id="position" class="block w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-900 placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all sm:text-sm" placeholder="e.g., Urban Planner">
                        </div>

                        <div>
                            <label for="weight" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Voting Weight (0.0 - 1.0) <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-4">
                                <div class="relative flex-1">
                                    <input type="number" id="weight" step="0.01" min="0" max="1" class="block w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-900 placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all sm:text-sm" placeholder="0.5" required>
                                </div>
                                <div class="text-xs text-slate-500 w-24 leading-tight">
                                    Determines influence in BORDA
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 sm:w-auto transition-all">
                            <span id="btnSubmitText">Save Stakeholder</span>
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
        <td class="px-6 py-4">
            <div class="flex items-center">
                <div class="h-10 w-10 flex-shrink-0 rounded-full bg-gradient-to-br from-primary-100 to-indigo-100 flex items-center justify-center text-primary-700 font-bold text-sm avatar-cell border border-primary-200">
                    XX
                </div>
                <div class="ml-4">
                    <div class="text-sm font-bold text-slate-900 name-cell"></div>
                    <div class="text-xs text-slate-500 role-cell mt-0.5"></div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 align-middle">
            <div class="w-full max-w-xs">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-mono font-medium text-slate-700 weight-text">0.00</span>
                    <span class="text-xs text-slate-400 percentage-text">0%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="bg-primary-600 h-2 rounded-full weight-bar" style="width: 0%"></div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Active</span>
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
@vite('resources/js/pages/decision-makers.js')
@endsection