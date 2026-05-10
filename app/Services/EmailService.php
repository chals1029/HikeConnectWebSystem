<?php

namespace App\Services;

use App\Models\SosAlert;
use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Mail settings must come from Config only. After `config:cache`, `env()` in
     * application code returns null, which breaks SMTP and From headers.
     */
    private function smtpHost(): string
    {
        $host = Config::get('mail.mailers.smtp.host');

        return ($host !== null && $host !== '') ? (string) $host : '127.0.0.1';
    }

    private function smtpPort(): int
    {
        $port = Config::get('mail.mailers.smtp.port');

        return ($port !== null && $port !== '') ? (int) $port : 587;
    }

    private function smtpUsername(): string
    {
        return (string) (Config::get('mail.mailers.smtp.username') ?? '');
    }

    private function smtpPassword(): string
    {
        return (string) (Config::get('mail.mailers.smtp.password') ?? '');
    }

    private function smtpEncryption(): string
    {
        $raw = Config::get('mail.mailers.smtp.encryption');
        $v = is_string($raw) ? strtolower(trim($raw)) : '';

        return match ($v) {
            'ssl', 'smtps' => PHPMailer::ENCRYPTION_SMTPS,
            'tls', 'starttls' => PHPMailer::ENCRYPTION_STARTTLS,
            default => $v === '' ? '' : PHPMailer::ENCRYPTION_STARTTLS,
        };
    }

    private function mailFromAddress(): string
    {
        $addr = (string) (Config::get('mail.from.address') ?? '');

        return $addr !== '' ? $addr : $this->smtpUsername();
    }

    private function mailFromName(): string
    {
        $name = (string) (Config::get('mail.from.name') ?? '');

        return $name !== '' ? $name : 'HikeConnect';
    }

    private function applySmtpSettings(PHPMailer $mail): void
    {
        $mail->isSMTP();
        $mail->Host = $this->smtpHost();
        $mail->SMTPAuth = true;
        $mail->Username = $this->smtpUsername();
        $mail->Password = $this->smtpPassword();
        $mail->SMTPSecure = $this->smtpEncryption();
        $mail->Port = $this->smtpPort();
    }

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
            $this->applySmtpSettings($mail);

            // Recipients
            $mail->setFrom($this->mailFromAddress(), $this->mailFromName());
            $mail->addAddress($email, $firstName);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'HikeConnect verification code';

            $safeName = e($firstName ?: 'Hiker');
            $safeCode = e($code);
            $year = now()->year;
            $mail->Body = "
                <div style='margin:0;padding:0;background:#eef7f3;font-family:Arial,\"Helvetica Neue\",Helvetica,sans-serif;color:#0f172a;'>
                    <table role='presentation' width='100%' cellspacing='0' cellpadding='0' style='background:#eef7f3;padding:28px 14px;'>
                        <tr>
                            <td align='center'>
                                <table role='presentation' width='100%' cellspacing='0' cellpadding='0' style='max-width:620px;background:#ffffff;border:1px solid #d9ece3;border-radius:14px;overflow:hidden;'>
                                    <tr>
                                        <td style='background:linear-gradient(135deg,#064e3b 0%,#0f766e 100%);padding:20px 22px;'>
                                            &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding:24px 24px 8px;'>
                                            <p style='margin:0 0 8px;font-size:14px;color:#0f766e;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;'>Email verification</p>
                                            <h1 style='margin:0 0 10px;font-size:24px;line-height:1.25;color:#0f172a;'>Welcome to HikeConnect, {$safeName}</h1>
                                            <p style='margin:0;font-size:15px;line-height:1.65;color:#334155;'>
                                                Use this one-time code to verify your account and start exploring trails, guides, and community features.
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding:16px 24px 10px;'>
                                            <div style='background:linear-gradient(180deg,#f8fffc 0%,#eefaf4 100%);border:1px solid #cce9db;border-radius:12px;padding:18px 16px;text-align:center;'>
                                                <p style='margin:0 0 8px;font-size:13px;color:#0f766e;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;'>Your verification code</p>
                                                <div style='font-size:34px;line-height:1.1;font-weight:800;letter-spacing:8px;color:#065f46;'>{$safeCode}</div>
                                                <p style='margin:10px 0 0;font-size:12px;color:#64748b;'>Code expires in 10 minutes</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding:8px 24px 12px;'>
                                            <div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px 14px;'>
                                                <p style='margin:0;font-size:13px;line-height:1.6;color:#475569;'>
                                                    For your security, do not share this code with anyone. The HikeConnect team will never ask for your code by chat or call.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding:6px 24px 24px;'>
                                            <p style='margin:0;font-size:14px;line-height:1.6;color:#334155;'>
                                                See you on the trail,<br>
                                                <strong style='color:#0f172a;'>The HikeConnect Team</strong>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding:14px 24px;background:#f8fafc;border-top:1px solid #e2e8f0;'>
                                            <p style='margin:0;font-size:12px;line-height:1.5;color:#64748b;'>
                                                This is an automated message from HikeConnect. If you did not request this code, you can ignore this email.
                                            </p>
                                            <p style='margin:8px 0 0;font-size:12px;color:#94a3b8;'>&copy; {$year} HikeConnect</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            ";

            $mail->AltBody = "HikeConnect verification code\n\n"
                ."Hi {$firstName},\n\n"
                ."Your verification code is: {$code}\n"
                ."This code expires in 10 minutes.\n\n"
                ."Do not share this code with anyone.\n\n"
                ."See you on the trail,\n"
                ."The HikeConnect Team";

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
            $this->applySmtpSettings($mail);

            $mail->setFrom($this->mailFromAddress(), $this->mailFromName());
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

    public function sendSosAlert(User $recipient, SosAlert $alert): bool
    {
        if (! $recipient->email) {
            return false;
        }

        $mail = new PHPMailer(true);
        $hiker = $alert->user;
        $booking = $alert->hikeBooking;
        $mountain = $alert->mountain ?? $booking?->mountain;
        $guide = $alert->tourGuide;
        $hikerName = e($hiker?->full_name ?? 'A hiker');
        $recipientName = e($recipient->first_name ?: 'HikeConnect team');
        $mountainName = e($mountain?->name ?? 'Unknown mountain');
        $guideName = e($guide?->full_name ?? 'Unassigned guide');
        $message = e($alert->message ?: 'Emergency SOS triggered from hiker live tracking.');
        $coords = ($alert->lat !== null && $alert->lng !== null)
            ? number_format($alert->lat, 7).', '.number_format($alert->lng, 7)
            : 'No GPS coordinates were available';
        $mapsUrl = ($alert->lat !== null && $alert->lng !== null)
            ? 'https://www.google.com/maps?q='.$alert->lat.','.$alert->lng
            : null;
        $bookingLine = $booking
            ? 'Booking #'.$booking->id.' on '.$booking->hike_on?->format('M j, Y').' ('.$booking->status.')'
            : 'No active booking was attached';

        try {
            $this->applySmtpSettings($mail);

            $mail->setFrom($this->mailFromAddress(), $this->mailFromName());
            $mail->addAddress($recipient->email, $recipient->full_name);

            $mail->isHTML(true);
            $mail->Subject = 'URGENT: HikeConnect Emergency SOS Alert';

            $mapLink = $mapsUrl
                ? "<p><a href='{$mapsUrl}' style='display:inline-block;background:#dc2626;color:#fff;text-decoration:none;padding:10px 16px;border-radius:8px;font-weight:bold;'>Open location in Google Maps</a></p>"
                : '';

            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 640px; margin: 0 auto; padding: 20px; color: #1f2937;'>
                    <div style='background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:18px;margin-bottom:18px;'>
                        <h2 style='color:#b91c1c;margin:0 0 8px;'>Emergency SOS Alert</h2>
                        <p style='margin:0;'>Hi {$recipientName}, {$hikerName} triggered an SOS in HikeConnect.</p>
                    </div>
                    <table style='width:100%;border-collapse:collapse;font-size:14px;'>
                        <tr><td style='padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;'>Hiker</td><td style='padding:8px;border-bottom:1px solid #e5e7eb;'>{$hikerName}</td></tr>
                        <tr><td style='padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;'>Mountain</td><td style='padding:8px;border-bottom:1px solid #e5e7eb;'>{$mountainName}</td></tr>
                        <tr><td style='padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;'>Assigned Guide</td><td style='padding:8px;border-bottom:1px solid #e5e7eb;'>{$guideName}</td></tr>
                        <tr><td style='padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;'>Booking</td><td style='padding:8px;border-bottom:1px solid #e5e7eb;'>".e($bookingLine)."</td></tr>
                        <tr><td style='padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;'>Coordinates</td><td style='padding:8px;border-bottom:1px solid #e5e7eb;'>".e($coords)."</td></tr>
                        <tr><td style='padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;'>Message</td><td style='padding:8px;border-bottom:1px solid #e5e7eb;'>{$message}</td></tr>
                    </table>
                    {$mapLink}
                    <p style='color:#6b7280;font-size:13px;margin-top:18px;'>Open the HikeConnect dashboard to acknowledge or resolve this alert.</p>
                </div>
            ";

            $mail->AltBody = "Emergency SOS Alert\n\n"
                ."Hiker: ".strip_tags($hikerName)."\n"
                ."Mountain: ".strip_tags($mountainName)."\n"
                ."Assigned Guide: ".strip_tags($guideName)."\n"
                ."Booking: {$bookingLine}\n"
                ."Coordinates: {$coords}\n"
                ."Message: ".strip_tags($message)."\n"
                .($mapsUrl ? "Map: {$mapsUrl}\n" : '')
                ."\nOpen the HikeConnect dashboard to acknowledge or resolve this alert.";

            $mail->send();

            return true;
        } catch (Exception $e) {
            Log::error("PHPMailer Error sending SOS alert #{$alert->id} to {$recipient->email}: ".$mail->ErrorInfo);

            return false;
        }
    }

    public function sendNewsletterSubscription(string $subscriberEmail): bool
    {
        $mail = new PHPMailer(true);

        try {
            $this->applySmtpSettings($mail);

            $internalTo = $this->mailFromAddress();
            $mail->setFrom($internalTo, $this->mailFromName());
            $mail->addAddress($internalTo, $this->mailFromName());
            $mail->addReplyTo($subscriberEmail);

            $mail->isHTML(true);
            $mail->Subject = 'New HikeConnect newsletter subscription';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 620px; margin: 0 auto; padding: 20px; color: #1f2937;'>
                    <h2 style='margin: 0 0 12px; color: #065f46;'>New newsletter subscriber</h2>
                    <p style='margin: 0 0 6px;'>A visitor subscribed from the HikeConnect landing page.</p>
                    <p style='margin: 0 0 6px;'><strong>Email:</strong> ".e($subscriberEmail)."</p>
                    <p style='margin: 0; color: #6b7280; font-size: 13px;'>Time: ".now()->toDateTimeString()."</p>
                </div>
            ";
            $mail->AltBody = "New HikeConnect newsletter subscriber\nEmail: {$subscriberEmail}\nTime: ".now()->toDateTimeString();

            $mail->send();

            return true;
        } catch (Exception $e) {
            Log::error("PHPMailer Error sending newsletter notification for {$subscriberEmail}: ".$mail->ErrorInfo);

            return false;
        }
    }
}
