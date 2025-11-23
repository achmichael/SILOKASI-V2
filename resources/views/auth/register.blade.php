<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - SILOKASI</title>
    
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
        
        .role-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .role-card:hover {
            transform: translateY(-4px);
        }
        
        .role-card.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Floating Shapes Background -->
    <div class="floating-shape" style="width: 300px; height: 300px; top: 10%; left: 5%; animation-delay: 0s;"></div>
    <div class="floating-shape" style="width: 200px; height: 200px; top: 60%; right: 10%; animation-delay: 2s;"></div>
    <div class="floating-shape" style="width: 150px; height: 150px; bottom: 10%; left: 15%; animation-delay: 4s;"></div>

    <div class="min-h-screen flex items-center justify-center px-4 py-12 relative z-10">
        <div class="w-full max-w-2xl">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-xl mb-4">
                    <i data-lucide="map-pin" class="w-10 h-10 text-purple-600"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-2">SILOKASI</h1>
                <p class="text-white/80 text-lg">Create Your Account</p>
            </div>

            <!-- Register Card -->
            <div class="glass-card rounded-3xl p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Join SILOKASI</h2>
                    <p class="text-gray-600">Fill in the information below to get started</p>
                </div>

                <!-- Alert Container -->
                <div id="alert-container"></div>

                <!-- Register Form -->
                <form id="registerForm" class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i data-lucide="user" class="w-4 h-4 inline mr-1"></i>
                            Full Name
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            required
                            class="input-field w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:outline-none text-gray-700"
                            placeholder="John Doe"
                        >
                    </div>

                    <!-- Email -->
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

                    <!-- Role Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i data-lucide="shield" class="w-4 h-4 inline mr-1"></i>
                            Select Your Role
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="role-card border-2 border-gray-200 rounded-xl p-4" data-role="admin">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <i data-lucide="crown" class="w-6 h-6 text-purple-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="font-semibold text-gray-800">Admin</h3>
                                        <p class="text-sm text-gray-600 mt-1">Full system access and management</p>
                                    </div>
                                    <input type="radio" name="role" value="admin" class="mt-1" required>
                                </div>
                            </div>
                            
                            <div class="role-card border-2 border-gray-200 rounded-xl p-4" data-role="decision_maker">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="font-semibold text-gray-800">Decision Maker</h3>
                                        <p class="text-sm text-gray-600 mt-1">Participate in decision-making</p>
                                    </div>
                                    <input type="radio" name="role" value="decision_maker" class="mt-1" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Weight (Only for Decision Maker) -->
                    <div id="weightField" class="hidden">
                        <label for="weight" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i data-lucide="scale" class="w-4 h-4 inline mr-1"></i>
                            Decision Maker Weight
                        </label>
                        <input
                            type="number"
                            id="weight"
                            name="weight"
                            step="0.01"
                            min="0"
                            max="1"
                            class="input-field w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:outline-none text-gray-700"
                            placeholder="e.g., 0.5"
                        >
                        <p class="text-xs text-gray-500 mt-1">Enter a value between 0 and 1 (e.g., 0.3, 0.5, 1.0)</p>
                    </div>

                    <!-- Password -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                    minlength="8"
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

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="lock" class="w-4 h-4 inline mr-1"></i>
                                Confirm Password
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    required
                                    minlength="8"
                                    class="input-field w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:outline-none text-gray-700"
                                    placeholder="••••••••"
                                >
                                <button
                                    type="button"
                                    id="togglePasswordConfirm"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                >
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div>
                        <label class="flex items-start">
                            <input type="checkbox" class="w-4 h-4 mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" required>
                            <span class="ml-2 text-sm text-gray-600">
                                I agree to the <a href="#" class="text-primary-600 hover:text-primary-700 font-semibold">Terms and Conditions</a> and <a href="#" class="text-primary-600 hover:text-primary-700 font-semibold">Privacy Policy</a>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        id="registerButton"
                        class="btn-primary w-full py-3 px-6 rounded-xl text-white font-semibold text-lg shadow-lg"
                    >
                        <span id="registerButtonText">Create Account</span>
                        <span id="registerButtonLoader" class="hidden">
                            <i data-lucide="loader-2" class="w-5 h-5 inline animate-spin"></i>
                            Creating Account...
                        </span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">Already have an account?</span>
                    </div>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <a href="/login" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold">
                        Sign in instead
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

        // Role Card Selection
        const roleCards = document.querySelectorAll('.role-card');
        const weightField = document.getElementById('weightField');
        const weightInput = document.getElementById('weight');

        roleCards.forEach(card => {
            card.addEventListener('click', function() {
                // Remove selected class from all cards
                roleCards.forEach(c => c.classList.remove('selected'));
                
                // Add selected class to clicked card
                this.classList.add('selected');
                
                // Check the radio button
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Show weight field if decision_maker is selected
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

        // Toggle Password Visibility
        function setupPasswordToggle(inputId, buttonId) {
            document.getElementById(buttonId).addEventListener('click', function() {
                const passwordInput = document.getElementById(inputId);
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
        }

        setupPasswordToggle('password', 'togglePassword');
        setupPasswordToggle('password_confirmation', 'togglePasswordConfirm');

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

        // Register Form Submission
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const registerButton = document.getElementById('registerButton');
            const registerButtonText = document.getElementById('registerButtonText');
            const registerButtonLoader = document.getElementById('registerButtonLoader');
            
            // Get form data
            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
                role: document.querySelector('input[name="role"]:checked').value,
            };

            // Add weight if decision_maker
            if (formData.role === 'decision_maker') {
                formData.weight = document.getElementById('weight').value;
            }
            
            // Validate password match
            if (formData.password !== formData.password_confirmation) {
                showAlert('Passwords do not match!');
                return;
            }
            
            // Disable button and show loader
            registerButton.disabled = true;
            registerButtonText.classList.add('hidden');
            registerButtonLoader.classList.remove('hidden');
            lucide.createIcons();
            
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
                    // Store token in localStorage
                    localStorage.setItem('token', data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    showAlert('Registration successful! Redirecting...', 'success');
                    
                    // Redirect to dashboard after 1 second
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1000);
                } else {
                    // Display validation errors
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join(', ');
                        showAlert(errorMessages);
                    } else {
                        showAlert(data.message || 'Registration failed. Please try again.');
                    }
                    
                    // Re-enable button
                    registerButton.disabled = false;
                    registerButtonText.classList.remove('hidden');
                    registerButtonLoader.classList.add('hidden');
                }
            } catch (error) {
                console.error('Registration error:', error);
                showAlert('An error occurred. Please try again.');
                
                // Re-enable button
                registerButton.disabled = false;
                registerButtonText.classList.remove('hidden');
                registerButtonLoader.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
