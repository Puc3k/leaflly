<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
        'image',
        'soil_type',
        'target_height',
        'watering_frequency',
        'last_watered',
        'insolation',
        'cultivation_difficulty',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'note_plant_user', 'plant_id', 'user_id'); // Opcjonalnie, jeśli przechowujesz informacje o notatkach w tabeli pośredniczącej
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class, 'note_plant_user', 'plant_id', 'note_id')
            ->withPivot('user_id'); // Opcjonalnie, jeśli przechowujesz informacje o użytkowniku w tabeli pośredniczącej
    }
}
