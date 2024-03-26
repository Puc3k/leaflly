<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\Plant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the retrieval of all users.
     *
     * Validates the ability to retrieve all users via API request.
     *
     * @authenticated
     *
     * @apiTest Tests\Feature\UserControllerTest::testCanGetUsers
     */
    public function testCanGetUsers()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/user');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    "emailVerifiedAt",
                    'password',
                    'fcmToken',
                    'rememberToken',
                    'createdAt',
                    'updatedAt'
                ]
            ]
        ]);
    }

    /**
     * Test filtering users by name.
     *
     * Validates the ability to filter users by name via API request.
     *
     * @authenticated
     *
     * @apiTest Tests\Feature\UserControllerTest::testCanFilterUsersByName
     * @queryParam name[eq] string Filter users by exact name. Example: John
     */
    public function testCanFilterUsersByName()
    {
        $user = User::factory()->create([
            'name' => 'John Doe'
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/user?name[eq]John');

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => 'John Doe'
        ]);
    }

    /**
     * Test fetching a user with their relationships.
     *
     * Validates the ability to fetch a user with associated plants and notes via API request.
     *
     * @authenticated
     *
     * @apiTest Tests\Feature\UserControllerTest::testFetchUserWithRelationships
     * @queryParam includePlants bool Include user's associated plants.
     * @queryParam includeNotes bool Include user's associated notes.
     */
    public function testFetchUserWithRelationships()
    {
        $plant = Plant::factory()->create();

        $note = Note::factory()->create();

        $user = User::factory()->create();

        $plant->notes()->attach($note, ['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/v1/user/{$user->id}?includePlants=true&includeNotes=true");

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'plants' => [
                        '*' => [
                            'id',
                        ],
                    ],
                    'notes' => [
                        '*' => [
                            'id',
                        ],
                    ],
                ],
            ]);
    }
}
