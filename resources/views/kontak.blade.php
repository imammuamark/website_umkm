@extends('layouts.app')

@section('title', 'Hubungi Kami | ' . \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))

@section('content')
@include('partials.page_hero', array_merge(['eyebrow' => 'Mari Terhubung', 'title' => 'Hubungi Kami', 'subtitle' => 'Tanyakan informasi menu, kunjungan, atau hal lain kepada tim Panama Corner.'], \App\Models\SiteSetting::pageHero('contact')))

<section class="public-page-content flex-grow py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div
            x-data="{
                name: '', email: '', phone: '', message: '', website: '',
                statusMessage: '', statusType: '', errors: {}, loading: false,
                submitForm() {
                    if (this.loading) return;
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
                            website: this.website,
                            source_page: 'kontak'
                        })
                    })
                    .then(async response => ({ status: response.status, body: await response.json() }))
                    .then(res => {
                        if (res.status === 200) {
                            this.statusMessage = res.body.message;
                            this.statusType = 'success';
                            this.name = ''; this.email = ''; this.phone = ''; this.message = '';
                        } else if (res.status === 422 && res.body.errors) {
                            this.errors = res.body.errors;
                        } else {
                            this.statusMessage = res.body.message || 'Pesan belum dapat dikirim. Silakan coba kembali.';
                            this.statusType = 'danger';
                        }
                    })
                    .catch(() => {
                        this.statusMessage = 'Koneksi bermasalah. Silakan periksa koneksi Anda dan coba kembali.';
                        this.statusType = 'danger';
                    })
                    .finally(() => this.loading = false);
                }
            }"
            class="contact-workspace home-reveal overflow-hidden rounded-[1.75rem] border border-[#d8d4ca] bg-white shadow-[0_28px_80px_rgba(16,37,31,.09)]"
        >
            <div class="grid lg:grid-cols-[.82fr_1.45fr]">
                <aside class="relative isolate overflow-hidden bg-[#0b2420] p-8 text-white sm:p-10 lg:p-12">
                    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_10%_10%,rgba(245,158,11,.22),transparent_32%),radial-gradient(circle_at_90%_85%,rgba(15,118,110,.48),transparent_46%)]"></div>
                    <div class="absolute -bottom-24 -right-20 -z-10 h-64 w-64 rounded-full border border-white/10"></div>
                    <div class="absolute -bottom-12 -right-8 -z-10 h-40 w-40 rounded-full border border-white/10"></div>

                    <p class="text-[10px] font-bold uppercase tracking-[.22em] text-secondary">Panama Corner</p>
                    <h2 class="mt-4 text-3xl font-semibold leading-tight tracking-[-.035em]">Ada yang ingin ditanyakan?</h2>
                    <p class="mt-4 text-sm leading-7 text-white/65">Sampaikan kebutuhan Anda dengan jelas. Tim kami akan meninjau pesan dan menghubungi Anda melalui kanal yang diberikan.</p>

                    <div class="mt-10 space-y-6">
                        <div class="flex gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/8 text-secondary">
                                <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                            </span>
                            <div><p class="text-[10px] font-bold uppercase tracking-wider text-white/45">Email</p><a class="mt-1 block text-sm font-semibold hover:text-secondary" href="mailto:{{ \App\Models\SiteSetting::get('email_address', 'info@panamacorner.com') }}">{{ \App\Models\SiteSetting::get('email_address', 'info@panamacorner.com') }}</a></div>
                        </div>
                        <div class="flex gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/8 text-secondary">
                                <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7A2 2 0 0 1 22 16.9Z"/></svg>
                            </span>
                            <div><p class="text-[10px] font-bold uppercase tracking-wider text-white/45">Telepon</p><p class="mt-1 text-sm font-semibold">{{ \App\Models\SiteSetting::get('office_phone', '+62 22 1234567') }}</p></div>
                        </div>
                        <div class="flex gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/8 text-secondary">
                                <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                            </span>
                            <div><p class="text-[10px] font-bold uppercase tracking-wider text-white/45">Waktu Respons</p><p class="mt-1 text-sm font-semibold">Pada jam operasional</p></div>
                        </div>
                    </div>

                    <div class="mt-10 border-t border-white/10 pt-6 text-xs leading-5 text-white/45">Informasi yang Anda kirim hanya digunakan untuk menindaklanjuti pertanyaan ini.</div>
                </aside>

                <div class="p-7 sm:p-10 lg:p-12">
                    <div class="mb-8">
                        <p class="text-[10px] font-bold uppercase tracking-[.18em] text-primary">Formulir Kontak</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-[-.025em] text-[#10251f]">Ceritakan kebutuhan Anda</h2>
                        <p class="mt-2 text-sm text-gray-500">Kolom bertanda <span class="text-red-500">*</span> wajib diisi.</p>
                    </div>

                    <template x-if="statusMessage">
                        <div role="status" aria-live="polite" class="mb-6 flex items-start gap-3 rounded-xl border p-4 text-sm" :class="statusType === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-red-200 bg-red-50 text-red-700'">
                            <span class="mt-0.5 font-bold" x-text="statusType === 'success' ? '✓' : '!' "></span><span x-text="statusMessage"></span>
                        </div>
                    </template>

                    <form @submit.prevent="submitForm" class="space-y-5" novalidate>
                        <div class="sr-only" aria-hidden="true"><label for="website">Website</label><input type="text" id="website" x-model="website" tabindex="-1" autocomplete="off"></div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="space-y-2">
                                <label for="name" class="block text-xs font-bold text-gray-700">Nama lengkap <span class="text-red-500">*</span></label>
                                <input type="text" id="name" x-model.trim="name" autocomplete="name" maxlength="100" :aria-invalid="Boolean(errors.name)" class="contact-input block min-h-12 w-full rounded-xl px-4 text-sm outline-none" placeholder="Nama Anda" required>
                                <template x-if="errors.name"><p class="text-xs font-medium text-red-600" x-text="errors.name[0]"></p></template>
                            </div>
                            <div class="space-y-2">
                                <label for="email" class="block text-xs font-bold text-gray-700">Alamat email <span class="text-red-500">*</span></label>
                                <input type="email" id="email" x-model.trim="email" autocomplete="email" maxlength="150" :aria-invalid="Boolean(errors.email)" class="contact-input block min-h-12 w-full rounded-xl px-4 text-sm outline-none" placeholder="nama@perusahaan.com" required>
                                <template x-if="errors.email"><p class="text-xs font-medium text-red-600" x-text="errors.email[0]"></p></template>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between gap-4"><label for="phone" class="block text-xs font-bold text-gray-700">Nomor WhatsApp</label><span class="text-[10px] font-medium text-gray-400">Opsional</span></div>
                            <input type="tel" id="phone" x-model.trim="phone" autocomplete="tel" maxlength="20" :aria-invalid="Boolean(errors.phone)" class="contact-input block min-h-12 w-full rounded-xl px-4 text-sm outline-none" placeholder="Contoh: 0812 3456 7890">
                            <template x-if="errors.phone"><p class="text-xs font-medium text-red-600" x-text="errors.phone[0]"></p></template>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between gap-4"><label for="message" class="block text-xs font-bold text-gray-700">Pesan <span class="text-red-500">*</span></label><span class="text-[10px] font-medium text-gray-400" x-text="`${message.length}/1000`"></span></div>
                            <textarea id="message" x-model="message" rows="6" maxlength="1000" :aria-invalid="Boolean(errors.message)" class="contact-input block w-full resize-y rounded-xl px-4 py-3 text-sm leading-6 outline-none" placeholder="Tuliskan pertanyaan atau informasi yang ingin Anda sampaikan..." required></textarea>
                            <template x-if="errors.message"><p class="text-xs font-medium text-red-600" x-text="errors.message[0]"></p></template>
                        </div>

                        <div class="flex flex-col gap-4 border-t border-gray-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                            <p class="max-w-sm text-[11px] leading-5 text-gray-400">Dengan mengirim formulir, Anda menyetujui pemrosesan data untuk keperluan tindak lanjut.</p>
                            <button type="submit" :disabled="loading" class="inline-flex min-h-12 shrink-0 items-center justify-center gap-2 rounded-xl bg-primary px-6 text-sm font-bold text-white shadow-[0_10px_24px_rgba(15,118,110,.22)] transition hover:-translate-y-0.5 hover:brightness-105 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary disabled:pointer-events-none disabled:opacity-60 disabled:transform-none">
                                <svg x-show="!loading" aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                                <svg x-show="loading" aria-hidden="true" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0A12 12 0 0 0 0 12h4Z"/></svg>
                                <span x-text="loading ? 'Mengirim...' : 'Kirim Pesan'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
