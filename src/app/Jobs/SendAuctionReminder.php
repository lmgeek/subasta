<?php

namespace App\Jobs;

use App\Auction;
use App\Jobs\Job;
use App\Subscription;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAuctionReminder extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        foreach ($this->auction->subscription()->where('status',Subscription::NO_NOTIFICADO) as $sub) {

            foreach($sub->user() as $user){
                $mailer->send('emails.auctionReminder', ['user' => $user, 'auction' => $this->auction],  function ($message) use ($user) {
                    $message->from(
                        env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
                        env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
                    );
                    $message->subject('Recordatorio Subasta');
                    $message->to($user->email);
                });
            }

            $sub->status = Subscription::NOTIFICADO;
            $sub->save();
        }

    }
}
