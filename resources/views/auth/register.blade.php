@extends('layouts.base')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-slate-900 px-4">
    <div class="w-full max-w-md py-10 px-6 bg-slate-800/50 backdrop-blur-md rounded-xl shadow-2xl border border-purple-500/20">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold logo-text">Daftar Akun</h1>
            <p class="text-slate-400 mt-2">Buat akun baru untuk mulai berbelanja.</p>
        </div>
        
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-slate-300">Nama Lengkap</label>
                <input type="text" id="name" name="name" required autocomplete="name" autofocus
                       class="mt-1 block w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors"
                       value="{{ old('name') }}">
                @error('name')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-slate-300">Alamat Email</label>
                <input type="email" id="email" name="email" required autocomplete="email"
                       class="mt-1 block w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors"
                       value="{{ old('email') }}">
                @error('email')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-slate-300">Kata Sandi</label>
                <input type="password" id="password" name="password" required autocomplete="new-password"
                       class="mt-1 block w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
                @error('password')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-300">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                       class="mt-1 block w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
            </div>

            <div>
                <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-lg text-white font-semibold bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    Daftar
                </button>
            </div>
        </form>

        <div class="mt-8 text-center text-sm text-slate-400">
            Sudah punya akun? <a href="{{ route('login.show') }}" class="font-medium text-purple-400 hover:underline">Masuk sekarang</a>
        </div>
    </div>
</div>
@endsection