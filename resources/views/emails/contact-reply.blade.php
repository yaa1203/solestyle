<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balasan dari SoleStyle</title>
</head>
<body style="font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f8f9fa;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #6366f1; margin-bottom: 10px;">SoleStyle</h1>
            <p style="color: #6b7280;">Terima kasih telah menghubungi kami</p>
        </div>
        
        <div style="border-bottom: 1px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px;">
            <h2 style="color: #4b5563; margin-bottom: 15px;">Pesan Anda</h2>
            <div style="background-color: #f9fafb; padding: 15px; border-radius: 6px;">
                <p style="margin: 0 0 10px 0;"><strong>Subjek:</strong> {{ $contact->subject }}</p>
                <p style="white-space: pre-wrap; margin: 0;">{{ $contact->message }}</p>
            </div>
        </div>
        
        <div style="margin-bottom: 30px;">
            <h2 style="color: #4b5563; margin-bottom: 15px;">Balasan Kami</h2>
            <div style="background-color: #f3f4f6; padding: 15px; border-radius: 6px; border-left: 4px solid #6366f1;">
                <p style="white-space: pre-wrap; margin: 0;">{{ $reply }}</p>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <p style="color: #6b7280; margin-bottom: 10px;">Terima kasih telah berbelanja di SoleStyle</p>
            <p style="color: #6b7280; font-size: 14px;">
                Jika Anda memiliki pertanyaan lebih lanjut, silakan balas email ini.
            </p>
        </div>
    </div>
</body>
</html>