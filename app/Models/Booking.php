<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'booking_reference',
        'service_type',
        'full_name',
        'email',
        'phone',
        'pickup_location',
        'destination',
        'package_name',
        'malaysian_adults',
        'malaysian_kids',
        'international_adults',
        'international_kids',
        'guest_count',
        'check_in_date',
        'check_out_date',
        'special_requests',
        'payment_method',
        'currency_code',
        'amount_myr',
        'amount_display',
        'status',
        'payment_status',
        'account_setup_token',
        'account_setup_expires_at',
        'account_setup_completed_at',
        'payment_submitted_at',
        'confirmed_at',
        'invoice_number',
        'invoice_issued_at',
    ];

    protected function casts(): array
    {
        return [
            'check_in_date' => 'date',
            'check_out_date' => 'date',
            'amount_myr' => 'decimal:2',
            'amount_display' => 'decimal:2',
            'account_setup_expires_at' => 'datetime',
            'account_setup_completed_at' => 'datetime',
            'payment_submitted_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'invoice_issued_at' => 'datetime',
        ];
    }

    public function getInvoiceNumberOrReferenceAttribute(): string
    {
        return $this->invoice_number ?: ($this->booking_reference ?: 'BOOKING-'.$this->id);
    }

    public function getTotalGuestsAttribute(): int
    {
        return (int) $this->malaysian_adults
            + (int) $this->malaysian_kids
            + (int) $this->international_adults
            + (int) $this->international_kids;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
