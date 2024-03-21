<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Group;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'username' => "Aso A.Sdiq",
            'email' => "aso.sargaty@yahoo.com",
            'phone_number' => "07721234567",
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'password' => '$2y$10$i01ZCM8KnkyVRFZ46ey6f.kl5MWVJZFosBSLHnHRtl2PS0.0Ypq46', // 12345678
            'role' => 'admin'

        ]);
        User::factory(10)->create();
        Brand::factory(10)->create();
        Discount::factory(10)->create();
        Category::factory(10)->create();
        Group::factory(10)->create();
        Product::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
