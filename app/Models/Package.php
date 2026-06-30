<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $appends = [
        'category',
        'has_active_discount',
        'discounted_malaysia_adult_price_myr',
        'discounted_malaysia_child_price_myr',
        'discounted_international_adult_price_myr',
        'discounted_international_child_price_myr',
        'gallery_urls',
    ];

    protected $fillable = [
        'name',
        'tour_code',
        'location',
        'summary',
        'description',
        'duration',
        'departure_time',
        'pickup_location',
        'dropoff_location',
        'group_size_label',
        'minimum_age',
        'price_myr',
        'malaysia_adult_price_myr',
        'malaysia_child_price_myr',
        'international_adult_price_myr',
        'international_child_price_myr',
        'capacity',
        'image_url',
        'gallery_images',
        'pricing_tiers',
        'itinerary_items',
        'service_inclusions',
        'tour_highlights',
        'package_details',
        'recommended_attire',
        'things_to_know',
        'travel_tips',
        'optional_activities',
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
            'malaysia_adult_price_myr' => 'decimal:2',
            'malaysia_child_price_myr' => 'decimal:2',
            'international_adult_price_myr' => 'decimal:2',
            'international_child_price_myr' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'gallery_images' => 'array',
            'pricing_tiers' => 'array',
            'itinerary_items' => 'array',
            'service_inclusions' => 'array',
            'tour_highlights' => 'array',
            'package_details' => 'array',
            'recommended_attire' => 'array',
            'things_to_know' => 'array',
            'travel_tips' => 'array',
            'optional_activities' => 'array',
            'is_featured' => 'boolean',
            'is_top_choice' => 'boolean',
            'is_discounted' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getCategoryAttribute(): string
    {
        return 'package';
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

        if ($this->image_url && ! $gallery->contains($this->image_url)) {
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

        $discountPercentage = max(0, min(100, (float) $this->discount_percentage));

        return round($basePrice * ((100 - $discountPercentage) / 100), 2);
    }
}
