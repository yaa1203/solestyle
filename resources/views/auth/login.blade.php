@extends('layouts.base')

@section('title', 'Masuk ke Akun Anda')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-slate-900 px-4">
    <div class="w-full max-w-md py-10 px-6 bg-slate-800/50 backdrop-blur-md rounded-xl shadow-2xl border border-purple-500/20">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold logo-text">Masuk</h1>
            <p class="text-slate-400 mt-2">Masuk untuk melanjutkan belanja Anda.</p>
        </div>

        @if(session('status'))
            <div class="bg-green-500/20 text-green-300 p-4 rounded-lg mb-4 text-sm" role="alert">
                {{ session('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-slate-300">Alamat Email</label>
                <input type="email" id="email" name="email" required autocomplete="email" autofocus
                       class="mt-1 block w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors"
                       value="{{ old('email') }}">
                @error('email')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-slate-300">Kata Sandi</label>
                <input type="password" id="password" name="password" required autocomplete="current-password"
                       class="mt-1 block w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
                @error('password')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-purple-600 bg-slate-700 border-slate-600 rounded focus:ring-purple-500">
                    <label for="remember_me" class="ml-2 block text-sm text-slate-400">
                        Ingat saya
                    </label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-purple-400 hover:text-purple-300 transition-colors">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <div>
                <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-lg text-white font-semibold bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    Masuk
                </button>
            </div>
        </form>

        <div class="mt-8 text-center text-sm text-slate-400">
            Belum punya akun? <a href="{{ route('register.show') }}" class="font-medium text-purple-400 hover:underline">Daftar sekarang</a>
        </div>
    </div>
</div>
@endsection