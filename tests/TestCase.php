<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Never allow cached local configuration to redirect tests to the real database.
     */
    public function createApplication()
    {
        $cachedConfig = dirname(__DIR__).'/bootstrap/cache/config.php';

        if (is_file($cachedConfig)) {
            throw new \RuntimeException(
                'Refusing to run tests with cached configuration. Run `php artisan config:clear` first.'
            );
        }

        return parent::createApplication();
    }
}
