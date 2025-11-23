<!DOCTYPE html>
<html lang="id" class="h-full bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SILOKASI | Sistem Informasi Lokasi Perumahan</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 
                0 0 0 1px rgba(0, 0, 0, 0.03),
                0 2px 4px rgba(0, 0, 0, 0.05),
                0 12px 24px rgba(0, 0, 0, 0.05);
        }
        
        .building-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%230ea5e9' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .input-field {
            transition: all 0.2s ease;
        }
        
        .input-field:focus {
            transform: translateY(-1px);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -10px rgba(14, 165, 233, 0.5);
        }
        
        .btn-primary:active:not(:disabled) {
            transform: translateY(0);
        }
        
        .brand-logo {
            background: linear-gradient(135deg, #0ea5e9 0%, #7c3aed 100%);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="h-full font-sans">
    <div class="min-h-full flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 relative overflow-hidden building-pattern">
            <!-- Decorative Elements -->
            <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 flex flex-col justify-center px-16 py-12 text-white">
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl mb-6 float-animation">
                        <i data-lucide="building-2" class="w-10 h-10"></i>
                    </div>
                    <h1 class="text-5xl font-display font-bold mb-4 leading-tight">
                        SILOKASI
                    </h1>
                    <p class="text-xl text-blue-100 font-medium mb-2">
                        Sistem Informasi Lokasi Perumahan
                    </p>
                    <p class="text-blue-200 text-lg leading-relaxed">
                        Group Decision Support System untuk Pemilihan Lokasi Optimal
                    </p>
                </div>
                
                <div class="space-y-6 mt-12">
                    <div class="flex items-start space-x-4 bg-white/10 backdrop-blur-sm rounded-xl p-5">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-500/30 rounded-lg flex items-center justify-center">
                            <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Multi-Criteria Analysis</h3>
                            <p class="text-blue-100 text-sm">ANP, Weighted Product, dan Borda Method</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4 bg-white/10 backdrop-blur-sm rounded-xl p-5">
                        <div class="flex-shrink-0 w-10 h-10 bg-purple-500/30 rounded-lg flex items-center justify-center">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Collaborative Decision</h3>
                            <p class="text-blue-100 text-sm">Keputusan kelompok dengan berbagai perspektif</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4 bg-white/10 backdrop-blur-sm rounded-xl p-5">
                        <div class="flex-shrink-0 w-10 h-10 bg-indigo-500/30 rounded-lg flex items-center justify-center">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Optimal Location</h3>
                            <p class="text-blue-100 text-sm">Temukan lokasi terbaik berdasarkan data objektif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 brand-logo rounded-2xl mb-4">
                        <i data-lucide="building-2" class="w-8 h-8 text-white"></i>
                    </div>
                    <h2 class="text-3xl font-display font-bold text-gray-900">SILOKASI</h2>
                </div>
                
                <div class="auth-card rounded-2xl p-8">
                    <div class="mb-8">
                        <h2 class="text-3xl font-display font-bold text-gray-900 mb-2">
                            Selamat Datang Kembali
                        </h2>
                        <p class="text-gray-600">
                            Masuk untuk melanjutkan ke dashboard SILOKASI
                        </p>
                    </div>
                    
                    <!-- Alert Container -->
                    <div id="alert-container" class="mb-6"></div>
                    
                    <!-- Login Form -->
                    <form id="loginForm" class="space-y-6">
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    required
                                    autocomplete="email"
                                    class="input-field block w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-brand-500 text-gray-900 placeholder-gray-400"
                                    placeholder="nama@email.com"
                                >
                            </div>
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    class="input-field block w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-brand-500 text-gray-900 placeholder-gray-400"
                                    placeholder="••••••••"
                                >
                                <button
                                    type="button"
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600"
                                >
                                    <i data-lucide="eye" class="h-5 w-5"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                                >
                                <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                            </label>
                            <a href="#" class="text-sm font-semibold text-brand-600 hover:text-brand-700">
                                Lupa password?
                            </a>
                        </div>
                        
                        <button
                            type="submit"
                            id="loginButton"
                            class="btn-primary w-full py-3.5 px-4 rounded-xl text-white font-semibold text-base shadow-lg shadow-brand-500/30"
                        >
                            <span id="loginButtonText" class="flex items-center justify-center">
                                <i data-lucide="log-in" class="w-5 h-5 mr-2"></i>
                                Masuk ke Dashboard
                            </span>
                            <span id="loginButtonLoader" class="hidden flex items-center justify-center">
                                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </form>
                    
                    <div class="mt-8">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-gray-500">Belum punya akun?</span>
                            </div>
                        </div>
                        
                        <div class="mt-6 text-center">
                            <a href="/register" class="inline-flex items-center justify-center w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-700 font-semibold hover:border-brand-500 hover:text-brand-600 transition-colors">
                                <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                                Daftar Akun Baru
                            </a>
                        </div>
                    </div>
                </div>
                
                <p class="mt-8 text-center text-sm text-gray-500">
                    &copy; 2024 SILOKASI. Sistem Informasi Lokasi Perumahan.
                </p>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Toggle Password Visibility
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
            const alertClasses = {
                error: 'bg-red-50 border-red-200 text-red-800',
                success: 'bg-green-50 border-green-200 text-green-800',
                warning: 'bg-yellow-50 border-yellow-200 text-yellow-800'
            };
            const iconNames = {
                error: 'alert-circle',
                success: 'check-circle',
                warning: 'alert-triangle'
            };
            
            alertContainer.innerHTML = `
                <div class="rounded-xl border-2 ${alertClasses[type]} p-4">
                    <div class="flex items-center">
                        <i data-lucide="${iconNames[type]}" class="w-5 h-5 mr-3 flex-shrink-0"></i>
                        <span class="font-medium">${message}</span>
                    </div>
                </div>
            `;
            lucide.createIcons();
            
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }
        
        // Login Form Submission
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const loginButton = document.getElementById('loginButton');
            const loginButtonText = document.getElementById('loginButtonText');
            const loginButtonLoader = document.getElementById('loginButtonLoader');
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Disable button and show loader
            loginButton.disabled = true;
            loginButtonText.classList.add('hidden');
            loginButtonLoader.classList.remove('hidden');
            
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
                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    showAlert('Login berhasil! Mengalihkan ke dashboard...', 'success');
                    
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1000);
                } else {
                    showAlert(data.message || 'Login gagal. Periksa email dan password Anda.');
                    
                    loginButton.disabled = false;
                    loginButtonText.classList.remove('hidden');
                    loginButtonLoader.classList.add('hidden');
                }
            } catch (error) {
                console.error('Login error:', error);
                showAlert('Terjadi kesalahan. Silakan coba lagi.');
                
                loginButton.disabled = false;
                loginButtonText.classList.remove('hidden');
                loginButtonLoader.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
