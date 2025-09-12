@extends('admin.layouts.app')

@section('title', 'Pesan Kontak - SoleStyle Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-white">Pesan Kontak</h1>
        <span class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 rounded-full text-sm font-semibold">
            <i class="fas fa-envelope mr-2"></i>
            Belum Dibaca: {{ $unreadCount }}
        </span>
    </div>
    
    <div class="glass-effect rounded-2xl p-6 border border-white/10">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-700">
                        <th class="py-3 px-4 text-slate-300 font-semibold">Nama</th>
                        <th class="py-3 px-4 text-slate-300 font-semibold">Email</th>
                        <th class="py-3 px-4 text-slate-300 font-semibold">Subjek</th>
                        <th class="py-3 px-4 text-slate-300 font-semibold">Status</th>
                        <th class="py-3 px-4 text-slate-300 font-semibold">Tanggal</th>
                        <th class="py-3 px-4 text-slate-300 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                    <tr class="border-b border-slate-800 hover:bg-slate-800/50 transition-colors">
                        <td class="py-4 px-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white font-semibold text-sm">{{ substr($contact->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-white font-medium">{{ $contact->name }}</div>
                                    <div class="text-slate-400 text-sm">{{ $contact->phone ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-slate-300">{{ $contact->email }}</td>
                        <td class="py-4 px-4">
                            <div class="text-white">{{ $contact->subject }}</div>
                            <div class="text-slate-400 text-sm line-clamp-1">{{ Str::limit($contact->message, 50) }}</div>
                        </td>
                        <td class="py-4 px-4">
                            @if($contact->read_at === null)
                                <span class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                                    Belum Dibaca
                                </span>
                            @else
                                <span class="bg-gradient-to-r from-green-500 to-teal-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                                    Sudah Dibaca
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-slate-300">
                            {{ $contact->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="py-4 px-4 text-right">
                            <a href="{{ route('admin.contacts.show', $contact) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:opacity-90 transition-opacity">
                                <i class="fas fa-eye mr-2"></i>Lihat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6 flex justify-center">
            {{ $contacts->links() }}
        </div>
    </div>
</div>
@endsection