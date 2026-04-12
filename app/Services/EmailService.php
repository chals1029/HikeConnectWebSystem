<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send a 6-digit verification code to the user.
     *
     * @param string $email
     * @param string $code
     * @param string $firstName
     * @return bool
     */
    public function sendVerificationCode(string $email, string $code, string $firstName): bool
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS);
            $mail->Port       = env('MAIL_PORT', 587);

            // Recipients
            $mail->setFrom(env('MAIL_FROM_ADDRESS') ?? env('MAIL_USERNAME'), env('MAIL_FROM_NAME', 'HikeConnect'));
            $mail->addAddress($email, $firstName);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'HikeConnect - Verify your email address';
            
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; color: #333;'>
                    <h2 style='color: #10b981;'>Welcome to HikeConnect, {$firstName}!</h2>
                    <p>Thanks for registering. To complete your account creation, please use the verification code below:</p>
                    <div style='background-color: #f6fbf8; padding: 15px; text-align: center; font-size: 24px; letter-spacing: 5px; font-weight: bold; color: #065f46; border-radius: 8px; margin: 20px 0;'>
                        {$code}
                    </div>
                    <p>This code will expire in 10 minutes.</p>
                    <p style='color: #666; font-size: 14px; margin-top: 30px;'>See you on the trail,<br>The HikeConnect Team</p>
                </div>
            ";
            
            $mail->AltBody = "Hi {$firstName},\n\nYour HikeConnect verification code is: {$code}\n\nThis code will expire in 10 minutes.\n\nSee you on the trail,\nThe HikeConnect Team";

            $mail->send();
            return true;
        } catch (Exception $e) {
            Log::error("PHPMailer Error sending verification to {$email}: " . $mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Send a 6-digit code to confirm a password change (logged-in user).
     */
    public function sendPasswordChangeCode(string $email, string $code, string $firstName): bool
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS);
            $mail->Port = env('MAIL_PORT', 587);

            $mail->setFrom(env('MAIL_FROM_ADDRESS') ?? env('MAIL_USERNAME'), env('MAIL_FROM_NAME', 'HikeConnect'));
            $mail->addAddress($email, $firstName);

            $mail->isHTML(true);
            $mail->Subject = 'HikeConnect — confirm your password change';

            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; color: #333;'>
                    <h2 style='color: #10b981;'>Hi {$firstName},</h2>
                    <p>You requested to change your HikeConnect password. Use this code to confirm:</p>
                    <div style='background-color: #f6fbf8; padding: 15px; text-align: center; font-size: 24px; letter-spacing: 5px; font-weight: bold; color: #065f46; border-radius: 8px; margin: 20px 0;'>
                        {$code}
                    </div>
                    <p>This code expires in 10 minutes. If you didn’t ask for this, you can ignore this email.</p>
                    <p style='color: #666; font-size: 14px; margin-top: 30px;'>See you on the trail,<br>The HikeConnect Team</p>
                </div>
            ";

            $mail->AltBody = "Hi {$firstName},\n\nYour HikeConnect password change code is: {$code}\n\nExpires in 10 minutes.\n\nIf you didn’t request this, ignore this email.\n\n— HikeConnect";

            $mail->send();

            return true;
        } catch (Exception $e) {
            Log::error("PHPMailer Error sending password-change code to {$email}: ".$mail->ErrorInfo);

            return false;
        }
    }
}
