<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Api\v1;

use App\Filters\v1\PlantFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\StorePlantRequest;
use App\Http\Requests\v1\UpdatePlantRequest;
use App\Http\Resources\v1\PlantCollection;
use App\Http\Resources\v1\PlantResource;
use App\Models\Plant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

/**
 * @group Plant
 *
 * Api for plants management
 *
 */
class PlantController extends Controller
{
    /**
     * Get all plants
     *
     * Display all plants.To retrieve a list of plants with notes includeNotes parameter in query.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\v1\PlantCollection
     * @apiResourceModel App\Models\Plant
     *
     * @queryParam includeNotes bool Include plants note.
     * @queryParam name[eq] string Enum:test Filter by name. No-example
     *
     * @queryParam name string The name of the plant. Example: "Monstera"
     *
     * @queryParam species string The species of the plant. Example: "Tropical"
     *
     * @queryParam soilType string The type of soil suitable for the plant. Example: "Loamy soil"
     *
     * @queryParam targetHeight string The target height of the plant. Example: "2m"
     * @queryParam targetHeight[lt] string The target height less than. Example: "1.5m"
     * @queryParam targetHeight[gt] string The target height greater than. Example: "3m"
     *
     * @queryParam wateringFrequency string The recommended watering frequency. Example: "Once a week"
     * @queryParam wateringFrequency[lt] string The watering frequency less than. Example: "2 times a week"
     * @queryParam wateringFrequency[gt] string The watering frequency greater than. Example: "Once every two weeks"
     *
     * @queryParam lastWatered string The date when the plant was last watered. Example: "2023-09-25"
     * @queryParam lastWatered[lt] string The last watered date less than. Example: "2023-09-20"
     * @queryParam lastWatered[gt] string The last watered date greater than. Example: "2023-09-30"
     *
     * @queryParam insolation string The required level of sunlight (insolation). Example: "Partial shade"
     *
     * @queryParam cultivationDifficulty string The cultivation difficulty level. Example: "Intermediate"
     * @param Request $request
     * @return PlantCollection
     */
    public function index(Request $request): PlantCollection
    {
        $filter = new PlantFilter();
        $filterItems = $filter->transform($request);  //[['column', 'operator', 'value']]
        var_dump($filterItems);
        $includeNotes = $request->query('includeNotes');

        $plants = Plant::where($filterItems);

        if ($includeNotes) {
            $plants = $plants->with('notes');
        }

        return new PlantCollection($plants->paginate()->appends($request->query()));
    }

    /**
     * Add new plant
     *
     * Add a new plant to the database
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\v1\PlantResource
     * @apiResourceModel App\Models\Plant
     *
     * @param StorePlantRequest $request
     * @return PlantResource|Response
     */
    public function store(StorePlantRequest $request): PlantResource|Response
    {
        return new PlantResource(Plant::create($request->all()));
    }

    /**
     * Get a single plant
     *
     * Display single plant
     *
     * @authenticated
     *
     * @queryParam includeNotes bool Include plants note.
     *
     * @apiResource  App\Http\Resources\v1\PlantResource
     * @apiResourceModel App\Models\Plant
     *
     * @authenticated
     *
     * @param Plant $plant
     * @return PlantResource
     */
    public function show(Plant $plant): PlantResource
    {
        $includeNotes = request()->query('includeNotes');

        if ($includeNotes) {
            return new PlantResource($plant->loadMissing('notes'));
        }

        return new PlantResource($plant);
    }

    /**
     * Update a plant
     *
     * Update the details of a plant.
     *
     * @authenticated
     *
     * @urlParam id required The ID of the plant to update.
     *
     * @apiResourceModel App\Models\Plant
     *
     * @param UpdatePlantRequest $request
     * @param Plant $plant
     */

    public function update(UpdatePlantRequest $request, Plant $plant)
    {
        $plant->update($request->all());
    }

    /**
     * Remove a Plant
     *
     * Removes a specified plant resource from storage.
     *
     * @urlParam id required The ID of the plant to remove.
     *
     * @response 200 {
     *     "message": "Roślina została pomyślnie usunięta."
     * }
     *
     * @response 404 {
     *     "message": "Nie znaleziono rośliny o podanym identyfikatorze."
     * }
     *
     * @authenticated
     *
     * @param Plant $plant The plant to be removed.
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(Plant $plant): JsonResponse
    {
        try {
            $plant->deleteOrFail();
            return response()->json(['message' => 'Roślina została pomyślnie usunięta.']);
        } catch (Throwable) {
            return response()->json(['message' => 'Nie znaleziono rośliny o podanym identyfikatorze.'], 404);
        }
    }
}
