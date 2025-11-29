@extends('layouts.app')

@section('title', 'Decision Makers Management')
@section('page-title', 'Stakeholder Management')
@section('page-subtitle', 'View users with decision maker role')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Decision Makers</h1>
            <p class="text-slate-500 mt-1">View individuals participating in the group decision process.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Decision Makers</p>
                <p class="text-xl font-bold text-slate-800 mt-1" id="statTotal">--</p>
            </div>
            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i data-lucide="users" class="w-5 h-5"></i></div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status</p>
                <p class="text-sm font-bold mt-1 text-emerald-600">All Active</p>
            </div>
            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg"><i data-lucide="check-circle-2" class="w-5 h-5"></i></div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Name & Email</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center w-32">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Joined</th>
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


@endsection

@section('page-scripts')
@vite('resources/js/pages/decision-makers.js')
@endsection