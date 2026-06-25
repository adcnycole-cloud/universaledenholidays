<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CompanyCertification extends Model
{
    protected $fillable = [
        'title',
        'value',
        'description',
        'logo_path',
        'certificate_path',
        'certificate_link',
        'sort_order',
    ];

    protected $appends = [
        'logo_url',
        'certificate_url',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo_path) {
            return null;
        }

        return Storage::disk('public')->url($this->logo_path);
    }

    public function getCertificateUrlAttribute(): ?string
    {
        if ($this->certificate_link) {
            return $this->certificate_link;
        }

        if (! $this->certificate_path) {
            return null;
        }

        return Storage::disk('public')->url($this->certificate_path);
    }
}
