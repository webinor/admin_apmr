<?php

namespace Database\Factories\Providers;

use App\Models\Settings\Area;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'social_reason' => $this->faker->company(),
            'code' => Str::random(20),
            'user_id' => 1,
            'address' => $this->faker->address(),
            'email'=>$this->faker->unique()->safeEmail(),
            'main_phone_number'=>$this->faker->unique()->phoneNumber(),
        ];
    }
}

