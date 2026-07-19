<?php

declare(strict_types=1);

namespace App\Modules\Sites\Infrastructure\Models;

use App\Modules\Domains\Infrastructure\Models\Domain;
use App\Modules\Events\Infrastructure\Models\Event;
use App\Modules\Publishing\Infrastructure\Models\SitePublication;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Site extends Model
{
    protected $fillable = ['event_id', 'name', 'slug', 'status', 'theme', 'settings', 'published_at'];

    protected function casts(): array
    {
        return ['theme' => 'array', 'settings' => 'array', 'published_at' => 'datetime'];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(SitePage::class)->orderBy('sort_order');
    }

    public function publications(): HasMany
    {
        return $this->hasMany(SitePublication::class);
    }

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    public function coverImageUrl(): ?string
    {
        $pages = $this->relationLoaded('pages') ? $this->pages : $this->pages()->get();

        foreach ($pages as $page) {
            foreach (['header', 'content', 'footer'] as $zone) {
                foreach ($page->{$zone} ?? [] as $block) {
                    if (($block['type'] ?? null) === 'image' && filled($block['props']['url'] ?? null)) {
                        return $block['props']['url'];
                    }

                    if (($block['type'] ?? null) === 'gallery') {
                        $image = collect($block['props']['images'] ?? [])->first(fn ($url) => filled($url));

                        if ($image) {
                            return $image;
                        }
                    }
                }
            }
        }

        return null;
    }
}
