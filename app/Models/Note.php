<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'status',
        'categories',
        'priority',
        'photo_path'
    ];

    public function plants(): BelongsToMany
    {
        return $this->belongsToMany(Plant::class, 'note_plant_user', 'note_id', 'plant_id')
            ->withPivot('user_id'); // Opcjonalnie, jeśli przechowujesz informacje o użytkowniku w tabeli pośredniczącej
    }

    public function notification(): HasOne
    {
        return $this->hasOne(Notification::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'note_plant_user', 'note_id', 'user_id')
            ->withPivot('plant_id');
    }
}
