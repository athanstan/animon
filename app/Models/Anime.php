<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AnimeRating;
use App\Enums\AnimeSeason;
use App\Enums\AnimeStatus;
use App\Enums\AnimeType;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

final class Anime extends Model
{
    use Sluggable;

    protected $fillable = [
        'mal_id',
        'slug',
        'title',
        'title_english',
        'title_japanese',
        'image_url',
        'episodes',
        'status',
        'type',
        'season',
        'rating',
        'score',
        'synopsis',
        'year',
    ];

    protected function casts(): array
    {
        return [
            'mal_id' => 'integer',
            'episodes' => 'integer',
            'score' => 'float',
            'year' => 'integer',
            'type' => AnimeType::class,
            'status' => AnimeStatus::class,
            'season' => AnimeSeason::class,
            'rating' => AnimeRating::class,
        ];
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function animeLists(): BelongsToMany
    {
        return $this->belongsToMany(AnimeList::class, 'anime_anime_lists')
            ->withPivot('order')
            ->orderBy('order');
    }
}
