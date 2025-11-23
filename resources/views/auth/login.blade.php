<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SILOKASI</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    
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
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .input-field {
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .alert {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Floating Shapes Background -->
    <div class="floating-shape" style="width: 300px; height: 300px; top: 10%; left: 5%; animation-delay: 0s;"></div>
    <div class="floating-shape" style="width: 200px; height: 200px; top: 60%; right: 10%; animation-delay: 2s;"></div>
    <div class="floating-shape" style="width: 150px; height: 150px; bottom: 10%; left: 15%; animation-delay: 4s;"></div>

    <div class="min-h-screen flex items-center justify-center px-4 py-12 relative z-10">
        <div class="w-full max-w-md">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-xl mb-4">
                    <i data-lucide="map-pin" class="w-10 h-10 text-purple-600"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-2">SILOKASI</h1>
                <p class="text-white/80 text-lg">Sistem Informasi Lokasi</p>
            </div>

            <!-- Login Card -->
            <div class="glass-card rounded-3xl p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back!</h2>
                    <p class="text-gray-600">Sign in to continue to your account</p>
                </div>

                <!-- Alert Container -->
                <div id="alert-container"></div>

                <!-- Login Form -->
                <form id="loginForm" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i data-lucide="mail" class="w-4 h-4 inline mr-1"></i>
                            Email Address
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            class="input-field w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:outline-none text-gray-700"
                            placeholder="your@email.com"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i data-lucide="lock" class="w-4 h-4 inline mr-1"></i>
                            Password
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="input-field w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:outline-none text-gray-700"
                                placeholder="••••••••"
                            >
                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            >
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-sm font-semibold text-primary-600 hover:text-primary-700">
                            Forgot Password?
                        </a>
                    </div>

                    <button
                        type="submit"
                        id="loginButton"
                        class="btn-primary w-full py-3 px-6 rounded-xl text-white font-semibold text-lg shadow-lg"
                    >
                        <span id="loginButtonText">Sign In</span>
                        <span id="loginButtonLoader" class="hidden">
                            <i data-lucide="loader-2" class="w-5 h-5 inline animate-spin"></i>
                            Signing In...
                        </span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">New to SILOKASI?</span>
                    </div>
                </div>

                <!-- Register Link -->
                <div class="text-center">
                    <a href="/register" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold">
                        Create an account
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-white/60 text-sm">
                <p>&copy; 2024 SILOKASI. All rights reserved.</p>
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
            const alertClass = type === 'error' ? 'bg-red-50 text-red-800 border-red-200' : 'bg-green-50 text-green-800 border-green-200';
            const iconName = type === 'error' ? 'alert-circle' : 'check-circle';
            
            alertContainer.innerHTML = `
                <div class="alert mb-6 p-4 rounded-xl border-2 ${alertClass}">
                    <div class="flex items-center">
                        <i data-lucide="${iconName}" class="w-5 h-5 mr-2"></i>
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
            
            // Get form data
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Disable button and show loader
            loginButton.disabled = true;
            loginButtonText.classList.add('hidden');
            loginButtonLoader.classList.remove('hidden');
            lucide.createIcons();
            
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
                    // Store token in localStorage
                    localStorage.setItem('token', data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    showAlert('Login successful! Redirecting...', 'success');
                    
                    // Redirect to dashboard after 1 second
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1000);
                } else {
                    showAlert(data.message || 'Login failed. Please try again.');
                    
                    // Re-enable button
                    loginButton.disabled = false;
                    loginButtonText.classList.remove('hidden');
                    loginButtonLoader.classList.add('hidden');
                }
            } catch (error) {
                console.error('Login error:', error);
                showAlert('An error occurred. Please try again.');
                
                // Re-enable button
                loginButton.disabled = false;
                loginButtonText.classList.remove('hidden');
                loginButtonLoader.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
