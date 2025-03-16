<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tắt kiểm tra khóa ngoại
        Schema::disableForeignKeyConstraints();

        // Xóa dữ liệu cũ
        Ward::truncate();
        District::truncate();
        Province::truncate();

        // Lấy dữ liệu tỉnh/thành phố từ API
        $response = Http::get('https://provinces.open-api.vn/api/?depth=3');

        if ($response->successful()) {
            $provinces = $response->json();

            foreach ($provinces as $provinceData) {
                // Tạo tỉnh/thành phố
                $province = Province::create([
                    'name' => $provinceData['name'],
                    'code' => $provinceData['code'],
                    'division_type' => $provinceData['division_type'],
                ]);

                // Tạo quận/huyện
                foreach ($provinceData['districts'] as $districtData) {
                    $district = District::create([
                        'name' => $districtData['name'],
                        'code' => $districtData['code'],
                        'division_type' => $districtData['division_type'],
                        'province_id' => $province->id,
                    ]);

                    // Tạo phường/xã
                    foreach ($districtData['wards'] as $wardData) {
                        Ward::create([
                            'name' => $wardData['name'],
                            'code' => $wardData['code'],
                            'division_type' => $wardData['division_type'],
                            'district_id' => $district->id,
                        ]);
                    }
                }
            }

            $this->command->info('Đã nhập dữ liệu địa điểm thành công!');
        } else {
            Log::error('Không thể lấy dữ liệu từ API: ' . $response->status());
            $this->command->error('Không thể lấy dữ liệu từ API!');
        }

        // Bật lại kiểm tra khóa ngoại
        Schema::enableForeignKeyConstraints();
    }
}
