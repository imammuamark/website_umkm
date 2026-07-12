<?php

namespace App\Support;

use App\Models\BusinessProfile;
use App\Models\MenuItem;
use App\Models\SiteSetting;
use Illuminate\Support\Str;

class FooterPresenter
{
    /** @return array<string, mixed> */
    public function data(): array
    {
        $profile = BusinessProfile::query()->with('media')->first();
        $businessName = trim((string) ($profile?->business_name ?: config('app.name')));
        $year = now()->year;

        return [
            'businessName' => $businessName,
            'logoUrl' => $profile?->getFirstMediaUrl('logo') ?: null,
            'description' => trim((string) SiteSetting::get('footer_description', 'Tempat menikmati pilihan makanan, camilan, dan minuman dalam suasana yang nyaman.')),
            'showSocials' => $this->boolean('footer_show_socials', true),
            'socials' => $this->socials(),
            'showNavigation' => $this->boolean('footer_show_navigation', true),
            'navigationTitle' => $this->title('footer_navigation_title', 'Navigasi'),
            'navigation' => $this->navigation(),
            'showLegal' => $this->boolean('footer_show_legal', true),
            'legalTitle' => $this->title('footer_legal_title', 'Informasi Legal'),
            'legalDocuments' => $this->legalDocuments($profile),
            'showContact' => $this->boolean('footer_show_contact', true),
            'contactTitle' => $this->title('footer_contact_title', 'Kontak'),
            'email' => $this->email(),
            'phone' => $this->phone(),
            'phoneHref' => $this->phoneHref(),
            'address' => trim((string) SiteSetting::get('footer_address', '')),
            'cta' => $this->cta(),
            'copyright' => Str::of((string) SiteSetting::get('footer_copyright', '© {year} {business_name}. Hak cipta dilindungi.'))
                ->replace(['{year}', '{business_name}'], [(string) $year, $businessName])
                ->limit(240, '')
                ->toString(),
        ];
    }

    /** @return list<array{label: string, url: string}> */
    private function socials(): array
    {
        return collect([
            'Instagram' => SiteSetting::get('instagram_url'),
            'Facebook' => SiteSetting::get('facebook_url'),
            'TikTok' => SiteSetting::get('tiktok_url'),
        ])->map(fn (mixed $url, string $label): ?array => ($safe = $this->safeUrl($url, false)) ? compact('label') + ['url' => $safe] : null)
            ->filter()
            ->values()
            ->all();
    }

    /** @return list<array{label: string, url: string}> */
    private function navigation(): array
    {
        return MenuItem::query()
            ->where('is_active', true)
            ->with('page:id,title,slug,status')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->limit(8)
            ->get()
            ->filter(fn (MenuItem $item): bool => $item->type !== 'page' || $item->page?->status === 'published')
            ->map(fn (MenuItem $item): array => ['label' => Str::limit(trim($item->label), 80, ''), 'url' => $item->getUrl()])
            ->filter(fn (array $item): bool => $item['label'] !== '' && $item['url'] !== '#')
            ->values()
            ->all();
    }

    /** @return list<array{name: string, number: string}> */
    private function legalDocuments(?BusinessProfile $profile): array
    {
        $limit = min(6, max(1, (int) SiteSetting::get('footer_legal_limit', 3)));

        return collect($profile?->legal_docs ?? [])
            ->filter(fn (mixed $document): bool => is_array($document) && filled(data_get($document, 'name')))
            ->map(fn (array $document): array => [
                'name' => Str::limit(trim((string) data_get($document, 'name')), 80, ''),
                'number' => Str::limit(trim((string) data_get($document, 'number', '')), 100, ''),
            ])
            ->take($limit)
            ->values()
            ->all();
    }

    /** @return array{enabled: bool, title: string, description: string, buttonLabel: string, buttonUrl: ?string} */
    private function cta(): array
    {
        return [
            'enabled' => $this->boolean('footer_cta_enabled', false),
            'title' => $this->title('footer_cta_title', 'Ada yang ingin ditanyakan?'),
            'description' => Str::limit(trim((string) SiteSetting::get('footer_cta_description', 'Hubungi tim kami untuk informasi menu dan pemesanan.')), 240, ''),
            'buttonLabel' => $this->title('footer_cta_button_label', 'Hubungi Kami'),
            'buttonUrl' => $this->safeUrl(SiteSetting::get('footer_cta_button_url', '/kontak'), true),
        ];
    }

    private function email(): ?string
    {
        $email = trim((string) SiteSetting::get('email_address', ''));

        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }

    private function phone(): ?string
    {
        $phone = trim((string) SiteSetting::get('office_phone', ''));

        return $phone !== '' && preg_match('/^[0-9+() .-]{6,30}$/', $phone) ? $phone : null;
    }

    private function phoneHref(): ?string
    {
        if (! $phone = $this->phone()) {
            return null;
        }

        $digits = (string) preg_replace('/\D+/', '', $phone);

        return str_starts_with($phone, '+') ? '+'.$digits : $digits;
    }

    private function title(string $key, string $default): string
    {
        return Str::limit(trim((string) SiteSetting::get($key, $default)), 80, '');
    }

    private function boolean(string $key, bool $default): bool
    {
        return filter_var(SiteSetting::get($key, $default), FILTER_VALIDATE_BOOL);
    }

    private function safeUrl(mixed $url, bool $allowRelative): ?string
    {
        if (! is_string($url)) {
            return null;
        }

        $url = trim($url);
        if ($allowRelative && str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return $url;
        }

        return filter_var($url, FILTER_VALIDATE_URL) && strtolower((string) parse_url($url, PHP_URL_SCHEME)) === 'https'
            ? $url
            : null;
    }
}
