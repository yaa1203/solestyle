<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;
use App\Mail\ContactReply;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        return view('user.pages.contact');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|min:10',
            ]);

            $contact = Contact::create($validatedData);

            Mail::to(config('mail.from.address'))->send(new ContactMessage($contact));

            return response()->json([
                'success' => true,
                'message' => 'Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.'
            ]);
        } catch (\Exception $e) {
            Log::error('Contact submission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.'
            ], 500);
        }
    }

    public function adminIndex()
    {
        $unreadCount = Contact::unread()->count();
        $contacts = Contact::latest()->paginate(10);
        
        return view('admin.pages.contacts.index', compact('contacts', 'unreadCount'));
    }

    public function show(Contact $contact)
    {
        if ($contact->read_at === null) {
            $contact->markAsRead();
        }
        
        return view('admin.pages.contacts.show', compact('contact'));
    }

    public function reply(Request $request, Contact $contact)
    {
        try {
            $validatedData = $request->validate([
                'reply' => 'required|string|min:10',
            ]);
            
            // Kirim balasan ke email pengirim
            Mail::to($contact->email)->send(new ContactReply($contact, $validatedData['reply']));
            
            // Update status pesan
            $contact->update(['status' => 'replied']);
            
            return redirect()->route('admin.contacts.show', $contact)
                ->with('success', 'Balasan telah berhasil dikirim.');
        } catch (\Exception $e) {
            Log::error('Contact reply error: ' . $e->getMessage());
            return redirect()->route('admin.contacts.show', $contact)
                ->with('error', 'Terjadi kesal saat mengirim balasan: ' . $e->getMessage());
        }
    }
}