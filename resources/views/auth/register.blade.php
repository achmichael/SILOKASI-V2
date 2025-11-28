<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f8f9fa]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - SILOKASI</title>
    
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
<body class="h-screen flex items-center justify-center">
    <div class="h-full w-full bg-white shadow-2xl overflow-hidden flex flex-col lg:flex-row border border-slate-100">
        <div class="relative w-full lg:w-[45%] bg-black flex flex-col justify-between p-10 lg:p-14 overflow-hidden order-first">
            
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1587325889132-2c9e393e7d70?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8dG93bnxlbnwwfHwwfHx8MA%3D%3D" 
                     alt="Abstract Background" 
                     class="w-full h-full object-cover opacity-90 mix-blend-screen hover:scale-105 transition-transform duration-[20s]">
                <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/80"></div>
            </div>

            <div class="relative z-10">
                <div class="flex items-center space-x-3 text-white/90">
                    <span class="text-xs font-bold tracking-[0.2em] uppercase">Bergabung</span>
                    <div class="h-px w-12 bg-white/50"></div>
                </div>
            </div>

            <div class="relative z-10 mt-auto">
                <h1 class="font-serif text-5xl lg:text-6xl text-white leading-[1.1] mb-6">
                    Mulai <br>
                    <span class="italic">Keputusan Cerdas</span><br>
                    Anda Disini
                </h1>
                <p class="text-white/70 text-sm font-light max-w-sm leading-relaxed">
                    Daftarkan diri Anda untuk mengakses sistem pengambilan keputusan lokasi perumahan yang canggih.
                </p>
                
                <div class="mt-8 space-y-3 text-white/80 text-sm font-light">
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        <span>Akses Penuh Dashboard</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        <span>Analisis Lokasi Real-time</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-[55%] bg-white flex flex-col justify-center items-center p-8 lg:p-20 relative overflow-y-auto max-h-[100vh] no-scrollbar">
            
            <div class="w-full max-w-lg space-y-8">
                
                <div class="lg:hidden flex items-center justify-center mb-6">
                    <i data-lucide="map-pin" class="w-6 h-6 text-slate-900 mr-2"></i>
                    <span class="font-serif font-bold text-xl">SILOKASI</span>
                </div>

                <div class="text-center lg:text-left space-y-2">
                    <h2 class="text-3xl lg:text-4xl font-serif text-slate-900">Buat Akun Baru</h2>
                    <p class="text-slate-400 text-sm">Lengkapi data diri anda untuk memulai sesi</p>
                </div>

                <div id="alert-container"></div>

                <form id="registerForm" class="space-y-6">
                    
                    <div class="space-y-1">
                        <label for="name" class="block text-xs font-semibold text-slate-600 uppercase tracking-wide ml-1">Nama Lengkap</label>
                        <input type="text" id="name" name="name" required 
                            class="input-minimal w-full px-5 py-4 rounded-xl text-slate-900 placeholder-slate-400 text-sm"
                            placeholder="Masukan nama lengkap">
                    </div>

                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-semibold text-slate-600 uppercase tracking-wide ml-1">Email Address</label>
                        <input type="email" id="email" name="email" required 
                            class="input-minimal w-full px-5 py-4 rounded-xl text-slate-900 placeholder-slate-400 text-sm"
                            placeholder="nama@email.com">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide ml-1">Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required minlength="8"
                                    class="input-minimal w-full px-5 py-4 rounded-xl text-slate-900 placeholder-slate-400 text-sm pr-10"
                                    placeholder="••••••••">
                                <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide ml-1">Ulangi Password</label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8"
                                    class="input-minimal w-full px-5 py-4 rounded-xl text-slate-900 placeholder-slate-400 text-sm pr-10"
                                    placeholder="••••••••">
                                <button type="button" id="togglePasswordConfirm" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <label class="flex items-start space-x-3 pt-2 cursor-pointer group">
                        <input type="checkbox" id="terms" required class="mt-1 w-4 h-4 rounded border-slate-300 text-black focus:ring-black/20 accent-black">
                        <span class="text-xs text-slate-500 leading-relaxed group-hover:text-slate-700 transition-colors">
                            Saya menyetujui <a href="#" class="font-bold text-slate-900 hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="font-bold text-slate-900 hover:underline">Kebijakan Privasi</a> SILOKASI.
                        </span>
                    </label>

                    <button type="submit" id="registerButton" 
                        class="w-full bg-black hover:bg-slate-800 text-white font-medium py-4 rounded-xl transition-all duration-300 shadow-lg shadow-black/20 hover:shadow-black/30 transform hover:-translate-y-1">
                        <span id="registerButtonText">Buat Akun</span>
                        <span id="registerButtonLoader" class="hidden flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mendaftarkan...
                        </span>
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-slate-500 text-sm">
                        Sudah punya akun? 
                        <a href="/login" class="text-black font-bold hover:underline">Sign In</a>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        
        // --- 1. Password Toggle Logic ---
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
        
        // --- 2. Alert Logic ---
        function showAlert(message, type = 'error') {
            const container = document.getElementById('alert-container');
            const colors = type === 'success' 
                ? 'bg-emerald-50 text-emerald-600 border-emerald-200' 
                : 'bg-red-50 text-red-600 border-red-200';
                
            container.innerHTML = `
                <div class="p-4 rounded-xl text-sm border ${colors} flex items-center animate-pulse mb-6">
                    <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5 mr-3"></i>
                    ${message}
                </div>
            `;
            lucide.createIcons();
            
            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        }

        // --- 3. Form Submission Logic ---
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('registerButton');
            const btnText = document.getElementById('registerButtonText');
            const btnLoader = document.getElementById('registerButtonLoader');
            
            // Gather Data
            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
                // Default Role (Jika backend butuh, sesuaikan nilai ini, misal: 'user' atau 'admin')
                role: 'decision_maker' 
            };
            
            // Client-side Validation
            if (formData.password !== formData.password_confirmation) {
                showAlert('Password tidak cocok, silakan periksa kembali.');
                return;
            }
            
            // UI Loading State
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
                    
                    showAlert('Pendaftaran berhasil! Mengalihkan...', 'success');
                    setTimeout(() => window.location.href = '/dashboard', 1000);
                } else {
                    const errorMsg = data.errors 
                        ? Object.values(data.errors).flat().join(', ')
                        : data.message || 'Pendaftaran gagal.';
                    showAlert(errorMsg);
                    
                    // Reset UI
                    btn.disabled = false;
                    btnText.classList.remove('hidden');
                    btnLoader.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan koneksi. Silakan coba lagi.');
                
                // Reset UI
                btn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoader.classList.add('hidden');
            }
        });
    </script>
</body>
</html>