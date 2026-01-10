<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Anime;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

final class PopulateAnimeSlugs extends Command
{
    protected $signature = 'anime:populate-slugs';

    protected $description = 'Populate slug field for existing anime records';

    public function handle(): int
    {
        $this->info('Populating slugs for existing anime...');

        $animeWithoutSlugs = Anime::whereNull('slug')
            ->orWhere('slug', '')
            ->get();

        if ($animeWithoutSlugs->isEmpty()) {
            $this->info('All anime already have slugs!');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($animeWithoutSlugs->count());
        $bar->start();

        foreach ($animeWithoutSlugs as $anime) {
            $slug = Str::slug($anime->title);
            $originalSlug = $slug;
            $counter = 1;

            // Ensure uniqueness
            while (Anime::where('slug', $slug)->where('id', '!=', $anime->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $anime->update(['slug' => $slug]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully populated {$animeWithoutSlugs->count()} anime slugs!");

        return self::SUCCESS;
    }
}
