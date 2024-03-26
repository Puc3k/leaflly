<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Update Plant Request
 *
 * Represents a request to update the details of a plant.
 *
 * @bodyParam name string required The name of the plant. Example: "Monstera deliciosa"
 * @bodyParam species string required The species of the plant. Example: "Tropical"
 * @bodyParam image string required The URL of the plant's image. Example: "https://example.com/plant.jpg"
 * @bodyParam soilType string required The type of soil suitable for the plant. Example: "Loamy soil"
 * @bodyParam targetHeight integer required The target height of the plant. Example: "2m"
 * @bodyParam wateringFrequency string required The recommended watering frequency. Example: "Once a week"
 * @bodyParam lastWatered string required The date when the plant was last watered. Example: "2023-09-25"
 * @bodyParam insolation string required The required level of sunlight (insolation). Example: "Partial shade"
 * @bodyParam cultivationDifficulty string required The cultivation difficulty level. Example: "Intermediate"
 *
 * @authenticated
 *
 * @param UpdatePlantRequest $request
 * @return array<string, mixed>
 */
class UpdatePlantRequest extends FormRequest
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
     * @return array<string, mixed>
     */

    #[ArrayShape(['name' => "string[]", 'species' => "string[]", 'image' => "string[]", 'soilType' => "string[]", 'targetHeight' => "string[]", 'wateringFrequency' => "string[]", 'lastWatered' => "string[]", 'insolation' => "string[]", 'cultivationDifficulty' => "string[]"])] public function rules(): array
    {
        $method = $this->method();

        if ($method == 'PUT') {
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
        } else {
            return [
                'name' => ['sometimes', 'required'],
                'species' => ['sometimes', 'required'],
                'image' => ['sometimes', 'required'],
                'soilType' => ['sometimes', 'required'],
                'targetHeight' => ['sometimes', 'required'],
                'wateringFrequency' => ['sometimes', 'required'],
                'lastWatered' => ['sometimes', 'required'],
                'insolation' => ['sometimes', 'required'],
                'cultivationDifficulty' => ['sometimes', 'required'],
            ];
        }
    }

    protected function prepareForValidation()
    {
        if ($this->soilType) {
            $this->merge([
                'soil_type' => $this->soilType
            ]);
        }
        if ($this->targetHeight) {
            $this->merge([
                'target_height' => $this->targetHeight
            ]);
        }
        if ($this->wateringFrequency) {
            $this->merge([
                'watering_frequency' => $this->wateringFrequency
            ]);
        }
        if ($this->lastWatered) {
            $this->merge([
                'last_watered' => $this->lastWatered,
            ]);
        }
        if ($this->cultivationDifficulty) {
            $this->merge([
                'cultivation_difficulty' => $this->cultivationDifficulty
            ]);
        }
    }
}
