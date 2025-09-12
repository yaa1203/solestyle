@extends('admin.layouts.app')

@section('title', 'Detail Pesan - SoleStyle Admin')

@section('content')

@if(session('success'))
    <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-500/20 border border-red-500/50 rounded-lg text-red-400">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
@endif

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-white">Detail Pesan</h1>
        <a href="{{ route('admin.contacts.index') }}" 
           class="inline-flex items-center px-4 py-2 glass-effect text-white rounded-lg hover:bg-slate-700/50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
    
    <div class="glass-effect rounded-2xl p-8 border border-white/10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div>
                <h2 class="text-xl font-bold text-white mb-4">Informasi Pengirim</h2>
                <div class="space-y-4">
                    <div>
                        <div class="text-slate-400 text-sm mb-1">Nama</div>
                        <div class="text-white font-medium">{{ $contact->name }}</div>
                    </div>
                    <div>
                        <div class="text-slate-400 text-sm mb-1">Email</div>
                        <div class="text-white font-medium">{{ $contact->email }}</div>
                    </div>
                    @if($contact->phone)
                        <div>
                            <div class="text-slate-400 text-sm mb-1">Telepon</div>
                            <div class="text-white font-medium">{{ $contact->phone }}</div>
                        </div>
                    @endif
                    <div>
                        <div class="text-slate-400 text-sm mb-1">Tanggal</div>
                        <div class="text-white font-medium">{{ $contact->created_at->format('d F Y, H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-slate-400 text-sm mb-1">Status</div>
                        @if($contact->read_at === null)
                            <span class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm px-3 py-1 rounded-full font-semibold">
                                Belum Dibaca
                            </span>
                        @else
                            <span class="bg-gradient-to-r from-green-500 to-teal-500 text-white text-sm px-3 py-1 rounded-full font-semibold">
                                Dibaca pada {{ $contact->read_at->format('d F Y, H:i') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="text-xl font-bold text-white mb-4">Detail Pesan</h2>
                <div class="space-y-4">
                    <div>
                        <div class="text-slate-400 text-sm mb-1">Subjek</div>
                        <div class="text-white font-medium">{{ $contact->subject }}</div>
                    </div>
                    <div>
                        <div class="text-slate-400 text-sm mb-1">Isi Pesan</div>
                        <div class="text-white bg-slate-800/50 rounded-lg p-4 mt-2 whitespace-pre-wrap">{{ $contact->message }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="border-t border-slate-700 pt-6">
            <h2 class="text-xl font-bold text-white mb-4">Balasan Pesan</h2>
            <form action="{{ route('admin.contacts.reply', $contact) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="reply" class="block text-slate-300 font-medium mb-2">Balasan</label>
                    <textarea 
                        id="reply" 
                        name="reply" 
                        rows="5" 
                        required
                        class="form-input w-full px-6 py-4 rounded-xl text-white placeholder-slate-500 focus:outline-none resize-none"
                        placeholder="Tulis balasan Anda..."
                    ></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-8 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shine-effect">
                        <i class="fas fa-reply mr-2"></i>Kirim Balasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection