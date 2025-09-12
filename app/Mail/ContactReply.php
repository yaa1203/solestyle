<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReply extends Mailable
{
    use Queueable, SerializesModels;

    protected $contact;
    protected $reply;

    public function __construct(Contact $contact, $reply)
    {
        $this->contact = $contact;
        $this->reply = $reply;
    }

    public function build()
    {
        return $this->subject('Balasan dari SoleStyle: ' . $this->contact->subject)
            ->markdown('emails.contact-reply', [
                'contact' => $this->contact,
                'reply' => $this->reply,
            ]);
    }
}