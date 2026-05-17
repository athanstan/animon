<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserAnimeLibraryStatus;
use Database\Factories\UserAnimeLibraryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/** @use HasFactory<UserAnimeLibraryFactory> */
final class UserAnimeLibrary extends Pivot
{
    use HasFactory;

    /**
     * @see https://laravel.com/docs/eloquent-relationships#custom-pivot-models-and-incrementing-ids
     */
    public $incrementing = true;

    protected $table = 'user_anime_libraries';

    protected $fillable = [
        'user_id',
        'anime_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => UserAnimeLibraryStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }
}
