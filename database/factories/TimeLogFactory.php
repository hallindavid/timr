<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\TimeLog>
 */
class TimeLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $started_at = $this->faker->dateTimeBetween('-2 years', 'now');
        $ended_at = Carbon::parse($started_at)->addMinutes((rand(1, 32) * 15));

        return [
            'started_at' => $started_at,
            'ended_at' => $ended_at,
            'notes' => $this->faker->sentence,
        ];
    }
}
