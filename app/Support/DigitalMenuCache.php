<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

final class DigitalMenuCache
{
    public const KEY = 'digital-menu.catalog.v1';

    public static function flush(): void
    {
        Cache::forget(self::KEY);
    }
}
