<?php

namespace App\Console\Commands;

use App\Mail\GenerateInvoiceReminderMail;
use App\Models\User;
use Illuminate\Console\Command;
use Mail;

class SendCMDInvoiceReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a reminder to all CMDs to generate invoice';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $emails = ['eokorie@emgeresources.com', 'yokubadejo@emgeresources.com'];
        $cmds = User::whereHas('roles', function ($query) {
            $query->whereIn('role', ["cmd"]);
        })->where('status', 'active')->get(['email']);

        foreach ($cmds as $cmd) {
            array_push($emails, $cmd->email);
        }

        return Mail::to($emails)->send(new GenerateInvoiceReminderMail());
        return 0;
    }
}
