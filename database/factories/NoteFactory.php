<?php

namespace Database\Factories;

use App\Models\Note;
use App\Models\Plant;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory
 */
class NoteFactory extends Factory
{
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    #[ArrayShape(['title' => "string", 'content' => "string", 'status' => "mixed", 'categories' => "mixed", 'priority' => "int", 'photo_path' => "string", 'plant_id' => "mixed"])] public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'content' => $this->faker->optional(0.9)->sentence(),
            'status' => $this->faker->randomElement(['aktywna', 'archiwalna', 'ważna']),
            'categories' => $this->faker->optional(0.7)->randomElement(['Problemy i Choroby', 'Podlewanie', 'Nawożenie', 'Historia', 'Inspiracje', 'Inne']),
            'priority' => $this->faker->numberBetween([1, 6]),
            'photo_path' => $this->faker->optional()->url()
        ];
    }
}
