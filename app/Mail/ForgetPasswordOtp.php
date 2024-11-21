<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPasswordOtp extends Mailable
{
    use Queueable, SerializesModels;
    public $otp_code;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otp_code)
    {
        $this->otp_code = $otp_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $otp_code = $this->otp_code;
        return $this->view('mails.forget_otp', compact('otp_code'))->subject('Forgot Password OTP Verification' .' | '.config('app.name'));
    }
}
