<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\Plant;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        // Tworzenie 25 roślin
        $plants = Plant::factory()->count(25)->create();

        foreach ($plants as $plant) {
            // Tworzenie notatki
            $note = Note::factory()->create();

            // Przypisanie użytkownika do notatki
            $user = User::factory()->create();

            // Przypisanie notatki do rośliny poprzez tabelę pośredniczącą
            $plant->notes()->attach($note, ['user_id' => $user->id]);
        }
    }
}
