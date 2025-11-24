<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILOKASI - Intelligent Group Decision Support System</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            200: '#fecdd3',
                            300: '#fda4af',
                            400: '#fb7185',
                            500: '#f43f5e', // Rose/Pink base
                            600: '#e11d48',
                            700: '#be123c',
                            800: '#9f1239',
                            900: '#881337',
                        },
                        accent: {
                            500: '#f97316', // Orange
                            600: '#ea580c',
                        }
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'fade-up': 'fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        fadeUp: {
                            '0%': { opacity: 0, transform: 'translateY(20px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }

        /* Glassmorphism Utilities */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .glass-dark {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mesh-bg {
            background-color: #ffffff;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,0) 0, hsla(253,16%,7%,0) 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,0) 0, hsla(225,39%,30%,0) 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,0) 0, hsla(339,49%,30%,0) 50%);
            position: relative;
        }

        /* Animated Gradient Text */
        .text-gradient {
            background: linear-gradient(to right, #f43f5e, #f97316, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200% auto;
            animation: shine 4s linear infinite;
        }

        @keyframes shine {
            to {
                background-position: 200% center;
            }
        }
    </style>
</head>
<body class="font-sans text-slate-600 antialiased selection:bg-brand-500 selection:text-white bg-slate-50 overflow-x-hidden">

    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-1/3 w-96 h-96 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <nav class="fixed w-full z-50 transition-all duration-300 top-0" id="navbar">
        <div class="absolute inset-0 glass shadow-sm"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="flex justify-between items-center h-20">
                <img src="/images/light_logo.png" alt="" class="h-20 w-auto object-cover">

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-sm font-medium text-slate-600 hover:text-brand-600 transition-colors">Features</a>
                    <a href="#how-it-works" class="text-sm font-medium text-slate-600 hover:text-brand-600 transition-colors">Methodology</a>
                    <a href="#about" class="text-sm font-medium text-slate-600 hover:text-brand-600 transition-colors">About</a>
                    
                    <div class="w-px h-6 bg-slate-200"></div>
                    
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-brand-600 transition-colors">
                        Log In
                    </a>
                    <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-white transition-all duration-200 bg-slate-900 rounded-full hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-900/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900">
                        Get Started
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>

                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-slate-600 p-2 hover:bg-slate-100 rounded-lg">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div id="mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-xl border-t border-slate-100">
            <div class="px-4 pt-4 pb-6 space-y-3">
                <a href="#features" class="block px-3 py-2 text-base font-medium text-slate-600 rounded-lg hover:bg-slate-50">Features</a>
                <a href="#how-it-works" class="block px-3 py-2 text-base font-medium text-slate-600 rounded-lg hover:bg-slate-50">Methodology</a>
                <a href="#about" class="block px-3 py-2 text-base font-medium text-slate-600 rounded-lg hover:bg-slate-50">About</a>
                <div class="h-px bg-slate-100 my-2"></div>
                <a href="{{ route('dashboard') }}" class="block w-full px-4 py-3 text-center text-white font-semibold bg-brand-600 rounded-lg shadow-lg shadow-brand-500/30">
                    Access Dashboard
                </a>
            </div>
        </div>
    </nav>

    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 border border-brand-100 text-brand-600 text-xs font-bold uppercase tracking-wider mb-6 animate-fade-up">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                    </span>
                    New Generation GDSS
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-extrabold text-slate-900 tracking-tight mb-8 leading-tight animate-fade-up" style="animation-delay: 100ms;">
                    Smarter Locations, <br/>
                    <span class="text-gradient">Better Decisions.</span>
                </h1>
                
                <p class="text-xl text-slate-600 mb-10 leading-relaxed animate-fade-up" style="animation-delay: 200ms;">
                    Leverage the power of <span class="font-semibold text-slate-900">ANP, Weighted Product, and BORDA</span> to align your team and pinpoint the perfect location with scientific precision.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-up" style="animation-delay: 300ms;">
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white transition-all duration-200 bg-brand-600 border border-transparent rounded-full hover:bg-brand-700 hover:shadow-lg hover:shadow-brand-500/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-600">
                        Start Analysis Now
                    </a>
                    <a href="#how-it-works" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-bold text-slate-700 transition-all duration-200 bg-white border border-slate-200 rounded-full hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-200">
                        <i data-lucide="play-circle" class="w-5 h-5 mr-2 text-slate-400"></i>
                        Watch Demo
                    </a>
                </div>
            </div>

            <div class="relative mt-16 mx-auto max-w-5xl animate-fade-up" style="animation-delay: 500ms;">
                <div class="relative rounded-2xl bg-slate-900/5 p-2 ring-1 ring-inset ring-slate-900/10 lg:rounded-3xl lg:p-4 backdrop-blur-sm">
                    <div class="rounded-xl bg-white shadow-2xl ring-1 ring-slate-900/5 overflow-hidden">
                        <div class="flex items-center px-4 py-3 bg-slate-50 border-b border-slate-100">
                            <div class="flex space-x-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <div class="mx-auto text-xs font-medium text-slate-400">silokasi-dashboard.app</div>
                        </div>
                        <div class="aspect-[16/9] bg-slate-50 relative overflow-hidden group">
                             <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
                                <div class="grid grid-cols-3 gap-6 w-3/4 opacity-80">
                                    <div class="col-span-2 h-32 bg-white rounded-lg shadow-sm border border-slate-100 flex items-end p-4 space-x-2">
                                        <div class="w-8 bg-brand-100 h-12 rounded-t"></div>
                                        <div class="w-8 bg-brand-200 h-16 rounded-t"></div>
                                        <div class="w-8 bg-brand-300 h-24 rounded-t"></div>
                                        <div class="w-8 bg-brand-500 h-20 rounded-t"></div>
                                        <div class="w-8 bg-brand-400 h-14 rounded-t"></div>
                                    </div>
                                    <div class="h-32 bg-white rounded-lg shadow-sm border border-slate-100 p-4">
                                        <div class="w-12 h-12 rounded-full border-4 border-accent-500 mb-2"></div>
                                        <div class="h-2 w-16 bg-slate-100 rounded"></div>
                                    </div>
                                    <div class="col-span-3 h-12 bg-white rounded-lg shadow-sm border border-slate-100 flex items-center px-4 justify-between">
                                        <div class="h-2 w-24 bg-slate-200 rounded"></div>
                                        <div class="h-2 w-12 bg-green-100 text-green-600 rounded"></div>
                                    </div>
                                </div>
                             </div>
                             <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/5 transition-colors flex items-center justify-center">
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity transform translate-y-4 group-hover:translate-y-0">
                                    <span class="bg-white px-4 py-2 rounded-lg shadow-lg text-sm font-semibold text-slate-800">View Live Dashboard</span>
                                </div>
                             </div>
                        </div>
                    </div>
                </div>
                <div class="absolute -top-12 -right-12 w-24 h-24 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl rotate-12 blur-xl opacity-20"></div>
                <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full blur-xl opacity-20"></div>
            </div>
        </div>
    </section>

    <section class="py-10 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-slate-100">
                <div class="text-center">
                    <p class="text-3xl font-bold text-slate-900">ANP & WP</p>
                    <p class="text-xs uppercase tracking-wide text-slate-500 mt-1">Methodology</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-slate-900">Multi-User</p>
                    <p class="text-xs uppercase tracking-wide text-slate-500 mt-1">Collaboration</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-slate-900">Real-time</p>
                    <p class="text-xs uppercase tracking-wide text-slate-500 mt-1">Processing</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-slate-900">100%</p>
                    <p class="text-xs uppercase tracking-wide text-slate-500 mt-1">Transparency</p>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 bg-slate-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-base text-brand-600 font-semibold tracking-wide uppercase">Features</h2>
                <p class="mt-2 text-3xl leading-8 font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Advanced Decision Intelligence
                </p>
                <p class="mt-4 text-lg text-slate-600">
                    Combine quantitative data with qualitative expert judgment in one unified platform.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-xl bg-brand-50 flex items-center justify-center mb-6 group-hover:bg-brand-600 transition-colors">
                        <i data-lucide="network" class="w-7 h-7 text-brand-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Analytic Network Process</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Handles complex decisions with dependencies and feedback between criteria, unlike traditional linear models.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-xl bg-accent-50 flex items-center justify-center mb-6 group-hover:bg-accent-500 transition-colors">
                        <i data-lucide="scale" class="w-7 h-7 text-accent-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Weighted Product</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Utilizes geometric mean aggregation to penalize poor performance in any single criterion.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-xl bg-purple-50 flex items-center justify-center mb-6 group-hover:bg-purple-600 transition-colors">
                        <i data-lucide="users" class="w-7 h-7 text-purple-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">BORDA Voting</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Aggregates individual rankings from multiple stakeholders to find the most broadly acceptable solution.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-xl bg-teal-50 flex items-center justify-center mb-6 group-hover:bg-teal-600 transition-colors">
                        <i data-lucide="sliders" class="w-7 h-7 text-teal-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Dynamic Criteria</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Add, edit, or remove criteria on the fly. The system automatically recalculates matrix consistency.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center mb-6 group-hover:bg-blue-600 transition-colors">
                        <i data-lucide="bar-chart-3" class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Visual Analytics</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Interactive charts help you understand not just the "what" but the "why" behind every ranking.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-xl bg-rose-50 flex items-center justify-center mb-6 group-hover:bg-rose-600 transition-colors">
                        <i data-lucide="file-check" class="w-7 h-7 text-rose-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Exportable Reports</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Generate comprehensive PDF reports detailing the entire decision process for auditing purposes.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Streamlined Workflow</h2>
                    <div class="space-y-8">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 font-bold">1</div>
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900">Setup Criteria</h3>
                                <p class="text-slate-600 mt-2">Define the evaluation parameters and alternatives for your location search.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 font-bold">2</div>
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900">Pairwise Comparison</h3>
                                <p class="text-slate-600 mt-2">Decision makers assess the importance of each criterion using Saaty's scale.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 font-bold">3</div>
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900">Automated Synthesis</h3>
                                <p class="text-slate-600 mt-2">The system calculates global weights, checks consistency, and ranks alternatives.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-10">
                         <a href="{{ route('dashboard') }}" class="text-brand-600 font-semibold hover:text-brand-700 flex items-center">
                            Explore the methodology <i data-lucide="arrow-right" class="ml-2 w-4 h-4"></i>
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-tr from-brand-100 to-purple-100 rounded-3xl transform rotate-3"></div>
                    <div class="relative bg-white p-8 rounded-3xl shadow-xl border border-slate-100">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center text-xs text-slate-500">C1</div>
                                    <span class="font-medium text-slate-700">Accessibility</span>
                                </div>
                                <div class="flex gap-1">
                                    <div class="w-16 h-2 bg-brand-500 rounded-full"></div>
                                    <span class="text-xs font-bold text-brand-600">0.45</span>
                                </div>
                            </div>
                             <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center text-xs text-slate-500">C2</div>
                                    <span class="font-medium text-slate-700">Cost</span>
                                </div>
                                <div class="flex gap-1">
                                    <div class="w-10 h-2 bg-brand-300 rounded-full"></div>
                                    <span class="text-xs font-bold text-brand-400">0.25</span>
                                </div>
                            </div>
                             <div class="flex items-center justify-between pb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center text-xs text-slate-500">C3</div>
                                    <span class="font-medium text-slate-700">Infrastructure</span>
                                </div>
                                <div class="flex gap-1">
                                    <div class="w-12 h-2 bg-brand-400 rounded-full"></div>
                                    <span class="text-xs font-bold text-brand-500">0.30</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 bg-slate-50 p-4 rounded-xl text-center">
                            <p class="text-xs text-slate-500 uppercase font-bold mb-1">Consistence Ratio</p>
                            <p class="text-2xl font-bold text-green-500">0.04 <span class="text-xs font-normal text-slate-400">(Valid < 0.1)</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="relative py-24 bg-slate-900 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-1/2 -right-1/2 w-[1000px] h-[1000px] rounded-full bg-gradient-to-b from-brand-600/20 to-transparent blur-3xl"></div>
             <div class="absolute -bottom-1/2 -left-1/2 w-[1000px] h-[1000px] rounded-full bg-gradient-to-t from-purple-600/20 to-transparent blur-3xl"></div>
        </div>
        
        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Ready to optimize your location strategy?</h2>
            <p class="text-slate-300 text-lg mb-10 max-w-2xl mx-auto">
                Join organizations making data-backed decisions today. No more guesswork, just clear, mathematical rankings.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                 <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-brand-600 bg-white rounded-full hover:bg-slate-50 transition-all hover:scale-105 shadow-lg shadow-white/10">
                    Get Started for Free
                    <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </a>
                <a href="#about" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white border border-slate-700 rounded-full hover:bg-slate-800 transition-all">
                    Contact Sales
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-white border-t border-slate-100 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <i data-lucide="map-pin" class="w-6 h-6 text-brand-600"></i>
                        <span class="text-xl font-bold text-slate-800">SILOKASI</span>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Empowering teams to make complex location decisions through advanced mathematical modeling.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-bold text-slate-900 mb-4">Product</h4>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Methodology</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Case Studies</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold text-slate-900 mb-4">Resources</h4>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">API Reference</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Support</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-slate-400 text-sm text-center md:text-left">
                    &copy; {{ date('Y') }} SILOKASI Group Decision Support System. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-slate-400 hover:text-slate-600 transition-colors"><i data-lucide="github" class="w-5 h-5"></i></a>
                    <a href="#" class="text-slate-400 hover:text-slate-600 transition-colors"><i data-lucide="twitter" class="w-5 h-5"></i></a>
                    <a href="#" class="text-slate-400 hover:text-slate-600 transition-colors"><i data-lucide="linkedin" class="w-5 h-5"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Navbar Glass Effect on Scroll
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
        });

        // Mobile Menu Toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>