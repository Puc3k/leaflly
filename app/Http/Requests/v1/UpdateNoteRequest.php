<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Update a Note
 *
 * Update an existing note in the system.
 *
 * @authenticated
 *
 * @apiResource App\Http\Resources\v1\NoteResource
 * @apiResourceModel App\Models\Note
 *
 * @bodyParam title string required The title of the note.
 * @bodyParam content string The content of the note.
 * @bodyParam status string required The status of the note (e.g., "aktywna").
 * @bodyParam categories string The categories of the note (e.g., "Problemy i Choroby"). Enum: 'Problemy i Choroby','Podlewanie','Nawożenie','Historia','Inspiracje','Inne'
 * @bodyParam priority integer required The priority of the note (e.g., "1").
 * @bodyParam photoPath string The path to the photo associated with the note. Example: https://www.wisoky.org/at-saepe-voluptatem-nostrum
 *
 **/
class UpdateNoteRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */

    #[ArrayShape(['title' => "string[]", 'content' => "string[]", 'status' => "string[]", 'categories' => "string[]", 'priority' => "string[]", 'photoPath' => "string[]"])] public function rules(): array
    {
        $method = $this->method();

        if ($method == 'PUT') {
            return [
                'title' => ['required'],
                'content' => ['required'],
                'status' => ['required'],
                'categories' => ['required'],
                'priority' => ['required'],
                'photoPath' => ['required']
            ];
        } else {
            return [
                'title' => ['sometimes', 'required'],
                'content' => ['sometimes', 'required'],
                'status' => ['sometimes', 'required'],
                'categories' => ['sometimes', 'required'],
                'priority' => ['sometimes', 'required'],
                'photoPath' => ['sometimes', 'required'],
            ];
        }
    }

    protected function prepareForValidation()
    {
        if ($this->photoPath) {
            $this->merge([
                'photo_path' => $this->photoPath
            ]);
        }
    }
}
