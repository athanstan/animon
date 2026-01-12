<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Visibility;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class AnimeList extends Model
{
    /** @use HasFactory<\Database\Factories\AnimeListFactory> */
    use HasFactory, Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'user_id',
        'visibility',
    ];

    protected function casts(): array
    {
        return [
            'visibility' => Visibility::class,
        ];
    }

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function animes(): BelongsToMany
    {
        return $this->belongsToMany(Anime::class, 'anime_anime_lists')
            ->withPivot('order')
            ->orderBy('order');
    }
}
