<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Update User Request
 *
 * Represents a request to update the details of a user.
 *
 * @bodyParam name string required The name of the user. Example: "John Doe"
 * @bodyParam species string required The species of the user. Example: "Human"
 * @bodyParam email string required The email address of the user. Example: "john@example.com"
 * @bodyParam password string required The user's password. Example: "secret_password"
 *
 * @authenticated
 *
 * @response 200 {
 *     "message": "User details updated successfully."
 * }
 *
 * @response 401 {
 *     "message": "Unauthorized. You do not have permission to update user details."
 * }
 *
 * @response 422 {
 *     "message": "The given data was invalid.",
 *     "errors": {
 *         "name": ["The name field is required."],
 *         "species": ["The species field is required."],
 *         "email": ["The email field is required."],
 *         "password": ["The password field is required."]
 *     }
 * }
 *
 * @param UpdateUserRequest $request
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->tokenCan('update');
    }

    /**
     * @return string[][]
     */
    public function rules(): array
    {
        $method = $this->method();

        if ($method == 'PUT') {
            return [
                'name' => ['required'],
                'species' => ['required'],
                'password' => ['required']
            ];
        } else {
            return [
                'name' => ['sometimes', 'required'],
                'email' => ['sometimes', 'required'],
                'password' => ['sometimes', 'required']
            ];
        }
    }
}
