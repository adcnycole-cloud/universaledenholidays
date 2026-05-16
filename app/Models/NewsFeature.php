<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsFeature extends Model
{
    protected $fillable = [
        'promo_label',
        'title',
        'summary',
        'poster_path',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function getPosterUrlAttribute(): string
    {
        return asset('storage/'.$this->poster_path);
    }
}
