<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            // Thêm cột province_city
            $table->string('province_city', 100)->nullable();

            // Thêm cột district sau cột province_city
            $table->string('district', 100)->nullable();

            // Thêm cột ward sau cột district
            $table->string('ward', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            // Xóa các cột theo thứ tự ngược lại
            $table->dropColumn('ward');
            $table->dropColumn('district');
            $table->dropColumn('province_city');
        });
    }
};
