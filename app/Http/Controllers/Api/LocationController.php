<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getDistricts($provinceId)
    {
        $districts = District::where('province_id', $provinceId)
            ->orderBy('name')
            ->get(['id', 'name']);
        return response()->json($districts);
    }

    public function getWards($districtId)
    {
        $wards = Ward::where('district_id', $districtId)
            ->orderBy('name')
            ->get(['id', 'name']);
        return response()->json($wards);
    }
}
