<?php

namespace App\Jobs;

use App\Auction;
use App\Jobs\Job;
use App\User;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAuctionResume extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;
    protected $auction;
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
        foreach($this->auction->bids()->select('user_id')->distinct()->where('auction_id',$this->auction->id)->get() as $u){
            $user = User::findOrFail($u->user_id);
            $bids = $this->auction->bids->where('user_id',$user->id);

            $mailer->send('emails.auctionResume', ['user' => $user, 'auction' => $this->auction, 'bids' => $bids ],  function ($message) use ($user) {
                $message->from(
                    env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
                    env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
                );
                $message->subject('Subasta finalizada');
                $message->to($user->email);
            });
        }

        $this->auction->notification_status = Auction::NOTIFICATION_STATUS_SENDED;
        $this->auction->save();

    }
}
