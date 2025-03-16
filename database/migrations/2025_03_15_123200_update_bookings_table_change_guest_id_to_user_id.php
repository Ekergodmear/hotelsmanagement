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
        Schema::table('bookings', function (Blueprint $table) {
            // Thêm cột user_id mới
            $table->foreignId('user_id')->nullable()->after('id');

            // Cập nhật dữ liệu từ guest_id sang user_id (nếu cần)
            // Đoạn này sẽ cần thực hiện thủ công hoặc bằng seeder

            // Xóa cột guest_id cũ
            $table->dropForeign(['guest_id']);
            $table->dropColumn('guest_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Thêm lại cột guest_id
            $table->foreignId('guest_id')->nullable()->after('id');

            // Xóa cột user_id
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
