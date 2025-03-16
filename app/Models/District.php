<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'division_type',
        'province_id',
    ];

    /**
     * Lấy tỉnh/thành phố mà quận/huyện thuộc về
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Lấy tất cả phường/xã thuộc quận/huyện
     */
    public function wards(): HasMany
    {
        return $this->hasMany(Ward::class);
    }
}
