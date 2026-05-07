<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EpisodeStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EpisodeUser extends Model
{
    protected $table = 'episode_user';

    protected $fillable = [
        'episode_id',
        'user_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => EpisodeStatus::class,
        ];
    }

    public function episode(): BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
