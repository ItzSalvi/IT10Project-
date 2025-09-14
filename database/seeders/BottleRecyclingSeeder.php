<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reward;

class BottleRecyclingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            [
                'name' => 'Eco Bag',
                'description' => 'Reusable eco-friendly shopping bag made from recycled materials',
                'points_req' => 100,
                'stock' => 50,
                'status' => true,
            ],
            [
                'name' => 'Water Bottle',
                'description' => 'Stainless steel water bottle to reduce plastic waste',
                'points_req' => 200,
                'stock' => 30,
                'status' => true,
            ],
            [
                'name' => 'Plant Pot',
                'description' => 'Biodegradable plant pot for growing herbs and small plants',
                'points_req' => 150,
                'stock' => 25,
                'status' => true,
            ],
            [
                'name' => 'Coffee Mug',
                'description' => 'Ceramic coffee mug with eco-friendly design',
                'points_req' => 120,
                'stock' => 40,
                'status' => true,
            ],
            [
                'name' => 'Garden Tools Set',
                'description' => 'Complete set of small garden tools for home gardening',
                'points_req' => 300,
                'stock' => 15,
                'status' => true,
            ],
            [
                'name' => 'Seed Packets',
                'description' => 'Assorted seed packets for growing vegetables and herbs',
                'points_req' => 80,
                'stock' => 100,
                'status' => true,
            ],
            [
                'name' => 'Compost Bin',
                'description' => 'Small compost bin for kitchen waste composting',
                'points_req' => 250,
                'stock' => 20,
                'status' => true,
            ],
            [
                'name' => 'Reusable Straws Set',
                'description' => 'Set of 4 stainless steel reusable straws with cleaning brush',
                'points_req' => 90,
                'stock' => 60,
                'status' => true,
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}