<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\User;
use App\Support\DigitalMenuCache;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(fn () => Password::min(12)->mixedCase()->numbers()->symbols());

        RateLimiter::for('contact', function (Request $request): array {
            $email = mb_strtolower(trim((string) $request->input('email')));

            return [
                Limit::perMinute(5)->by('contact-ip:'.$request->ip()),
                Limit::perHour(10)->by('contact-email:'.hash('sha256', $email)),
            ];
        });

        RateLimiter::for('digital-menu', fn (Request $request): Limit => Limit::perMinute(180)->by($request->ip()));

        Media::saved(function (Media $media): void {
            if ($media->model_type === Product::class) {
                DigitalMenuCache::flush();
            }
        });
        Media::deleted(function (Media $media): void {
            if ($media->model_type === Product::class) {
                DigitalMenuCache::flush();
            }
        });

        // Implicitly grant "Super Admin" role all permissions
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
