<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            Product::query()->get()->each->delete();
            ProductCategory::query()->delete();

            $categories = collect([
                ['slug' => 'makanan', 'name' => 'Makanan', 'description' => 'Pilihan menu makan dengan porsi praktis untuk dinikmati di tempat maupun dibawa pulang.'],
                ['slug' => 'snack', 'name' => 'Snack', 'description' => 'Pilihan camilan untuk dinikmati sendiri atau dibagi bersama.'],
                ['slug' => 'minuman', 'name' => 'Minuman', 'description' => 'Ragam minuman kopi dan nonkopi yang tersedia dalam pilihan penyajian sesuai menu.'],
            ])->mapWithKeys(function (array $category): array {
                $model = ProductCategory::create($category);

                return [$category['slug'] => $model];
            });

            foreach ($this->products() as $product) {
                Product::create([
                    'category_id' => $categories[$product['category']]->id,
                    'name' => $product['name'],
                    'slug' => $product['slug'],
                    'description' => '<p>'.$product['description'].'</p>',
                    'image_url' => $product['image_url'],
                    'price' => $product['price'],
                    'stock_status' => 'tersedia',
                    'is_featured' => in_array($product['slug'], ['es-kopi-gula-aren', 'ayam-bakar', 'mix-platter'], true),
                    'is_bestseller' => false,
                    'views_count' => 0,
                ]);
            }
        });
    }

    /** @return list<array{slug: string, name: string, description: string, price: int, category: string, image_url: string}> */
    private function products(): array
    {
        $base = 'https://panama-menu.onrender.com';

        return [
            ['slug' => 'air-es', 'name' => 'Air Es', 'description' => 'Air minum dingin dengan tambahan es, pilihan sederhana untuk menemani hidangan Anda.', 'price' => 2000, 'category' => 'minuman', 'image_url' => "$base/assets/menu/minuman-air-es.jpeg"],
            ['slug' => 'es-cokelat', 'name' => 'Es Cokelat', 'description' => 'Minuman cokelat dingin dengan karakter rasa manis dan lembut, disajikan bersama es.', 'price' => 8000, 'category' => 'minuman', 'image_url' => "$base/assets/menu/minuman-es-cokelat.jpeg"],
            ['slug' => 'es-kopi-gula-aren', 'name' => 'Es Kopi Gula Aren', 'description' => 'Perpaduan kopi dan gula aren yang disajikan dingin untuk menghasilkan rasa manis yang seimbang.', 'price' => 10000, 'category' => 'minuman', 'image_url' => "$base/assets/menu/minuman-kopi-gula-aren.jpeg"],
            ['slug' => 'es-kopi-hazelnut', 'name' => 'Es Kopi Hazelnut', 'description' => 'Kopi dingin dengan sentuhan aroma hazelnut, cocok bagi penikmat kopi bercita rasa manis.', 'price' => 10000, 'category' => 'minuman', 'image_url' => "$base/assets/menu/minuman-kopi-hazelnut.jpeg"],
            ['slug' => 'good-day', 'name' => 'Good Day', 'description' => 'Minuman kopi Good Day yang disajikan sebagai pilihan praktis.', 'price' => 5000, 'category' => 'minuman', 'image_url' => "$base/assets/menu/minuman-good-day.png"],
            ['slug' => 'jeruk-hangat-es', 'name' => 'Jeruk Hangat / Es', 'description' => 'Minuman rasa jeruk yang dapat dipilih dalam penyajian hangat atau dingin sesuai selera.', 'price' => 6000, 'category' => 'minuman', 'image_url' => "$base/assets/menu/minuman-jeruk.png"],
            ['slug' => 'kopi-hitam-panas', 'name' => 'Kopi Hitam Panas', 'description' => 'Kopi hitam yang disajikan panas dengan profil rasa sederhana dan familiar.', 'price' => 5000, 'category' => 'minuman', 'image_url' => "$base/assets/menu/minuman-kopi-hitam.jpeg"],
            ['slug' => 'lemon-tea', 'name' => 'Lemon Tea', 'description' => 'Perpaduan teh dan rasa lemon dengan karakter ringan, disajikan sebagai minuman yang menyegarkan.', 'price' => 8000, 'category' => 'minuman', 'image_url' => "$base/assets/menu/minuman-lemon-tea.jpeg"],
            ['slug' => 'milo', 'name' => 'Milo', 'description' => 'Minuman cokelat malt Milo dengan rasa manis yang familiar dan mudah dinikmati.', 'price' => 5000, 'category' => 'minuman', 'image_url' => "$base/assets/menu/minuman-milo.jpeg"],
            ['slug' => 'nutrisari', 'name' => 'Nutrisari', 'description' => 'Minuman rasa buah Nutrisari yang disajikan sebagai pilihan ringan dan praktis.', 'price' => 4000, 'category' => 'minuman', 'image_url' => "$base/assets/menu/minuman-nutrisari.jpeg"],
            ['slug' => 'taro-red-velvet-ice', 'name' => 'Taro / Red Velvet Ice', 'description' => 'Pilihan minuman dingin rasa taro atau red velvet dengan karakter manis dan lembut.', 'price' => 8000, 'category' => 'minuman', 'image_url' => "$base/assets/menu/minuman-taro-redvelvet.png"],
            ['slug' => 'teh-hangat-es', 'name' => 'Teh Hangat / Es', 'description' => 'Teh yang tersedia dalam pilihan penyajian hangat atau dingin untuk melengkapi waktu makan.', 'price' => 4000, 'category' => 'minuman', 'image_url' => "$base/assets/menu/minuman-teh.jpeg"],
            ['slug' => 'ayam-bakar', 'name' => 'Ayam Bakar', 'description' => 'Ayam bakar yang disajikan bersama tahu, tempe, dan sambal sebagai satu paket hidangan.', 'price' => 18000, 'category' => 'makanan', 'image_url' => "$base/assets/menu/split-image-10.jpg"],
            ['slug' => 'ayam-geprek', 'name' => 'Ayam Geprek', 'description' => 'Ayam renyah dengan sambal pilihan geprek atau matah, disajikan untuk pengalaman makan yang praktis.', 'price' => 15000, 'category' => 'makanan', 'image_url' => "$base/assets/menu/split-image-11.jpg"],
            ['slug' => 'ayam-goreng', 'name' => 'Ayam Goreng', 'description' => 'Ayam goreng yang disajikan bersama tahu, tempe, dan sambal dalam satu porsi.', 'price' => 18000, 'category' => 'makanan', 'image_url' => "$base/assets/menu/split-image-12.jpg"],
            ['slug' => 'ayam-rempah', 'name' => 'Ayam Rempah', 'description' => 'Olahan ayam rempah yang dilengkapi tahu, tempe, dan sambal untuk sajian yang lebih lengkap.', 'price' => 18000, 'category' => 'makanan', 'image_url' => "$base/assets/menu/split-image-12.jpg"],
            ['slug' => 'extra-telur', 'name' => 'Extra Telur', 'description' => 'Tambahan telur yang dapat dipadukan dengan menu mie kuah atau mie goreng.', 'price' => 4000, 'category' => 'makanan', 'image_url' => "$base/assets/menu/ekstra_telur.jpeg"],
            ['slug' => 'mie-goreng', 'name' => 'Mie Goreng', 'description' => 'Mie goreng dengan rasa gurih yang disiapkan sebagai pilihan makan sederhana dan praktis.', 'price' => 7000, 'category' => 'makanan', 'image_url' => "$base/assets/menu/split-image-6.jpg"],
            ['slug' => 'mie-kuah', 'name' => 'Mie Kuah', 'description' => 'Mie berkuah hangat dengan penyajian sederhana untuk dinikmati kapan saja.', 'price' => 7000, 'category' => 'makanan', 'image_url' => "$base/assets/menu/split-image-5.jpg"],
            ['slug' => 'nasi-goreng', 'name' => 'Nasi Goreng', 'description' => 'Nasi goreng yang disajikan bersama telur sebagai pilihan menu makan yang praktis.', 'price' => 12000, 'category' => 'makanan', 'image_url' => "$base/assets/menu/split-image-9.jpg"],
            ['slug' => 'nasi-putih', 'name' => 'Nasi Putih', 'description' => 'Nasi putih hangat yang dapat dipesan sebagai pelengkap berbagai menu utama.', 'price' => 4000, 'category' => 'makanan', 'image_url' => "$base/assets/menu/split-image-7.jpg"],
            ['slug' => 'nasi-telor', 'name' => 'Nasi Telor', 'description' => 'Nasi putih dengan telur dan sambal, disajikan sebagai menu sederhana untuk makan sehari-hari.', 'price' => 11000, 'category' => 'makanan', 'image_url' => "$base/assets/menu/split-image-8.jpg"],
            ['slug' => 'rice-bowl-ayam-kecap', 'name' => 'Rice Bowl Ayam Kecap', 'description' => 'Nasi dalam kemasan bowl dengan olahan ayam kecap, disiapkan sebagai pilihan makan yang ringkas.', 'price' => 11000, 'category' => 'makanan', 'image_url' => "$base/uploads/menu/9fc55e88-29a7-4a03-980a-0d3447a70646.webp"],
            ['slug' => 'rice-bowl-campur', 'name' => 'Rice Bowl Campur', 'description' => 'Nasi dalam kemasan bowl dengan kombinasi lauk, cocok untuk pilihan makan yang praktis.', 'price' => 11000, 'category' => 'makanan', 'image_url' => "$base/uploads/menu/abddfa9a-fbb3-44f6-b38a-7559beffd607.webp"],
            ['slug' => 'rice-bowl-chicken-katsu', 'name' => 'Rice Bowl Chicken Katsu', 'description' => 'Nasi dalam kemasan bowl dengan chicken katsu sebagai lauk utama.', 'price' => 11000, 'category' => 'makanan', 'image_url' => "$base/uploads/menu/9d2c43d3-eabd-465f-b810-bb6dbc150e8c.webp"],
            ['slug' => 'rice-bowl-daun-jeruk-ayam-sambal-matah', 'name' => 'Rice Bowl Daun Jeruk Ayam Sambal Matah', 'description' => 'Nasi dalam kemasan bowl dengan olahan ayam bercita rasa daun jeruk dan sambal matah.', 'price' => 11000, 'category' => 'makanan', 'image_url' => "$base/uploads/menu/cc6d9bf0-259f-4747-bd4d-223aa69c9619.webp"],
            ['slug' => 'rice-bowl-teriyaki', 'name' => 'Rice Bowl Teriyaki', 'description' => 'Nasi dalam kemasan bowl dengan lauk bercita rasa teriyaki untuk pilihan makan yang ringkas.', 'price' => 11000, 'category' => 'makanan', 'image_url' => "$base/uploads/menu/48a41212-0fda-463b-a2ab-ddc208043928.webp"],
            ['slug' => 'kentang-goreng', 'name' => 'Kentang Goreng', 'description' => 'Potongan kentang goreng yang disajikan hangat sebagai camilan untuk dinikmati sendiri atau bersama.', 'price' => 12000, 'category' => 'snack', 'image_url' => "$base/assets/menu/split-image-1.jpeg"],
            ['slug' => 'mix-platter', 'name' => 'Mix Platter', 'description' => 'Paket berisi kombinasi camilan untuk dinikmati bersama saat bersantai.', 'price' => 20000, 'category' => 'snack', 'image_url' => "$base/assets/menu/split-image-4.jpg"],
            ['slug' => 'nugget-goreng', 'name' => 'Nugget Goreng', 'description' => 'Nugget yang digoreng dan disajikan hangat sebagai pilihan camilan sederhana.', 'price' => 7000, 'category' => 'snack', 'image_url' => "$base/assets/menu/split-image-3.jpeg"],
            ['slug' => 'sosis-goreng', 'name' => 'Sosis Goreng', 'description' => 'Sosis goreng yang disajikan hangat dengan karakter rasa gurih.', 'price' => 7000, 'category' => 'snack', 'image_url' => "$base/assets/menu/split-image-2.jpeg"],
        ];
    }
}
