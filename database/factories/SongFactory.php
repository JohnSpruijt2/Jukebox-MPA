<?php

namespace Database\Factories;

use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SongFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Song::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(10),
            'artist' => $this->faker->name(),
            'duration' => rand(44, 360), //random duration between 2 numbers that are sort of reasonable
            'genre_id' => rand(1, 15), //was unable to link the genre table to this so random number will be how its done
        ];
    }
}
