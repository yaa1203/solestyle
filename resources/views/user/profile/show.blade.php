@extends('user.layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6">Profil Saya</h1>

    @if(session('status'))
        <div class="mb-4 p-3 bg-green-600/20 text-green-400 rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6 bg-slate-800/50 p-6 rounded-xl shadow-lg border border-slate-700">
        @csrf

        <!-- Foto Profil -->
        <div class="flex items-center space-x-6">
            <div class="w-24 h-24 rounded-full overflow-hidden bg-slate-700 flex items-center justify-center">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/'.$user->profile_photo) }}" class="w-full h-full object-cover" alt="Foto Profil">
                @else
                    <i class="fas fa-user text-4xl text-slate-400"></i>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Ganti Foto Profil</label>
                <input type="file" name="profile_photo" accept="image/*" class="mt-1 text-slate-300">
                @error('profile_photo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Nama & Email -->
        <div>
            <label class="block text-sm font-medium text-slate-300">Nama</label>
            <input type="text" value="{{ $user->name }}" readonly class="mt-1 block w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-300">Email</label>
            <input type="text" value="{{ $user->email }}" readonly class="mt-1 block w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white">
        </div>
        <!-- Nomor Telepon -->
        <div>
            <label class="block text-sm font-medium text-slate-300">Nomor Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                class="mt-1 block w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white">
        </div>


        <!-- Alamat -->
        <div>
            <label class="block text-sm font-medium text-slate-300">Alamat Lengkap</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="mt-1 block w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-300">Kota</label>
                <input type="text" name="city" value="{{ old('city', $user->city) }}" class="mt-1 block w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Provinsi</label>
                <input type="text" name="province" value="{{ old('province', $user->province) }}" class="mt-1 block w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Kode Pos</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="mt-1 block w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white">
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div>
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition-all">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
