<?php

namespace App\Rules;

use App\Support\ArticleVideo;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TrustedArticleVideoUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (filled($value) && ArticleVideo::resolve((string) $value) === null) {
            $fail('Gunakan URL HTTPS video YouTube atau Vimeo yang valid.');
        }
    }
}
