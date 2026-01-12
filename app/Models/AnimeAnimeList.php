<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AnimeAnimeList extends Model
{
    protected $fillable = [
        'anime_id',
        'anime_list_id',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }

    public function animeList(): BelongsTo
    {
        return $this->belongsTo(AnimeList::class);
    }
}
