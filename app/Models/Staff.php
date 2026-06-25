<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $fillable = [
        'name',
        'contact',
        'email',
        'designation',
        'photo_path',
        'sort_order',
    ];

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            return asset('storage/'.$this->photo_path);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=1f2937&color=ffffff&size=320&bold=true';
    }
}
