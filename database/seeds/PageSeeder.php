<?php

namespace Database\Seeders;

use App\Models\AdvertisementPage;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = ['Home', 'Happy stories', 'Active members', 'Premium plans', 'Contact us'];

        foreach ($pages as $page) {
            AdvertisementPage::create(['name' => $page]);
        }
    }
}
