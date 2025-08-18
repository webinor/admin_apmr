<?php

namespace Database\Factories\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
           // 'first_name' => "Aubin",
           // 'last_name' => "EKITIKE",
            'code' => Str::random(20),
            'phone_number' => $this->faker->phoneNumber(),
            //'email' => "m.kengne@extract.com",//$this->faker->unique()->safeEmail(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
           // 'password' => Hash::make('Pa$$w0rd!'),
           // 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
           // 'employee_id'
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return  $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
