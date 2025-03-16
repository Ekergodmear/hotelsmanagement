<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'division_type',
    ];

    /**
     * Lấy tất cả quận/huyện thuộc tỉnh/thành phố
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}
