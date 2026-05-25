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
        'itinerary_items',
        'service_inclusions',
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
            'itinerary_items' => 'array',
            'service_inclusions' => 'array',
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

    public function getHasActiveDiscountAttribute(): bool
    {
        return $this->is_discounted
            && $this->discount_percentage !== null
            && (float) $this->discount_percentage > 0;
    }

    public function getDiscountedMalaysiaAdultPriceMyrAttribute(): float
    {
        return $this->calculateDiscountedPrice($this->malaysia_adult_price_myr);
    }

    public function getDiscountedMalaysiaChildPriceMyrAttribute(): float
    {
        return $this->calculateDiscountedPrice($this->malaysia_child_price_myr);
    }

    public function getDiscountedInternationalAdultPriceMyrAttribute(): float
    {
        return $this->calculateDiscountedPrice($this->international_adult_price_myr);
    }

    public function getDiscountedInternationalChildPriceMyrAttribute(): float
    {
        return $this->calculateDiscountedPrice($this->international_child_price_myr);
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

    private function calculateDiscountedPrice(mixed $price): float
    {
        $basePrice = round((float) $price, 2);

        if (! $this->has_active_discount) {
            return $basePrice;
        }

        $discountMultiplier = max(0, 1 - (((float) $this->discount_percentage) / 100));

        return round($basePrice * $discountMultiplier, 2);
    }
}
