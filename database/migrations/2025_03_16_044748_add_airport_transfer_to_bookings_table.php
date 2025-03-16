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
            $table->boolean('has_airport_transfer')->default(false)->after('status');
            $table->string('airport_name')->nullable()->after('has_airport_transfer');
            $table->dateTime('transfer_datetime')->nullable()->after('airport_name');
            $table->string('transfer_type')->nullable()->after('transfer_datetime');
            $table->integer('transfer_passengers')->nullable()->after('transfer_type');
            $table->decimal('transfer_price', 10, 2)->nullable()->after('transfer_passengers');
            $table->text('transfer_notes')->nullable()->after('transfer_price');
            $table->string('transfer_status')->default('pending')->after('transfer_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'has_airport_transfer',
                'airport_name',
                'transfer_datetime',
                'transfer_type',
                'transfer_passengers',
                'transfer_price',
                'transfer_notes',
                'transfer_status'
            ]);
        });
    }
};
