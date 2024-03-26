<?php

namespace App\Http\Controllers\Api\v1;

use App\Filters\v1\NoteFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\StoreNoteRequest;
use App\Http\Requests\v1\UpdateNoteRequest;
use App\Http\Resources\v1\NoteCollection;
use App\Http\Resources\v1\NoteResource;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * @group Note
 *
 * Api for notes management
 *
 */
class NoteController extends Controller
{
    /**
     * List Notes
     *
     * Get a paginated list of notes.
     *
     * @queryParam name string Filter by note name. Example: "Important Note"
     * @queryParam content string Filter by note content. Example: "This is a test note."
     * @queryParam status string Filter by note status. Example: "active"
     * @queryParam categories string Filter by note categories. Example: "Work"
     * @queryParam priority int Filter by note priority. Example: 3
     * @queryParam photo_path string Filter by note photo path. Example: "https://example.com/note.jpg"
     * @queryParam includePlants boolean Include associated plants in the response. Example: true
     *
     * @param Request $request
     * @return NoteCollection
     */

    public function index(Request $request): NoteCollection
    {
        $filter = new NoteFilter();

        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]

        $includePlants = $request->query('includePlants');

        $notes = Note::where($filterItems);

        if ($includePlants) {
            $notes->with('plants');
        }

        return new NoteCollection($notes->paginate()->appends($request->query()));
    }

    /**
     * Show a single note.
     *
     * Display the details of a note.
     *
     * @queryParam includePlant bool Include the associated plant information.
     *
     * @param Note $note
     * @return NoteResource
     */

    public function show(Note $note): NoteResource
    {
        $includePlant = request()->query('includePlant');

        if ($includePlant) {
            return new NoteResource($note->loadMissing('plants'));
        }

        return new NoteResource($note);
    }

    /**
     * Store a New Note
     *
     * Create a new note in the system.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\v1\NoteResource
     * @apiResourceModel App\Models\Note
     *
     * @param StoreNoteRequest $request
     * @return NoteResource
     */

    public function store(StoreNoteRequest $request): NoteResource
    {
        return new NoteResource(Note::create($request->all()));
    }

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
     * @urlParam id required The ID of the note to update. Example: 1
     *
     * @param UpdateNoteRequest $request
     * @param Note $note
     * @return NoteResource
     */

    public function update(UpdateNoteRequest $request, Note $note): NoteResource
    {
        $note->update($request->all());

        return new NoteResource($note);
    }

    /**
     * Delete a Note
     *
     * Delete an existing note from the system.
     *
     * @authenticated
     *
     * @urlParam id required The ID of the note to delete. Example: 2
     *
     * @response status=200 {"message": "Roślina została pomyślnie usunięta."}
     * @response status=404 {"message": "Nie znaleziono notatki o podanym identyfikatorze."}
     *
     * @param Note $note The note to delete.
     * @return JsonResponse
     */
    public function destroy(Note $note): JsonResponse
    {
        try {
            $note->deleteOrFail();
            return response()->json(['message' => 'Roślina została pomyślnie usunięta.']);
        } catch (Throwable) {
            return response()->json(['message' => 'Nie znaleziono rośliny o podanym identyfikatorze.'], 404);
        }
    }
}
