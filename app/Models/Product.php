<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category',
        'location',
        'summary',
        'image_url',
        'gallery_images',
        'description',
        'duration',
        'price_myr',
        'malaysia_adult_price_myr',
        'malaysia_child_price_myr',
        'international_adult_price_myr',
        'international_child_price_myr',
        'capacity',
        'is_featured',
        'is_top_choice',
        'is_discounted',
        'discount_percentage',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_myr' => 'decimal:2',
            'gallery_images' => 'array',
            'malaysia_adult_price_myr' => 'decimal:2',
            'malaysia_child_price_myr' => 'decimal:2',
            'international_adult_price_myr' => 'decimal:2',
            'international_child_price_myr' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_top_choice' => 'boolean',
            'is_discounted' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getGalleryUrlsAttribute(): array
    {
        $gallery = collect($this->gallery_images ?? [])
            ->filter(fn ($image) => is_string($image) && filled($image))
            ->values();

        if ($this->image_url && !$gallery->contains($this->image_url)) {
            $gallery->prepend($this->image_url);
        }

        return $gallery->values()->all();
    }
}
