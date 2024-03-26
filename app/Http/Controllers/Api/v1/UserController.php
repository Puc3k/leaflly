<?php

namespace App\Http\Controllers\Api\v1;

use App\Filters\v1\UserFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\UpdateUserRequest;
use App\Http\Resources\v1\UserCollection;
use App\Http\Resources\v1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * @group User
 *
 * Api for users management
 *
 */
class UserController extends Controller
{
    /**
     * Get all users
     *
     * Display a list of users with optional filtering by various parameters. You can include relationships like plants and notes in the results.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\v1\UserCollection
     * @apiResourceModel App\Models\User
     *
     * @queryParam includePlants bool Include user's plants.
     * @queryParam includeNotes bool Include user's notes.
     *
     * @queryParam name[eq] string Filter users by name. No-example
     * @queryParam email[eq] string Filter users by email address. No-example
     * @queryParam emailVerifiedAt[date][eq] string Filter users by exact email verification date. No-example
     * @queryParam emailVerifiedAt[date][lt] string Filter users by email verification date less than. No-example
     * @queryParam emailVerifiedAt[date][gt] string Filter users by email verification date greater than. No-example
     * @queryParam password[eq] string Filter users by password. No-example
     * @queryParam fcmToken[eq] string Filter users by Firebase Cloud Messaging (FCM) token. No-example
     * @queryParam rememberToken[eq] string Filter users by "remember me" token. No-example
     * @queryParam createdAt[date][eq] string Filter users by exact creation date. No-example
     * @queryParam createdAt[date][lt] string Filter users by creation date less than. No-example
     * @queryParam createdAt[date][gt] string Filter users by creation date greater than. No-example
     * @queryParam updatedAt[date][eq] string Filter users by exact update date. No-example
     * @queryParam updatedAt[date][lt] string Filter users by update date less than. No-example
     * @queryParam updatedAt[date][gt] string Filter users by update date greater than. No-example
     *
     * @param Request $request
     * @return UserCollection
     */

    public function index(Request $request): UserCollection
    {
        $filter = new UserFilter();
        $filterItems = $filter->transform($request);  //[['column', 'operator', 'value']]

        $includePlants = $request->query('includePlants');
        $includeNotes = $request->query('includeNotes');

        $users = User::where($filterItems);

        if ($includePlants) {
            $users->with('plants');
        }

        if ($includeNotes) {
            $users->with('notes');
        }
        return new UserCollection($users->paginate()->appends($request->query()));
    }

    /**
     * Get a single user
     *
     * Display a specific user with optional relationships, such as plants and notes.
     *
     * @authenticated
     *
     * @queryParam includePlants bool Include user's plants.
     * @queryParam includeNotes bool Include user's notes.
     *
     * @apiResource App\Http\Resources\v1\UserResource
     * @apiResourceModel App\Models\User
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        $includePlants = request()->query('includePlants');
        $includeNotes = request()->query('includeNotes');

        if ($includePlants && $includeNotes) {
            $user->load(['plants', 'notes']);
        } elseif ($includePlants) {
            $user->load('plants');
        } elseif ($includeNotes) {
            $user->load('notes');
        }

        return new UserResource($user);
    }

    /**
     * Update a user
     *
     * Update the user's information based on the provided data. The request should be authorized with a valid token that has the "update" scope.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\v1\UserResource
     * @apiResourceModel App\Models\User
     *
     * @param UpdateUserRequest $request
     * @param User $user
     */

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
    }

    /**
     * Remove a user
     *
     * Removes a specified user from the database. To delete a user, provide the user's ID in the URL.
     *
     * @authenticated
     *
     * @response 200 {"message": "Użytkownik został pomyślnie usunięty."}
     * @response 404 {"message": "Nie znaleziono użytkownika o podanym identyfikatorze."}
     * @response 500 {"message": "Wystąpił błąd serwera."}
     *
     * @param User $user
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->deleteOrFail();
            return response()->json(['message' => 'Użytkownik został pomyślnie usunięty.']);
        } catch (Throwable) {
            return response()->json(['message' => 'Nie znaleziono użytkownika o podanym identyfikatorze.'], 404);
        }
    }
}
