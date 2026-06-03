<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'credits',
        'excerpt',
        'content',
        'cover_image_path',
        'social_media_url',
        'video_url',
        'published_at',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (BlogPost $blogPost) {
            if (blank($blogPost->slug) && filled($blogPost->title)) {
                $blogPost->slug = static::generateUniqueSlug($blogPost->title, $blogPost->getKey());
            }
        });
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image_path ? asset('storage/'.$this->cover_image_path) : null;
    }

    public function getDescriptionAttribute($value): ?string
    {
        if (filled($value)) {
            return $value;
        }

        return $this->attributes['content'] ?? $this->attributes['excerpt'] ?? null;
    }

    public function getCreditsAttribute($value): ?string
    {
        return filled($value) ? $value : null;
    }

    public function getExcerptAttribute($value): ?string
    {
        if (filled($value)) {
            return $value;
        }

        return $this->attributes['description'] ?? null;
    }

    public function getContentAttribute($value): ?string
    {
        if (filled($value)) {
            return $value;
        }

        return $this->attributes['description'] ?? null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (! is_string($this->video_url) || trim($this->video_url) === '') {
            return null;
        }

        $videoUrl = trim($this->video_url);
        $parts = parse_url($videoUrl);

        if (! is_array($parts)) {
            return null;
        }

        $host = strtolower($parts['host'] ?? '');
        $path = $parts['path'] ?? '';
        parse_str($parts['query'] ?? '', $query);

        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $videoId = trim($path, '/');

            return $videoId !== '' ? 'https://www.youtube.com/embed/'.$videoId : null;
        }

        if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
            if (($path === '/watch' || $path === '/watch/') && filled($query['v'] ?? null)) {
                return 'https://www.youtube.com/embed/'.$query['v'];
            }

            if (str_starts_with($path, '/embed/')) {
                return $videoUrl;
            }

            if (str_starts_with($path, '/shorts/')) {
                $videoId = basename($path);

                return $videoId !== '' ? 'https://www.youtube.com/embed/'.$videoId : null;
            }
        }

        if (in_array($host, ['vimeo.com', 'www.vimeo.com', 'player.vimeo.com'], true)) {
            $videoId = trim($path, '/');

            if (str_starts_with($path, '/video/')) {
                $videoId = basename($path);
            }

            return $videoId !== '' ? 'https://player.vimeo.com/video/'.$videoId : null;
        }

        return null;
    }

    public function hasEmbeddableVideo(): bool
    {
        return filled($this->video_embed_url);
    }

    public function hasDirectVideoFile(): bool
    {
        if (! is_string($this->video_url) || trim($this->video_url) === '') {
            return false;
        }

        return preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $this->video_url) === 1;
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title) ?: 'blog-post';

        if (! Schema::hasTable('blog_posts')) {
            return $baseSlug;
        }

        $slug = $baseSlug;
        $suffix = 2;

        while (static::query()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
