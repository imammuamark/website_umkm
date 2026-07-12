<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            BusinessProfileSeeder::class,
            SiteSettingSeeder::class,
            LocationSeeder::class,
            CatalogSeeder::class,
            MenuProductSeeder::class,
            DigitalMenuSeeder::class,
            PanamaStoryArticleSeeder::class,
            PageSeeder::class,
            MenuItemSeeder::class,
        ]);
    }
}
