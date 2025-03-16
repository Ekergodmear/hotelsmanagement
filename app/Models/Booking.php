<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'guest_id',
        'check_in',
        'check_out',
        'guests',
        'total_price',
        'status',
        'has_airport_transfer',
        'airport_name',
        'transfer_datetime',
        'transfer_type',
        'transfer_passengers',
        'transfer_price',
        'transfer_notes',
        'transfer_status'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'transfer_datetime' => 'datetime',
        'has_airport_transfer' => 'boolean',
        'total_price' => 'decimal:2',
        'transfer_price' => 'decimal:2'
    ];

    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the room that owns the booking.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the services for the booking.
     */
    public function services(): HasMany
    {
        return $this->hasMany(BookingService::class);
    }

    /**
     * Lấy thông tin dịch vụ đưa đón sân bay
     */
    public function airportTransfer()
    {
        return $this->belongsTo(AirportTransfer::class, 'transfer_type', 'vehicle_type');
    }

    /**
     * Kiểm tra xem đặt phòng có dịch vụ đưa đón sân bay hay không
     */
    public function hasAirportTransfer()
    {
        return $this->has_airport_transfer;
    }

    /**
     * Tính tổng giá tiền bao gồm cả dịch vụ đưa đón sân bay
     */
    public function getTotalWithTransferAttribute()
    {
        $total = $this->total_price;

        if ($this->has_airport_transfer && $this->transfer_price) {
            $total += $this->transfer_price;
        }

        return $total;
    }
}
