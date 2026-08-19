<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'email',
        'location',
        'trip_name',
        'quote',
        'rating',
        'profile_photo_path',
        'is_featured',
        'display_location',
        'product_id',
        'package_id',
        'show_in_customer_gallery',
        'gallery_title',
        'gallery_description',
        'gallery_image_path',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'show_in_customer_gallery' => 'boolean',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path) {
            return asset('storage/'.$this->profile_photo_path);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=0f4fb5&color=ffffff&size=128&bold=true';
    }

    public function getGalleryImageUrlAttribute(): string
    {
        if ($this->gallery_image_path) {
            return asset('storage/'.$this->gallery_image_path);
        }

        return $this->profile_photo_url;
    }
}
