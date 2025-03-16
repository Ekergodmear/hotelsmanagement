<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingService extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'service_id',
        'quantity',
        'price',
        'service_date',
        'notes',
    ];

    protected $casts = [
        'service_date' => 'datetime',
    ];

    /**
     * Get the booking that owns the booking service.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the service that owns the booking service.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
