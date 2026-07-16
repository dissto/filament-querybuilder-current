<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $brands = Brand::factory(3)->create();

        // 10 with a brand
        Product::factory(10)->state(fn () => ['brand_id' => $brands->random()->getKey()])->create();

        // 10 without a brand
        Product::factory(10)->create();
    }
}
