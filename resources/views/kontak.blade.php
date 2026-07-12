@extends('layouts.app')

@section('title', 'Hubungi Kami | ' . \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))

@section('content')
<!-- Header Page -->
<section class="bg-gray-900 py-16 text-white relative">
    <div class="absolute inset-0 bg-gradient-to-br from-primary/30 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-3">
        <h1 class="text-3xl font-bold font-title tracking-tight sm:text-4xl">Hubungi Kami</h1>
        <p class="text-gray-300 max-w-xl mx-auto">Ada pertanyaan tentang produk atau pemesanan dalam jumlah besar (B2B)? Kirimkan pesan di bawah ini.</p>
    </div>
</section>

<!-- Contact Form Section -->
<section class="py-24 bg-white flex-grow">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div x-data="{ 
            name: '', 
            email: '', 
            phone: '', 
            message: '', 
            website: '', 
            statusMessage: '', 
            statusType: '', 
            errors: {}, 
            loading: false,
            submitForm() {
                this.loading = true;
                this.statusMessage = '';
                this.errors = {};

                fetch('{{ route('kontak.submit') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: this.name,
                        email: this.email,
                        phone: this.phone,
                        message: this.message,
                        website: this.website, // Honeypot
                        source_page: 'kontak'
                    })
                })
                .then(response => {
                    this.loading = false;
                    return response.json().then(data => ({
                        status: response.status,
                        body: data
                    }));
                })
                .then(res => {
                    if (res.status === 200) {
                        this.statusMessage = res.body.message;
                        this.statusType = 'success';
                        // Reset form fields
                        this.name = '';
                        this.email = '';
                        this.phone = '';
                        this.message = '';
                    } else if (res.status === 422) {
                        if (res.body.errors) {
                            this.errors = res.body.errors;
                        } else {
                            this.statusMessage = res.body.message || 'Pesan terdeteksi sebagai spam.';
                            this.statusType = 'danger';
                        }
                    } else {
                        this.statusMessage = 'Terjadi kesalahan sistem. Silakan coba lagi.';
                        this.statusType = 'danger';
                    }
                })
                .catch(err => {
                    this.loading = false;
                    this.statusMessage = 'Gagal mengirim pesan. Periksa koneksi internet Anda.';
                    this.statusType = 'danger';
                });
            }
        }" class="bg-gray-50 rounded-3xl border border-gray-100 p-8 md:p-12 shadow-sm">
            
            <h2 class="text-2xl font-bold text-gray-900 font-title mb-6">Formulir Hubungi Kami</h2>

            <!-- Status Alert Banner -->
            <template x-if="statusMessage">
                <div 
                    class="p-4 mb-6 rounded-2xl text-sm font-semibold border"
                    :class="{
                        'bg-green-50 text-green-700 border-green-200/50': statusType === 'success',
                        'bg-red-50 text-red-700 border-red-200/50': statusType === 'danger'
                    }"
                    x-text="statusMessage"
                ></div>
            </template>

            <!-- Form Layout -->
            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Honeypot anti-spam field -->
                <div class="hidden" aria-hidden="true">
                    <label for="website">Website URL (Jangan diisi)</label>
                    <input type="text" id="website" x-model="website" autocomplete="off" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="name" x-model="name" class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:border-primary focus:ring-primary focus:outline-none" placeholder="Masukkan nama Anda..." required />
                        <template x-if="errors.name">
                            <span class="text-xs text-red-500 font-medium" x-text="errors.name[0]"></span>
                        </template>
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" x-model="email" class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:border-primary focus:ring-primary focus:outline-none" placeholder="Masukkan email Anda..." required />
                        <template x-if="errors.email">
                            <span class="text-xs text-red-500 font-medium" x-text="errors.email[0]"></span>
                        </template>
                    </div>
                </div>

                <!-- Phone -->
                <div class="space-y-2">
                    <label for="phone" class="block text-sm font-semibold text-gray-700">Nomor Telepon / WhatsApp (Opsional)</label>
                    <input type="tel" id="phone" x-model="phone" class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:border-primary focus:ring-primary focus:outline-none" placeholder="Misal: 081234567890" />
                    <template x-if="errors.phone">
                        <span class="text-xs text-red-500 font-medium" x-text="errors.phone[0]"></span>
                    </template>
                </div>

                <!-- Message -->
                <div class="space-y-2">
                    <label for="message" class="block text-sm font-semibold text-gray-700">Isi Pesan <span class="text-red-500">*</span></label>
                    <textarea id="message" x-model="message" rows="5" class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:border-primary focus:ring-primary focus:outline-none" placeholder="Tuliskan pesan atau pertanyaan Anda..." required></textarea>
                    <template x-if="errors.message">
                        <span class="text-xs text-red-500 font-medium" x-text="errors.message[0]"></span>
                    </template>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="w-full md:w-auto inline-flex items-center justify-center px-6 py-3.5 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-primary/95 transition shadow-lg shadow-primary/20 transform hover:-translate-y-0.5 focus:outline-none disabled:opacity-50 disabled:transform-none disabled:pointer-events-none"
                        :disabled="loading"
                    >
                        <span x-show="!loading">Kirim Pesan</span>
                        <span x-show="loading" class="flex items-center gap-2">
                            <!-- Simple spinner -->
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mengirim...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
