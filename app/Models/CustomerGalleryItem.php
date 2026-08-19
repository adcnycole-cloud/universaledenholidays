<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGalleryItem extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return asset('storage/'.$this->image_path);
        }

        return '';
    }
}
