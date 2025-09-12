<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun Baru - SoleStyle</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .logo-text {
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .glass-effect {
            backdrop-filter: blur(20px);
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }
        
        .input-field {
            background: rgba(51, 65, 85, 0.5);
            border: 1px solid rgba(100, 116, 139, 1);
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
            background: rgba(51, 65, 85, 0.8);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #7c3aed, #db2777);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
        }
        
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        
        .floating-shapes::before,
        .floating-shapes::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.1;
        }
        
        .floating-shapes::before {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            top: 10%;
            right: 10%;
        }
        
        .floating-shapes::after {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #06b6d4, #8b5cf6);
            bottom: 20%;
            left: 15%;
        }
        
        .error-message {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .password-strength {
            transition: all 0.3s ease;
        }
        
        .strength-weak { background-color: #ef4444; }
        .strength-medium { background-color: #f59e0b; }
        .strength-strong { background-color: #10b981; }
    </style>
</head>
<body class="bg-slate-900 text-white min-h-screen">
    <div class="floating-shapes"></div>
    
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="w-full max-w-md py-10 px-6 glass-effect rounded-xl shadow-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shoe-prints text-white text-lg"></i>
                    </div>
                    <span class="logo-text text-2xl font-bold">SoleStyle</span>
                </div>
                <h1 class="text-3xl font-bold text-white">Daftar Akun</h1>
                <p class="text-slate-400 mt-2">Buat akun baru untuk mulai berbelanja.</p>
            </div>

            <!-- Error Message -->
            <div id="error-message" class="hidden bg-red-500/20 text-red-300 p-4 rounded-lg mb-4 text-sm border border-red-500/30 error-message" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span id="error-text"></span>
                </div>
            </div>
            
            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-6" id="registerForm">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-user mr-2 text-purple-400"></i>Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" required autocomplete="name" autofocus
                           class="input-field mt-1 block w-full px-4 py-3 rounded-lg text-white focus:outline-none transition-all"
                           placeholder="Masukkan nama lengkap"
                           value="{{ old('name') }}">
                    <div id="name-error" class="hidden mt-2 text-sm text-red-400"></div>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-envelope mr-2 text-purple-400"></i>Alamat Email
                    </label>
                    <input type="email" id="email" name="email" required autocomplete="email"
                           class="input-field mt-1 block w-full px-4 py-3 rounded-lg text-white focus:outline-none transition-all"
                           placeholder="nama@email.com"
                           value="{{ old('email') }}">
                    <div id="email-error" class="hidden mt-2 text-sm text-red-400"></div>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-user-tag mr-2 text-purple-400"></i>Role
                    </label>
                    <select id="role" name="role" required
                        class="input-field mt-1 block w-full px-4 py-3 rounded-lg text-white focus:outline-none transition-all">
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User (Pelanggan)</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Pengelola)</option>
                    </select>
                    <div id="role-error" class="hidden mt-2 text-sm text-red-400"></div>
                    <p class="mt-1 text-xs text-slate-500">Pilih User untuk berbelanja, Admin untuk mengelola toko</p>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-lock mr-2 text-purple-400"></i>Kata Sandi
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                               class="input-field mt-1 block w-full px-4 py-3 pr-12 rounded-lg text-white focus:outline-none transition-all"
                               placeholder="Minimal 8 karakter">
                        <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-purple-400 transition-colors">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="flex space-x-1">
                            <div id="strength-bar-1" class="h-1 flex-1 bg-slate-600 rounded password-strength"></div>
                            <div id="strength-bar-2" class="h-1 flex-1 bg-slate-600 rounded password-strength"></div>
                            <div id="strength-bar-3" class="h-1 flex-1 bg-slate-600 rounded password-strength"></div>
                            <div id="strength-bar-4" class="h-1 flex-1 bg-slate-600 rounded password-strength"></div>
                        </div>
                        <p id="strength-text" class="text-xs text-slate-500 mt-1">Kekuatan kata sandi</p>
                    </div>
                    <div id="password-error" class="hidden mt-2 text-sm text-red-400"></div>
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-check-double mr-2 text-purple-400"></i>Konfirmasi Kata Sandi
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                               class="input-field mt-1 block w-full px-4 py-3 pr-12 rounded-lg text-white focus:outline-none transition-all"
                               placeholder="Ulangi kata sandi">
                        <div id="match-indicator" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <i id="match-icon" class="fas fa-times text-red-400 hidden"></i>
                        </div>
                    </div>
                    <div id="password-confirmation-error" class="hidden mt-2 text-sm text-red-400"></div>
                </div>

                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-slate-600 rounded bg-slate-700">
                    <label for="terms" class="ml-2 block text-sm text-slate-400">
                        Saya setuju dengan <a href="#" class="text-purple-400 hover:text-purple-300">Syarat dan Ketentuan</a> serta <a href="#" class="text-purple-400 hover:text-purple-300">Kebijakan Privasi</a>
                    </label>
                </div>

                <div>
                    <button type="submit" class="btn-primary w-full py-3 px-4 border border-transparent rounded-lg text-white font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Akun
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-slate-400">
                Sudah punya akun? 
                <a href="{{ route('login.show') }}" class="font-medium text-purple-400 hover:text-purple-300 transition-colors">
                    Masuk sekarang
                </a>
            </div>

            <!-- Alternative Actions -->
            <div class="mt-6 text-center">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-slate-900 text-slate-400">atau</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ url('/') }}" class="w-full flex items-center justify-center px-4 py-2 border border-slate-600 rounded-lg text-slate-300 hover:bg-slate-700/50 transition-colors">
                        <i class="fas fa-home mr-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBars = ['strength-bar-1', 'strength-bar-2', 'strength-bar-3', 'strength-bar-4'];
            const strengthText = document.getElementById('strength-text');
            
            // Reset bars
            strengthBars.forEach(bar => {
                document.getElementById(bar).className = 'h-1 flex-1 bg-slate-600 rounded password-strength';
            });
            
            if (password.length === 0) {
                strengthText.textContent = 'Kekuatan kata sandi';
                strengthText.className = 'text-xs text-slate-500 mt-1';
                return;
            }
            
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Character variety checks
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z\d]/.test(password)) strength++;
            
            // Cap at 4
            strength = Math.min(4, strength);
            
            // Update bars
            for (let i = 0; i < strength; i++) {
                const bar = document.getElementById(strengthBars[i]);
                if (strength <= 1) {
                    bar.className = 'h-1 flex-1 rounded password-strength strength-weak';
                } else if (strength <= 2) {
                    bar.className = 'h-1 flex-1 rounded password-strength strength-medium';
                } else {
                    bar.className = 'h-1 flex-1 rounded password-strength strength-strong';
                }
            }
            
            // Update text
            const strengthTexts = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
            const strengthColors = ['text-red-400', 'text-orange-400', 'text-yellow-400', 'text-green-400', 'text-green-400'];
            
            strengthText.textContent = strengthTexts[strength] || 'Sangat Lemah';
            strengthText.className = `text-xs mt-1 ${strengthColors[strength] || 'text-red-400'}`;
        });

        // Password confirmation checker
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;
            const matchIcon = document.getElementById('match-icon');
            
            if (confirmation.length === 0) {
                matchIcon.classList.add('hidden');
                return;
            }
            
            matchIcon.classList.remove('hidden');
            
            if (password === confirmation) {
                matchIcon.className = 'fas fa-check text-green-400';
            } else {
                matchIcon.className = 'fas fa-times text-red-400';
            }
        });

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Clear previous errors
            ['name-error', 'email-error', 'role-error', 'password-error', 'password-confirmation-error'].forEach(id => {
                document.getElementById(id).classList.add('hidden');
            });
            document.getElementById('error-message').classList.add('hidden');
            
            // Name validation
            const name = document.getElementById('name');
            if (!name.value || name.value.trim().length < 2) {
                document.getElementById('name-error').textContent = 'Nama minimal 2 karakter';
                document.getElementById('name-error').classList.remove('hidden');
                name.classList.add('border-red-500');
                isValid = false;
            } else {
                name.classList.remove('border-red-500');
            }
            
            // Email validation
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value || !emailRegex.test(email.value)) {
                document.getElementById('email-error').textContent = 'Email tidak valid';
                document.getElementById('email-error').classList.remove('hidden');
                email.classList.add('border-red-500');
                isValid = false;
            } else {
                email.classList.remove('border-red-500');
            }
            
            // Password validation
            const password = document.getElementById('password');
            if (!password.value || password.value.length < 8) {
                document.getElementById('password-error').textContent = 'Kata sandi minimal 8 karakter';
                document.getElementById('password-error').classList.remove('hidden');
                password.classList.add('border-red-500');
                isValid = false;
            } else {
                password.classList.remove('border-red-500');
            }
            
            // Password confirmation validation
            const passwordConfirmation = document.getElementById('password_confirmation');
            if (password.value !== passwordConfirmation.value) {
                document.getElementById('password-confirmation-error').textContent = 'Konfirmasi kata sandi tidak cocok';
                document.getElementById('password-confirmation-error').classList.remove('hidden');
                passwordConfirmation.classList.add('border-red-500');
                isValid = false;
            } else {
                passwordConfirmation.classList.remove('border-red-500');
            }
            
            // Terms validation
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                document.getElementById('error-message').classList.remove('hidden');
                document.getElementById('error-text').textContent = 'Anda harus menyetujui syarat dan ketentuan';
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                if (document.getElementById('error-text').textContent === '') {
                    document.getElementById('error-message').classList.remove('hidden');
                    document.getElementById('error-text').textContent = 'Harap perbaiki kesalahan di atas';
                }
            }
        });

        // Display Laravel errors
        @if($errors->any())
            document.getElementById('error-message').classList.remove('hidden');
            document.getElementById('error-text').textContent = '{{ $errors->first() }}';
            
            // Highlight specific fields with errors
            @foreach($errors->keys() as $field)
                const field{{ ucfirst($field) }} = document.getElementById('{{ $field }}');
                if (field{{ ucfirst($field) }}) {
                    field{{ ucfirst($field) }}.classList.add('border-red-500');
                }
            @endforeach
        @endif
    </script>
</body>
</html>