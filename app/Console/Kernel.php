<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Danh sách các lệnh Artisan được cung cấp bởi ứng dụng.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\SimplifyHotelAmenities::class,
    ];

    /**
     * Định nghĩa lịch trình lệnh của ứng dụng.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Đăng ký các lệnh cho ứng dụng.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
