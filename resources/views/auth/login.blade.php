<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk ke Akun Anda - SoleStyle</title>
    
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
            left: 10%;
        }
        
        .floating-shapes::after {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #06b6d4, #8b5cf6);
            bottom: 20%;
            right: 15%;
        }
        
        .error-message {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body class="bg-slate-900 text-white min-h-screen">
    <div class="floating-shapes"></div>
    
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-md py-10 px-6 glass-effect rounded-xl shadow-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shoe-prints text-white text-lg"></i>
                    </div>
                    <span class="logo-text text-2xl font-bold">SoleStyle</span>
                </div>
                <h1 class="text-3xl font-bold text-white">Masuk</h1>
                <p class="text-slate-400 mt-2">Masuk untuk melanjutkan belanja Anda.</p>
            </div>

            <!-- Success Message -->
            <div id="success-message" class="hidden bg-green-500/20 text-green-300 p-4 rounded-lg mb-4 text-sm border border-green-500/30" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span id="success-text"></span>
                </div>
            </div>

            <!-- Error Message -->
            <div id="error-message" class="hidden bg-red-500/20 text-red-300 p-4 rounded-lg mb-4 text-sm border border-red-500/30 error-message" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span id="error-text"></span>
                </div>
            </div>
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6" id="loginForm">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-envelope mr-2 text-purple-400"></i>Alamat Email
                    </label>
                    <input type="email" id="email" name="email" required autocomplete="email" autofocus
                           class="input-field mt-1 block w-full px-4 py-3 rounded-lg text-white focus:outline-none transition-all"
                           placeholder="nama@email.com"
                           value="{{ old('email') }}">
                    <div id="email-error" class="hidden mt-2 text-sm text-red-400"></div>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-lock mr-2 text-purple-400"></i>Kata Sandi
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required autocomplete="current-password"
                               class="input-field mt-1 block w-full px-4 py-3 pr-12 rounded-lg text-white focus:outline-none transition-all"
                               placeholder="Masukkan kata sandi">
                        <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-purple-400 transition-colors">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <div id="password-error" class="hidden mt-2 text-sm text-red-400"></div>
                </div>

                <div>
                    <button type="submit" class="btn-primary w-full py-3 px-4 border border-transparent rounded-lg text-white font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-slate-400">
                Belum punya akun? 
                <a href="{{ route('register.show') }}" class="font-medium text-purple-400 hover:text-purple-300 transition-colors">
                    Daftar sekarang
                </a>
            </div>

            <!-- Alternative Login -->
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

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Clear previous errors
            document.getElementById('email-error').classList.add('hidden');
            document.getElementById('password-error').classList.add('hidden');
            document.getElementById('error-message').classList.add('hidden');
            
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
            if (!password.value || password.value.length < 6) {
                document.getElementById('password-error').textContent = 'Kata sandi minimal 6 karakter';
                document.getElementById('password-error').classList.remove('hidden');
                password.classList.add('border-red-500');
                isValid = false;
            } else {
                password.classList.remove('border-red-500');
            }
            
            if (!isValid) {
                e.preventDefault();
                document.getElementById('error-message').classList.remove('hidden');
                document.getElementById('error-text').textContent = 'Harap perbaiki kesalahan di atas';
            }
        });

        // Display Laravel errors
        @if($errors->any())
            document.getElementById('error-message').classList.remove('hidden');
            document.getElementById('error-text').textContent = '{{ $errors->first() }}';
        @endif

        // Display success message
        @if(session('status'))
            document.getElementById('success-message').classList.remove('hidden');
            document.getElementById('success-text').textContent = '{{ session('status') }}';
        @endif
    </script>
</body>
</html>