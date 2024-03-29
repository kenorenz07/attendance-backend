<?php

namespace Database\Factories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'time_start' => $this->faker->time($format = 'H:i'),
            'time_end' => $this->faker->time($format = 'H:i'),
            'day' => $this->faker->dayOfWeek(),
        ];
    }
}
