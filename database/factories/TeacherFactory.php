<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class TeacherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "first_name" => $this->faker->firstName(),
            "middle_name" => $this->faker->lastName(),
            "last_name" => $this->faker->lastName(),
            "rfid_number" => $this->faker->unique()->ean8(),
            "email" => $this->faker->unique()->safeEmail(),
            "username" => $this->faker->unique()->userName(),
            "password" =>  Hash::make('123123123'),
        ];
    }
}
