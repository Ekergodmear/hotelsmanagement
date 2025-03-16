<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'province_city',
        'district',
        'ward',
        'description',
        'rating',
        'price_per_night',
        'image',
        'amenities'
    ];

    protected $casts = [
        'amenities' => 'array',
        'rating' => 'float',
        'price_per_night' => 'decimal:2'
    ];

    /**
     * Lấy tất cả các phòng của khách sạn
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Lấy tất cả các ảnh của khách sạn
     */
    public function images(): HasMany
    {
        return $this->hasMany(HotelImage::class)->orderBy('display_order');
    }

    /**
     * Lấy ảnh chính của khách sạn
     */
    public function primaryImage()
    {
        return $this->hasOne(HotelImage::class)->where('is_primary', true);
    }

    /**
     * Lấy tất cả các đánh giá của khách sạn
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->orderBy('created_at', 'desc');
    }

    /**
     * Lấy giá thấp nhất của các phòng trong khách sạn
     */
    public function getMinRoomPriceAttribute()
    {
        // Ưu tiên lấy giá từ bảng rooms nếu có
        $minRoomPrice = $this->rooms()->min('price');

        // Nếu không có giá trong bảng rooms, lấy giá từ room_types
        if (!$minRoomPrice) {
            $minRoomPrice = $this->rooms()
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->min('room_types.base_price');
        }

        // Nếu không có phòng hoặc không có giá, trả về giá của khách sạn
        return $minRoomPrice ?: $this->price_per_night;
    }
}
