@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'Account Settings')
@section('page-subtitle', 'Manage your personal information and preferences')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Profile & Settings</h1>
            <p class="text-slate-500 mt-1">Update your account details and decision making parameters.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button id="btnSaveProfile" class="btn-primary flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-lg shadow-primary-600/20 transition-all hover:shadow-primary-600/40 hover:-translate-y-0.5">
                <i data-lucide="save" class="w-5 h-5"></i>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden relative group">
                <div class="h-24 bg-gradient-to-r from-primary-500 to-indigo-600"></div>
                <div class="px-6 pb-6">
                    <div class="relative flex justify-center -mt-12 mb-4">
                        <div class="w-24 h-24 rounded-full bg-white p-1 shadow-lg">
                            <div id="profileAvatar" class="w-full h-full rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-3xl font-bold border border-slate-200">
                                --
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center space-y-1">
                        <h3 id="profileNameDisplay" class="font-bold text-xl text-slate-800">Loading...</h3>
                        <p id="profileEmailDisplay" class="text-sm text-slate-500">Please wait</p>
                        <div class="pt-2">
                            <span id="profileRoleBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                --
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <div class="flex justify-between items-center text-sm mb-2">
                            <span class="text-slate-500">Account Status</span>
                            <span class="text-emerald-600 font-bold flex items-center gap-1">
                                <i data-lucide="check-circle-2" class="w-3 h-3"></i> Active
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Member Since</span>
                            <span id="profileJoined" class="font-medium text-slate-700">--</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decision Maker Status Card -->
            <div id="dmStatusCard" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hidden">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i data-lucide="award" class="w-4 h-4 text-primary-500"></i>
                    Decision Maker Status
                </h3>
                
                <div id="dmActiveContent" class="space-y-4 hidden">
                    <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-lg flex items-start gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-bold text-emerald-800">Profile Linked</p>
                            <p class="text-xs text-emerald-600 mt-0.5">Your account is successfully linked to a Decision Maker profile.</p>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-500">Influence Weight</span>
                            <span id="weightDisplay" class="font-bold text-slate-700">0%</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div id="weightBar" class="h-full bg-primary-500 transition-all duration-1000" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <div id="dmMissingContent" class="space-y-4 hidden">
                    <div class="p-3 bg-amber-50 border border-amber-100 rounded-lg flex items-start gap-3">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-bold text-amber-800">Profile Missing</p>
                            <p class="text-xs text-amber-600 mt-0.5">Please complete your Decision Maker profile to participate in evaluations.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Edit Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                    <i data-lucide="user-cog" class="w-4 h-4 text-slate-400"></i>
                    <h3 class="font-bold text-slate-800">Edit Profile</h3>
                </div>
                
                <div class="p-6 space-y-6">
                    <form id="profileForm" class="space-y-6">
                        <!-- Personal Info Section -->
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Personal Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="name" class="text-sm font-medium text-slate-700">Full Name</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i data-lucide="user" class="h-4 w-4 text-slate-400"></i>
                                        </div>
                                        <input type="text" id="name" name="name" class="pl-10 w-full rounded-lg border-slate-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="Your full name">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="email" class="text-sm font-medium text-slate-700">Email Address</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i data-lucide="mail" class="h-4 w-4 text-slate-400"></i>
                                        </div>
                                        <input type="email" id="email" name="email" class="pl-10 w-full rounded-lg border-slate-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="name@example.com">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Section -->
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Security</h4>
                            <div class="space-y-4">
                                <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                                    <p class="text-sm text-slate-600 mb-4">Leave blank if you don't want to change your password.</p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label for="password" class="text-sm font-medium text-slate-700">New Password</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i data-lucide="lock" class="h-4 w-4 text-slate-400"></i>
                                                </div>
                                                <input type="password" id="password" name="password" class="pl-10 w-full rounded-lg border-slate-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="••••••••">
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="password_confirmation" class="text-sm font-medium text-slate-700">Confirm Password</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i data-lucide="lock" class="h-4 w-4 text-slate-400"></i>
                                                </div>
                                                <input type="password" id="password_confirmation" name="password_confirmation" class="pl-10 w-full rounded-lg border-slate-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="••••••••">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@vite('resources/js/pages/profile.js')
@endsection
