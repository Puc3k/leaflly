<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class StorePlantRequest extends FormRequest
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
            'name' => ['required'],
            'species' => ['required'],
            'image' => ['required'],
            'soilType' => ['required'],
            'targetHeight' => ['required'],
            'wateringFrequency' => ['required'],
            'lastWatered' => ['required'],
            'insolation' => ['required'],
            'cultivationDifficulty' => ['required'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'soil_type' => $this->soilType,
            'target_height' => $this->targetHeight,
            'watering_frequency' => $this->wateringFrequency,
            'last_watered' => $this->lastWatered,
            'cultivation_difficulty' => $this->cultivationDifficulty,
        ]);
    }
}
