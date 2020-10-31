<?php

namespace Database\Factories;

use App\Models\Word;
use Illuminate\Database\Eloquent\Factories\Factory;

class WordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Word::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'value' => $this->faker->word,
            'song_id' => 1,
            'start_index' => $this->faker->numberBetween(1, 1000),
            'position' => $this->faker->randomElement(['start', 'middle', 'end']),
            'stanza' => $this->faker->numberBetween(1, 4),
        ];
    }
}
