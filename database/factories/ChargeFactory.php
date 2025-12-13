<?php

namespace Database\Factories;

use App\Models\Charge;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ChargeFactory extends Factory
{
    protected $model = Charge::class;

    public function definition(): array
    {
        return [
            'apartment_id' => $this->faker->randomNumber(),
            'amount' => $this->faker->randomFloat(),
            'period' => Carbon::now(),
            'comment' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'service_id' => Service::factory(),
        ];
    }
}
