<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\EpisodeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/** @use HasFactory<EpisodeFactory> */
final class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'anime_id',
        'number',
        'title',
        'title_japanese',
        'title_romanji',
        'aired',
        'score',
        'filler',
        'recap',
        'url',
        'forum_url',
        'duration',
        'synopsis',
    ];

    protected function casts(): array
    {
        return [
            'number' => 'integer',
            'aired' => 'datetime',
            'score' => 'float',
            'filler' => 'boolean',
            'recap' => 'boolean',
        ];
    }

    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'episode_user')
            ->withPivot('status')
            ->withTimestamps();
    }
}
