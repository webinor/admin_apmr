<?php

namespace Database\Factories\HumanResource;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'code' => Str::random(20),
            'main_phone_number' => $this->faker->phoneNumber(),
          //  'email' => "a.ekitike@montresorier.com",//$this->faker->unique()->safeEmail(),
          //  'email_verified_at' => now(),
          
           // 'profile_url' => "daniel.jpg",
            'personal_email' => $this->faker->unique()->safeEmail(),
        
              /*  'first_name' => "NANFACK",
            'last_name' => "Ariane",
            //'profile_url' => "daniel.jpg",
            'personal_email' => 'ariit@gmail.com', */
        

        ];
    }
}
