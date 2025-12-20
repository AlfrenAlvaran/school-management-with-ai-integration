<?php

namespace App\Services;

use Core\Http\Session;
use Core\Mail\Mailer;

class OtpService
{
    protected Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function generateOtp(int $userId): int
    {
        $otp = random_int(100000, 999999);
        Session::set('otp', $otp);
        Session::set('otp_user_id', $userId);
        Session::set('otp_expires', time() + 300); 
        Session::set('otp_attempts', 0);
        return $otp;
    }


    public function sendOtp(string $email, int $opt)
    {
        return $this->mailer->send(
            $email,
            'Your OTP Code',
            $this->buildOtpEmailBody($opt)
        );
    }


    public function validateOtp(int $otp): bool
    {
        if (!Session::get('otp') || !Session::get('otp_expires')) {
            return false;
        }

        if (time() > Session::get('otp_expires')) {
            return false;
        }


        $attempts = Session::get('otp_attempts') ?? 0;
        if ($attempts >= 5) {
            return false;
        }
        Session::set('otp_attempts', $attempts + 1);
        if($otp === Session::get('otp')) {
            Session::remove('otp');
            Session::remove('otp_user_id');
            Session::remove('otp_expires');
            Session::remove('otp_attempts');
            return true;
        }
        return false;
    }


    protected function buildOtpEmailBody(int $otp): string
    {
        return "
            <div style='font-family: \"Segoe UI\", Roboto, sans-serif; background-color:#f4f4f7; padding:40px;'>
                <table width='100%' cellpadding='0' cellspacing='0' style='max-width:600px; margin:0 auto; background:#ffffff; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.1);'>
                    <tr>
                        <td style='padding:40px; text-align:center;'>
                            <h1 style='color:#4F46E5; font-size:28px; margin-bottom:10px;'>Verify Your Account</h1>
                            <p style='color:#555555; font-size:16px; margin-bottom:30px;'>Use the code below to complete your verification process. It expires in <strong>5 minutes</strong>.</p>
                            
                            <div style='display:inline-block; padding:20px 40px; background:#4F46E5; color:#ffffff; font-size:32px; font-weight:bold; letter-spacing:4px; border-radius:8px; margin-bottom:30px;'>
                                {$otp}
                            </div>
                            
                            <p style='color:#555555; font-size:14px; margin-bottom:10px;'>If you did not request this, you can safely ignore this email.</p>
                            <p style='color:#555555; font-size:14px;'>Need help? <a href='#' style='color:#4F46E5; text-decoration:none;'>Contact Support</a></p>
                            
                            <hr style='border:none; border-top:1px solid #eeeeee; margin:30px 0;' />
                            
                            <p style='color:#999999; font-size:12px;'>© " . date('Y') . " MyApp. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </div>
        ";
    }
}
