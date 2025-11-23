<!DOCTYPE html>
<html lang="id" class="h-full bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - SILOKASI | Sistem Informasi Lokasi Perumahan</title>
    
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
        
        .role-card {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 2px solid #e5e7eb;
        }
        
        .role-card:hover {
            transform: translateY(-4px);
            border-color: #bfdbfe;
            box-shadow: 0 8px 16px -4px rgba(14, 165, 233, 0.2);
        }
        
        .role-card.selected {
            border-color: #0ea5e9;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.05) 0%, rgba(124, 58, 237, 0.05) 100%);
            box-shadow: 0 4px 12px -2px rgba(14, 165, 233, 0.3);
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
    <div class="min-h-full py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 brand-logo rounded-2xl mb-4 float-animation">
                    <i data-lucide="building-2" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-4xl font-display font-bold text-gray-900 mb-2">SILOKASI</h1>
                <p class="text-lg text-gray-600">Sistem Informasi Lokasi Perumahan</p>
            </div>
            
            <div class="grid lg:grid-cols-5 gap-8 items-start">
                <!-- Info Sidebar -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="auth-card rounded-2xl p-6">
                        <h2 class="text-2xl font-display font-bold text-gray-900 mb-4">
                            Bergabung dengan SILOKASI
                        </h2>
                        <p class="text-gray-600 mb-6">
                            Daftarkan diri Anda untuk mengakses sistem pengambilan keputusan lokasi perumahan yang canggih dan kolaboratif.
                        </p>
                        
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mt-0.5">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm">Akses Aman</h4>
                                    <p class="text-xs text-gray-600 mt-0.5">Data Anda dilindungi dengan enkripsi tingkat tinggi</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mt-0.5">
                                    <i data-lucide="users-2" class="w-4 h-4 text-purple-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm">Kolaborasi Tim</h4>
                                    <p class="text-xs text-gray-600 mt-0.5">Bekerja sama dalam pengambilan keputusan</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mt-0.5">
                                    <i data-lucide="chart-bar" class="w-4 h-4 text-indigo-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm">Analisis Mendalam</h4>
                                    <p class="text-xs text-gray-600 mt-0.5">Gunakan metode ANP, WP, dan Borda untuk hasil optimal</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="auth-card rounded-2xl p-6">
                        <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i data-lucide="info" class="w-5 h-5 mr-2 text-brand-500"></i>
                            Pilihan Peran
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="font-semibold text-gray-900">Administrator:</span>
                                <p class="text-gray-600 mt-1">Kelola seluruh sistem, kriteria, alternatif, dan hasil keputusan.</p>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-900">Decision Maker:</span>
                                <p class="text-gray-600 mt-1">Berpartisipasi dalam proses pengambilan keputusan dengan bobot yang ditentukan.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Registration Form -->
                <div class="lg:col-span-3">
                    <div class="auth-card rounded-2xl p-8">
                        <div class="mb-6">
                            <h2 class="text-2xl font-display font-bold text-gray-900 mb-2">
                                Buat Akun Baru
                            </h2>
                            <p class="text-gray-600">
                                Lengkapi formulir di bawah untuk mendaftar
                            </p>
                        </div>
                        
                        <!-- Alert Container -->
                        <div id="alert-container" class="mb-6"></div>
                        
                        <!-- Register Form -->
                        <form id="registerForm" class="space-y-5">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                                    </div>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        required
                                        class="input-field block w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-brand-500 text-gray-900"
                                        placeholder="Masukkan nama lengkap Anda"
                                    >
                                </div>
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
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
                                        class="input-field block w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-brand-500 text-gray-900"
                                        placeholder="nama@email.com"
                                    >
                                </div>
                            </div>
                            
                            <!-- Role Selection -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Pilih Peran <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="role-card rounded-xl p-4" data-role="admin">
                                        <div class="flex flex-col h-full">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-red-500 rounded-lg flex items-center justify-center">
                                                    <i data-lucide="shield" class="w-5 h-5 text-white"></i>
                                                </div>
                                                <input type="radio" name="role" value="admin" class="h-4 w-4 text-brand-600 focus:ring-brand-500" required>
                                            </div>
                                            <h3 class="font-bold text-gray-900 text-base mb-1">Administrator</h3>
                                            <p class="text-xs text-gray-600">Kelola sistem secara penuh</p>
                                        </div>
                                    </div>
                                    
                                    <div class="role-card rounded-xl p-4" data-role="decision_maker">
                                        <div class="flex flex-col h-full">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center">
                                                    <i data-lucide="users" class="w-5 h-5 text-white"></i>
                                                </div>
                                                <input type="radio" name="role" value="decision_maker" class="h-4 w-4 text-brand-600 focus:ring-brand-500" required>
                                            </div>
                                            <h3 class="font-bold text-gray-900 text-base mb-1">Decision Maker</h3>
                                            <p class="text-xs text-gray-600">Berpartisipasi dalam keputusan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Weight (Only for Decision Maker) -->
                            <div id="weightField" class="hidden">
                                <label for="weight" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Bobot Decision Maker <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i data-lucide="scale" class="h-5 w-5 text-gray-400"></i>
                                    </div>
                                    <input
                                        type="number"
                                        id="weight"
                                        name="weight"
                                        step="0.01"
                                        min="0"
                                        max="1"
                                        class="input-field block w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-brand-500 text-gray-900"
                                        placeholder="Contoh: 0.5"
                                    >
                                </div>
                                <p class="text-xs text-gray-500 mt-2 flex items-start">
                                    <i data-lucide="info" class="w-3 h-3 mr-1 mt-0.5 flex-shrink-0"></i>
                                    Masukkan nilai antara 0 dan 1. Semakin besar bobot, semakin besar pengaruh dalam keputusan.
                                </p>
                            </div>
                            
                            <!-- Password -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Password <span class="text-red-500">*</span>
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
                                            minlength="8"
                                            class="input-field block w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-brand-500 text-gray-900"
                                            placeholder="Min. 8 karakter"
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
                                
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Konfirmasi Password <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                                        </div>
                                        <input
                                            type="password"
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            required
                                            minlength="8"
                                            class="input-field block w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-brand-500 text-gray-900"
                                            placeholder="Ulangi password"
                                        >
                                        <button
                                            type="button"
                                            id="togglePasswordConfirm"
                                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600"
                                        >
                                            <i data-lucide="eye" class="h-5 w-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Terms -->
                            <div class="flex items-start">
                                <input 
                                    type="checkbox" 
                                    id="terms"
                                    required
                                    class="h-4 w-4 mt-1 rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                                >
                                <label for="terms" class="ml-3 text-sm text-gray-600">
                                    Saya menyetujui <a href="#" class="text-brand-600 hover:text-brand-700 font-semibold">Syarat dan Ketentuan</a> serta <a href="#" class="text-brand-600 hover:text-brand-700 font-semibold">Kebijakan Privasi</a> SILOKASI
                                </label>
                            </div>
                            
                            <!-- Submit Button -->
                            <button
                                type="submit"
                                id="registerButton"
                                class="btn-primary w-full py-3.5 px-4 rounded-xl text-white font-semibold text-base shadow-lg shadow-brand-500/30"
                            >
                                <span id="registerButtonText" class="flex items-center justify-center">
                                    <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                                    Daftar Sekarang
                                </span>
                                <span id="registerButtonLoader" class="hidden flex items-center justify-center">
                                    <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Mendaftarkan...
                                </span>
                            </button>
                        </form>
                        
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-center text-sm text-gray-600">
                                Sudah punya akun?
                                <a href="/login" class="font-semibold text-brand-600 hover:text-brand-700 ml-1">
                                    Masuk di sini
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <p class="mt-8 text-center text-sm text-gray-500">
                &copy; 2024 SILOKASI. Sistem Informasi Lokasi Perumahan.
            </p>
        </div>
    </div>

    <script>
        lucide.createIcons();
        
        // Role Card Selection
        const roleCards = document.querySelectorAll('.role-card');
        const weightField = document.getElementById('weightField');
        const weightInput = document.getElementById('weight');
        
        roleCards.forEach(card => {
            card.addEventListener('click', function() {
                roleCards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                const role = this.dataset.role;
                if (role === 'decision_maker') {
                    weightField.classList.remove('hidden');
                    weightInput.required = true;
                } else {
                    weightField.classList.add('hidden');
                    weightInput.required = false;
                    weightInput.value = '';
                }
            });
        });
        
        // Password Toggle
        function setupPasswordToggle(inputId, buttonId) {
            document.getElementById(buttonId).addEventListener('click', function() {
                const input = document.getElementById(inputId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.setAttribute('data-lucide', 'eye-off');
                } else {
                    input.type = 'password';
                    icon.setAttribute('data-lucide', 'eye');
                }
                lucide.createIcons();
            });
        }
        
        setupPasswordToggle('password', 'togglePassword');
        setupPasswordToggle('password_confirmation', 'togglePasswordConfirm');
        
        // Alert
        function showAlert(message, type = 'error') {
            const container = document.getElementById('alert-container');
            const classes = {
                error: 'bg-red-50 border-red-200 text-red-800',
                success: 'bg-green-50 border-green-200 text-green-800'
            };
            const icons = { error: 'alert-circle', success: 'check-circle' };
            
            container.innerHTML = `
                <div class="rounded-xl border-2 ${classes[type]} p-4">
                    <div class="flex items-center">
                        <i data-lucide="${icons[type]}" class="w-5 h-5 mr-3"></i>
                        <span class="font-medium">${message}</span>
                    </div>
                </div>
            `;
            lucide.createIcons();
            setTimeout(() => container.innerHTML = '', 5000);
        }
        
        // Form Submit
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('registerButton');
            const btnText = document.getElementById('registerButtonText');
            const btnLoader = document.getElementById('registerButtonLoader');
            
            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
                role: document.querySelector('input[name="role"]:checked').value,
            };
            
            if (formData.role === 'decision_maker') {
                formData.weight = document.getElementById('weight').value;
            }
            
            if (formData.password !== formData.password_confirmation) {
                showAlert('Password tidak cocok!');
                return;
            }
            
            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');
            
            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    localStorage.setItem('token', data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    showAlert('Pendaftaran berhasil! Mengalihkan ke dashboard...', 'success');
                    setTimeout(() => window.location.href = '/dashboard', 1000);
                } else {
                    const errorMsg = data.errors 
                        ? Object.values(data.errors).flat().join(', ')
                        : data.message || 'Pendaftaran gagal.';
                    showAlert(errorMsg);
                    
                    btn.disabled = false;
                    btnText.classList.remove('hidden');
                    btnLoader.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan. Silakan coba lagi.');
                
                btn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoader.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
