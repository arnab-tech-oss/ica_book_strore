<?php

namespace App\Console\Commands;

use App\Mail\UserRegistrationMail;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendRegisteredUserMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendRegisteredUser:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $dataTime = Carbon::now()->subMinutes(5)->toDateTimeString();
        $nowDateTime = Carbon::now()->toDateTimeString();
        $users = User::where('created_at','>=', $dataTime)->where('created_at','<',$nowDateTime)->get();
        foreach ($users as $user){
            $setting = config('app.setting');
            if (!empty($setting)) {
                $cc = $bcc = null;
                $cc = explode(',', $setting['email_cc']);
                $bcc = explode(',', $setting['email_bcc']);
            }
            $option = [
                'user' => $user->name,
                'supportEmail' => isset($setting) && !empty($setting) ? $setting['email_cc'] : '',
                'user_email' => $user->email,
                'user_password' => $user->decrypt_password
            ];
            try {
                Mail::to($user->email)->send(new UserRegistrationMail($option,$cc,$bcc));
            }catch(\Exception $e){
                Log::info($e->getMessage());
            }
        }
    }
}
