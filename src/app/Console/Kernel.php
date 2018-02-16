<?php

namespace App\Console;

use App\Auction;
use App\Jobs\SendAuctionResume;
use App\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Kernel extends ConsoleKernel
{
    use DispatchesJobs;
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

        $schedule->call(function () {
            $now = date("Y-m-d H:i:s");
            $auctions = Auction::where('notification_status',Auction::NOTIFICATION_STATUS_IN_CURSE)
                ->where('end','<=',$now)->get();

            foreach ($auctions as $a) {
                $a->notification_status = Auction::NOTIFICATION_STATUS_SENDING;
                $a->save();
                $this->dispatch(new SendAuctionResume($a));
            }

        })->everyMinute();

        $schedule->call(function () {
            $now = date("Y-m-d H:i:s");
            $dt = $dt = Carbon::parse($now)->addMinutes(env('AUCTION_SUSCRIPTION_NOTIFICATION_MINUTES',60));

            $auctions = Auction::where('start','>',$now)
                ->where('end','<=',$dt)->get();

            foreach ($auctions as $a) {
                $this->dispatch(new SendAuctionReminder($a));
            }

        })->everyMinute();


    }
}
