<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SILOKASI - Decision Support System')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/js/app.js'])

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        }

        .nav-item.active {
            background-color: #f0f9ff;
            color: #0284c7;
            border-right: 3px solid #0284c7;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-600 antialiased">
    <div class="flex h-screen overflow-hidden">
        <aside id="sidebar"
            class="fixed lg:static inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
            <div class="h-20 flex items-center px-8 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="bg-primary-600 p-2 rounded-lg text-white">
                        <i data-lucide="layers" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 tracking-tight">SILOKASI</h1>
                        <p class="text-xs text-slate-400 font-medium tracking-wide">GDSS PLATFORM</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-8 overflow-y-auto">
                <div>
                    <p class="px-4 text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Overview</p>
                    <a href="{{ route('dashboard') }}"
                        class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-50 hover:text-slate-900 transition-colors {{ request()->routeIs('dashboard') ? 'active' : 'text-slate-600' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('profile') }}"
                        class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-50 hover:text-slate-900 transition-colors {{ request()->routeIs('profile') ? 'active' : 'text-slate-600' }}">
                        <i data-lucide="user-circle" class="w-5 h-5"></i>
                        <span>My Profile</span>
                    </a>
                </div>

                @if (Auth::check() && Auth::user()->role === 'admin')
                    <div>
                        <p class="px-4 text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Data Master</p>
                        <div class="space-y-1">
                            <a href="{{ route('criteria.index') }}"
                                class="nav-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-50 hover:text-slate-900 transition-colors {{ request()->routeIs('criteria.*') ? 'active' : 'text-slate-600' }}">
                                <i data-lucide="sliders" class="w-5 h-5"></i>
                                <span>Criteria Setup</span>
                            </a>
                            <a href="{{ route('alternatives.index') }}"
                                class="nav-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-50 hover:text-slate-900 transition-colors {{ request()->routeIs('alternatives.*') ? 'active' : 'text-slate-600' }}">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                                <span>Alternatives</span>
                            </a>
                            <a href="{{ route('decision-makers.index') }}"
                                class="nav-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-50 hover:text-slate-900 transition-colors {{ request()->routeIs('decision-makers.*') ? 'active' : 'text-slate-600' }}">
                                <i data-lucide="users" class="w-5 h-5"></i>
                                <span>Decision Makers</span>
                            </a>
                        </div>
                    </div>
                @endif

                <div>
                    <p class="px-4 text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Analysis Workflow</p>
                    <div class="relative pl-4 space-y-1">
                        <div class="absolute left-6 top-2 bottom-4 w-px bg-slate-200"></div>

                        <a href="{{ route('pairwise.index') }}"
                            class="relative flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-50 hover:text-slate-900 transition-colors {{ request()->routeIs('pairwise.*') ? 'active' : 'text-slate-600' }}">
                            <div
                                class="w-1.5 h-1.5 rounded-full bg-slate-300 {{ request()->routeIs('pairwise.*') ? '!bg-primary-600 ring-4 ring-primary-100' : '' }}">
                            </div>
                            <span>1. AHP Matrix</span>
                        </a>
                        <a href="{{ route('anp.index') }}"
                            class="relative flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-50 hover:text-slate-900 transition-colors {{ request()->routeIs('anp.*') ? 'active' : 'text-slate-600' }}">
                            <div
                                class="w-1.5 h-1.5 rounded-full bg-slate-300 {{ request()->routeIs('anp.*') ? '!bg-primary-600 ring-4 ring-primary-100' : '' }}">
                            </div>
                            <span>2. ANP Network</span>
                        </a>
                        <a href="{{ route('ratings.index') }}"
                            class="relative flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-50 hover:text-slate-900 transition-colors {{ request()->routeIs('ratings.*') ? 'active' : 'text-slate-600' }}">
                            <div
                                class="w-1.5 h-1.5 rounded-full bg-slate-300 {{ request()->routeIs('ratings.*') ? '!bg-primary-600 ring-4 ring-primary-100' : '' }}">
                            </div>
                            <span>3. Voting/Ratings</span>
                        </a>
                        @if (Auth::check() && Auth::user()->role === 'decision_maker')
                            <a href="{{ route('my-results.index') }}"
                                class="relative flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-50 hover:text-slate-900 transition-colors {{ request()->routeIs('my-results.*') ? 'active' : 'text-slate-600' }}">
                                <div
                                    class="w-1.5 h-1.5 rounded-full bg-slate-300 {{ request()->routeIs('my-results.*') ? '!bg-primary-600 ring-4 ring-primary-100' : '' }}">
                                </div>
                                <span>4. My Rankings</span>
                            </a>
                        @endif
                        @if (Auth::check() && Auth::user()->role === 'admin')
                            <a href="{{ route('results.index') }}"
                                class="relative flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-50 hover:text-slate-900 transition-colors {{ request()->routeIs('results.*') ? 'active' : 'text-slate-600' }}">
                                <div
                                    class="w-1.5 h-1.5 rounded-full bg-slate-300 {{ request()->routeIs('results.*') ? '!bg-primary-600 ring-4 ring-primary-100' : '' }}">
                                </div>
                                <span>4. Final Results</span>
                            </a>
                        @endif
                    </div>
                </div>
            </nav>

            <div class="p-4 border-t border-slate-100">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                            AD
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900 truncate">Administrator</p>
                            <p class="text-xs text-slate-500 truncate">admin@silokasi.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="glass-effect sticky top-0 z-40 px-8 h-20 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggle" class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <div class="hidden md:block">
                        <h2 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-xs text-slate-500">@yield('page-subtitle', 'Welcome back to the decision hub')</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button class="p-2 text-slate-400 hover:text-primary-600 transition-colors relative">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span
                            class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                    <div class="h-8 w-px bg-slate-200 mx-1"></div>
                    <button class="flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900">
                        <span>Settings</span>
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <div id="overlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity">
    </div>

    <script>
        lucide.createIcons();

        // Sidebar Logic
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const btn = document.getElementById('sidebarToggle');

        function toggleSidebar() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        btn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    </script>

    @yield('page-scripts')
    @stack('scripts')
</body>

</html>
