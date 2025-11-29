@extends('layouts.app')

@section('title', 'My Rankings')
@section('page-title', 'My Rankings & Results')

@section('content')
<div class="min-h-screen bg-gray-50/50 dark:bg-slate-900 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div id="userAvatar" class="w-16 h-16 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center text-xl font-bold text-primary-600 dark:text-primary-400 shadow-sm border border-gray-100 dark:border-slate-700">
                    --
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                        Welcome back, <span id="userName" class="text-primary-600 dark:text-primary-400">Decision Maker</span>
                    </h1>
                    <div class="flex items-center gap-3 mt-1 text-sm text-slate-500 dark:text-slate-400">
                        <span id="userEmail">--</span>
                        <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                        <span class="inline-flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Verified
                        </span>
                    </div>
                </div>
            </div>
            
            <button id="btnRefresh" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-medium shadow-sm border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-all duration-200">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Refresh Data</span>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- My WP Rankings Card -->
            <div class="flex flex-col bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50/30 dark:bg-slate-800/50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Personal Rankings</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Based on your individual ratings</p>
                    </div>
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>

                <div class="flex-1 p-6">
                    <div id="wpRankingsContent" class="h-full flex flex-col">
                        <div class="flex-1 flex flex-col items-center justify-center py-12 text-center space-y-4">
                            <div class="w-8 h-8 border-2 border-slate-200 dark:border-slate-700 border-t-primary-600 rounded-full animate-spin"></div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Calculating...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BORDA Final Results Card -->
            <div class="flex flex-col bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50/30 dark:bg-slate-800/50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Group Consensus</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Final results using Borda Count</p>
                    </div>
                    <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-purple-600 dark:text-purple-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>

                <div class="flex-1 p-6">
                    <div id="bordaResultsContent" class="h-full flex flex-col">
                        <div class="flex-1 flex flex-col items-center justify-center py-12 text-center space-y-4">
                            <div class="w-12 h-12 bg-slate-50 dark:bg-slate-700/50 rounded-xl flex items-center justify-center text-slate-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Waiting for results...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@vite('resources/js/pages/my-results.js')
@endsection
