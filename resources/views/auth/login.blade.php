<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f8f9fa]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SILOKASI</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        brand: {
                            black: '#0f172a',
                            gray: '#f3f4f6',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Minimalist Input Style */
        .input-minimal {
            background-color: #f3f4f6; /* Gray-100 */
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }
        .input-minimal:focus {
            background-color: #ffffff;
            border-color: #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            outline: none;
        }
        
        /* Hide Scrollbar but keep functionality */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="h-screen flex items-center">

    <div class="w-full h-full shadow-2xl overflow-hidden flex flex-col lg:flex-row border border-slate-100">
        <div class="relative w-full lg:w-[45%] flex flex-col justify-between p-10 lg:p-14 overflow-hidden order-first">
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1761850648640-2ee5870ee883?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDF8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwxfHx8ZW58MHx8fHx8" 
                     alt="Abstract Fluid Background" 
                     class="w-full h-full object-cover opacity-90 mix-blend-screen hover:scale-105 transition-transform duration-[20s]">
                <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/80"></div>
            </div>

            <div class="relative z-10">
                <div class="flex items-center space-x-3 text-white/90">
                    <span class="text-xs font-bold tracking-[0.2em] uppercase">Visi Kami</span>
                    <div class="h-px w-12 bg-white/50"></div>
                </div>
            </div>

            <div class="relative z-10 mt-auto">
                <h1 class="font-serif text-5xl lg:text-6xl text-white leading-[1.1] mb-6">
                    Temukan <br>
                    <span class="italic">Lokasi Impian</span><br>
                    Anda
                </h1>
                <p class="text-white/70 text-sm font-light max-w-sm leading-relaxed">
                    SILOKASI membantu Anda mendapatkan keputusan lokasi terbaik dengan metode ilmiah dan data akurat. Percayakan prosesnya pada kami.
                </p>
                
                <div class="mt-8 space-y-3 text-white/80 text-sm font-light">
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        <span>Metode ANP & Weighted Product</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        <span>Analisis Multi-Kriteria Akurat</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-[55%] bg-white flex flex-col justify-center items-center p-8 lg:p-20 relative overflow-y-auto max-h-[100vh] no-scrollbar">
            
            <div class="w-full max-w-lg space-y-8">
                
                <div class="lg:hidden flex items-center justify-center mb-6">
                    <img src="/images/light_logo.png" alt="SILOKASI Logo" class="h-12 w-auto object-cover mr-2">
                    <span class="font-serif font-bold text-xl">SILOKASI</span>
                </div>

                <div class="text-center lg:text-left space-y-2">
                    <h2 class="text-3xl lg:text-4xl font-serif text-slate-900">Welcome Back</h2>
                    <p class="text-slate-400 text-sm">Masukan detail akun anda untuk mengakses dashboard</p>
                </div>

                <div id="alert-container"></div>

                <form id="loginForm" class="space-y-6">
                    
                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-semibold text-slate-600 uppercase tracking-wide ml-1">Email</label>
                        <input type="email" id="email" name="email" required 
                            class="input-minimal w-full px-5 py-4 rounded-xl text-slate-900 placeholder-slate-400 text-sm"
                            placeholder="Masukan email anda">
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="block text-xs font-semibold text-slate-600 uppercase tracking-wide ml-1">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required 
                                class="input-minimal w-full px-5 py-4 rounded-xl text-slate-900 placeholder-slate-400 text-sm pr-12"
                                placeholder="Masukan password">
                            <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm py-2">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-black focus:ring-black/20">
                            <span class="ml-2 text-slate-500 group-hover:text-slate-800 transition-colors">Ingat saya</span>
                        </label>
                        <a href="#" class="font-medium text-slate-900 hover:underline">Lupa Password?</a>
                    </div>

                    <button type="submit" id="loginButton" 
                        class="w-full bg-black hover:bg-slate-800 text-white font-medium py-4 rounded-xl transition-all duration-300 shadow-lg shadow-black/20 hover:shadow-black/30 transform hover:-translate-y-1">
                        <span id="loginButtonText">Sign In</span>
                        <span id="loginButtonLoader" class="hidden items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>

                    <button type="button" class="w-full bg-white border border-slate-200 text-slate-700 font-medium py-4 rounded-xl transition-all duration-300 hover:bg-slate-50 hover:border-slate-300 flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Sign In with Google
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-slate-500 text-sm">
                        Belum punya akun? 
                        <a href="/register" class="text-black font-bold hover:underline">Sign Up</a>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        
        // Toggle Password
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        });
        
        // Show Alert Function
        function showAlert(message, type = 'error') {
            const alertContainer = document.getElementById('alert-container');
            const colors = type === 'success' 
                ? 'bg-emerald-50 text-emerald-600 border-emerald-200' 
                : 'bg-red-50 text-red-600 border-red-200';
                
            alertContainer.innerHTML = `
                <div class="p-4 rounded-xl text-sm border ${colors} flex items-center animate-pulse mb-6">
                    <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5 mr-3"></i>
                    ${message}
                </div>
            `;
            lucide.createIcons();
            
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        // Form Submit Handler
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('loginButton');
            const text = document.getElementById('loginButtonText');
            const loader = document.getElementById('loginButtonLoader');
            
            // UI Loading State
            btn.disabled = true;
            text.classList.add('hidden');
            loader.classList.remove('hidden');
            loader.classList.add('flex');

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (data.success) {
                    localStorage.setItem('token', data.access_token);
                    showAlert('Login berhasil! Mengalihkan...', 'success');
                    setTimeout(() => window.location.href = '/dashboard', 1000);
                } else {
                    showAlert(data.message || 'Login gagal. Periksa kredensial Anda.');
                    btn.disabled = false;
                    text.classList.remove('hidden');
                    loader.classList.add('hidden');
                    loader.classList.remove('flex');
                }
            } catch (error) {
                showAlert('Terjadi kesalahan sistem.');
                btn.disabled = false;
                text.classList.remove('hidden');
                loader.classList.add('hidden');
                loader.classList.remove('flex');
            }
        });
    </script>
</body>
</html>