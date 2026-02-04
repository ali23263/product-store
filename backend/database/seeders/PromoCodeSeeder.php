<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PromoCode;
use Carbon\Carbon;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promoCodes = [
            [
                'code' => 'WELCOME10',
                'discount_percent' => 10.00,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(3),
                'usage_limit' => 100,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER20',
                'discount_percent' => 20.00,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(2),
                'usage_limit' => 50,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'MEGA50',
                'discount_percent' => 50.00,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addDays(7),
                'usage_limit' => 10,
                'used_count' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($promoCodes as $promoCode) {
            PromoCode::create($promoCode);
        }

        $this->command->info('Promo codes created successfully!');
        $this->command->info('WELCOME10 - 10% off (100 uses)');
        $this->command->info('SUMMER20 - 20% off (50 uses)');
        $this->command->info('MEGA50 - 50% off (10 uses)');
    }
}
