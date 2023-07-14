<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

use App\Mail\EmailVerification;
use App\Traits\tUserVerification;
use App\Models\UserVerification;

class SendAccountVerificationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, tUserVerification;

    public Array $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Array $data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        /* 
        *   sendEmailToken method comes from the tUserVerification Trait.
         */
        $this->sendEmailToken($this->data['email']);
    }
}
