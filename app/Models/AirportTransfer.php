<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirportTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'vehicle_type',
        'max_passengers',
        'max_luggage',
        'price',
        'description',
        'image_path',
        'is_popular',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Lấy tất cả các đặt phòng có sử dụng dịch vụ đưa đón này
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'transfer_type', 'vehicle_type');
    }

    /**
     * Get the URL for the image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }

        return asset('images/airport-transfers/default-' . strtolower($this->vehicle_type) . '.jpg');
    }
}
