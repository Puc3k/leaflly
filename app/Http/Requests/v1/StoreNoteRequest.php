<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->tokenCan('create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'content' => ['nullable'],
            'status' => ['required'],
            'categories' => ['nullable'],
            'priority' => ['required'],
            'photoPath' => ['nullable']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'photo_path' => $this->photoPath
        ]);
    }
}
