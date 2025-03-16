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
            // Thêm cột district sau cột province_city
            $table->string('district', 100)->after('province_city')->nullable();

            // Thêm cột ward sau cột district
            $table->string('ward', 100)->after('district')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            // Xóa cột ward
            $table->dropColumn('ward');

            // Xóa cột district
            $table->dropColumn('district');
        });
    }
};
