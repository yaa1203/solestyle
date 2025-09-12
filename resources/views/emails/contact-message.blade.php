<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Baru dari Form Kontak</title>
</head>
<body style="font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f8f9fa;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h1 style="color: #6366f1; margin-bottom: 20px;">Pesan Baru dari Form Kontak</h1>
        
        <div style="margin-bottom: 20px;">
            <p><strong>Nama:</strong> {{ $contact->name }}</p>
            <p><strong>Email:</strong> {{ $contact->email }}</p>
            @if($contact->phone)
                <p><strong>Telepon:</strong> {{ $contact->phone }}</p>
            @endif
            <p><strong>Subjek:</strong> {{ $contact->subject }}</p>
        </div>
        
        <div style="border-top: 1px solid #e5e7eb; padding-top: 20px;">
            <h2 style="margin-top: 0; color: #4b5563;">Isi Pesan</h2>
            <p style="white-space: pre-wrap;">{{ $contact->message }}</p>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #6b7280;">
            <p>Ini adalah pesan otomatis dari website SoleStyle. Silakan balas email ini untuk merespons pengunjung.</p>
        </div>
    </div>
</body>
</html>