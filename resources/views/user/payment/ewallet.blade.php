<!-- resources/views/user/payment/ewallet.blade.php -->
@extends('user.layouts.app')

@section('title', 'Pembayaran E-Wallet - SoleStyle')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">Pembayaran E-Wallet</h1>
            <p class="text-slate-400 mt-2">Lengkapi pembayaran Anda untuk menyelesaikan pesanan</p>
        </div>
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-8">
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-white mb-4">Detail Pesanan</h2>
                <div class="bg-slate-700/30 rounded-lg p-4">
                    <p class="text-slate-300">Nomor Pesanan: <span class="text-white font-medium">{{ $order->order_number }}</span></p>
                    <p class="text-slate-300">Metode Pembayaran: <span class="text-white font-medium">{{ ucfirst($paymentMethod) }}</span></p>
                    <p class="text-slate-300">Total Pembayaran: <span class="text-white font-bold text-lg">Rp {{ number_format($order->total, 0, ',', '.') }}</span></p>
                </div>
            </div>
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-white mb-4">Cara Pembayaran</h2>
                <div class="bg-slate-700/30 rounded-lg p-6">
                    <div class="flex items-start mb-4">
                        <div class="bg-purple-500/20 p-3 rounded-full mr-4">
                            <i class="fas fa-wallet text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-medium mb-2">1. Buka Aplikasi {{ ucfirst($paymentMethod) }}</h3>
                            <p class="text-slate-400 text-sm">Pastikan Anda sudah login ke akun {{ ucfirst($paymentMethod) }} Anda.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start mb-4">
                        <div class="bg-purple-500/20 p-3 rounded-full mr-4">
                            <i class="fas fa-qrcode text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-medium mb-2">2. Scan QR Code</h3>
                            <p class="text-slate-400 text-sm">Gunakan fitur scan QR di aplikasi {{ ucfirst($paymentMethod) }} untuk membayar.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-purple-500/20 p-3 rounded-full mr-4">
                            <i class="fas fa-check-circle text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-medium mb-2">3. Konfirmasi Pembayaran</h3>
                            <p class="text-slate-400 text-sm">Pastikan jumlah pembayaran sudah benar, lalu konfirmasi pembayaran.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-white mb-4">QR Code Pembayaran</h2>
                <div class="bg-white p-6 rounded-lg flex justify-center">
                    <!-- QR Code Placeholder -->
                    <div class="bg-gray-200 w-64 h-64 flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-qrcode text-gray-400 text-6xl mb-2"></i>
                            <p class="text-gray-500">QR Code Pembayaran</p>
                            <p class="text-gray-400 text-sm mt-2">{{ $transactionId }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-white mb-4">Detail Pembayaran</h2>
                <div class="bg-slate-700/30 rounded-lg p-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-300">Nomor Pesanan:</span>
                        <span class="text-white">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-300">Jumlah:</span>
                        <span class="text-white">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-300">Kode Pembayaran:</span>
                        <span class="text-white font-mono">{{ $transactionId }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-300">Berlaku hingga:</span>
                        <span class="text-white">{{ now()->addMinutes(15)->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Bagian Unggah Bukti Pembayaran -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-white mb-4">Unggah Bukti Pembayaran</h2>
                <div class="bg-slate-700/30 rounded-lg p-6">
                    <p class="text-slate-400 mb-4">Setelah melakukan pembayaran, silakan unggah bukti pembayaran Anda (struk) untuk mempercepat proses verifikasi.</p>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-300 mb-2">Pilih File Bukti Pembayaran</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="receipt-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-600 rounded-lg cursor-pointer bg-slate-700/30 hover:bg-slate-700/50 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-cloud-upload-alt text-purple-400 text-3xl mb-2"></i>
                                    <p class="mb-2 text-sm text-slate-400">
                                        <span class="font-semibold">Klik untuk mengunggah</span> atau seret dan letakkan file di sini
                                    </p>
                                    <p class="text-xs text-slate-500">PNG, JPG, PDF (Max. 5MB)</p>
                                </div>
                                <input id="receipt-upload" type="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" />
                            </label>
                        </div>
                        
                        <!-- Preview gambar yang diunggah -->
                        <div id="receipt-preview" class="mt-4 hidden">
                            <div class="bg-slate-700/30 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-slate-300">File yang diunggah:</span>
                                    <button type="button" id="remove-file" class="text-red-400 hover:text-red-300">
                                        <i class="fas fa-times-circle"></i> Hapus
                                    </button>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div id="image-preview" class="w-16 h-16 rounded-lg overflow-hidden bg-slate-600 flex items-center justify-center">
                                        <i class="fas fa-file-image text-slate-400"></i>
                                    </div>
                                    <div id="file-info" class="flex-1">
                                        <p class="text-white font-medium" id="file-name">nama_file.jpg</p>
                                        <p class="text-slate-400 text-sm" id="file-size">0 KB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <button id="submit-receipt" 
                                class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-3 rounded-lg font-medium transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                            <i class="fas fa-paper-plane mr-2"></i>
                            Kirim Bukti Pembayaran
                        </button>
                        
                        <button id="simulate-failure" 
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-medium transition-all">
                            <i class="fas fa-times-circle mr-2"></i>
                            Simulasi Pembayaran Gagal
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <button onclick="window.location.href='{{ route('order.show', $order->id) }}'" 
                        class="flex-1 bg-slate-700 hover:bg-slate-600 text-white py-3 rounded-lg font-medium transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Detail Pesanan
                </button>
                
                <button onclick="window.location.href='{{ route('dashboard') }}'" 
                        class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-3 rounded-lg font-medium transition-all">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh the page every 10 seconds to check payment status
    setInterval(function() {
        fetch(`/api/payment/check/{{ $order->id }}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'paid') {
                    showNotification('Pembayaran berhasil! Mengalihkan ke halaman pesanan...', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route('order.success') }}';
                    }, 2000);
                } else if (data.status === 'failed') {
                    showNotification('Pembayaran gagal. Silakan coba lagi.', 'error');
                }
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
            });
    }, 10000);
    
    // Handle file upload
    const fileInput = document.getElementById('receipt-upload');
    const submitButton = document.getElementById('submit-receipt');
    const removeButton = document.getElementById('remove-file');
    const previewContainer = document.getElementById('receipt-preview');
    const imagePreview = document.getElementById('image-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                showNotification('Ukuran file maksimal 5MB', 'error');
                fileInput.value = '';
                return;
            }
            
            // Show preview
            previewContainer.classList.remove('hidden');
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            
            // Show image preview for images
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.innerHTML = '<i class="fas fa-file-pdf text-slate-400 text-2xl"></i>';
            }
            
            // Enable submit button
            submitButton.disabled = false;
        }
    });
    
    // Remove file button
    removeButton.addEventListener('click', function() {
        fileInput.value = '';
        previewContainer.classList.add('hidden');
        submitButton.disabled = true;
    });
    
    // Submit receipt
    submitButton.addEventListener('click', function() {
        const file = fileInput.files[0];
        if (!file) {
            showNotification('Silakan pilih file bukti pembayaran', 'error');
            return;
        }
        
        // Show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
        
        // Create FormData
        const formData = new FormData();
        formData.append('receipt', file);
        formData.append('order_id', '{{ $order->id }}');
        formData.append('payment_method', '{{ $paymentMethod }}');
        formData.append('transaction_id', '{{ $transactionId }}');
        
        // Send request
        fetch('{{ route('payment.upload.receipt') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => {
                    window.location.href = '{{ route('order.success') }}';
                }, 1500);
            } else {
                throw new Error(data.message || 'Gagal mengupload bukti pembayaran');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message || 'Terjadi kesalahan saat mengupload bukti pembayaran', 'error');
            // Reset button
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Kirim Bukti Pembayaran';
        });
    });
    
    // Simulate payment failure
    document.getElementById('simulate-failure').addEventListener('click', function() {
        const button = this;
        const originalText = button.innerHTML;
        
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
        
        fetch('{{ route('payment.simulate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                order_id: '{{ $order->id }}',
                payment_method: '{{ $paymentMethod }}',
                transaction_id: '{{ $transactionId }}',
                success: false
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'error');
                setTimeout(() => {
                    window.location.href = '{{ route('order.failed', $order->id) }}';
                }, 1500);
            } else {
                throw new Error(data.message || 'Gagal memproses pembayaran');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message || 'Terjadi kesalahan saat memproses pembayaran', 'error');
            // Reset button
            button.disabled = false;
            button.innerHTML = originalText;
        });
    });
});

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Notification function
function showNotification(message, type = 'info') {
    const colors = {
        success: 'from-green-500 to-emerald-500',
        error: 'from-red-500 to-pink-500',
        warning: 'from-yellow-500 to-orange-500',
        info: 'from-blue-500 to-purple-500'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 bg-gradient-to-r ${colors[type]} text-white px-6 py-4 rounded-xl shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    toast.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas ${icons[type]}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 4000);
}
</script>
@endsection