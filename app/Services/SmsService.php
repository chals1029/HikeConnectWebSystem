<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class SmsService
{
    public function sendVerificationCode(string $phoneNumber, string $code): bool
    {
        return $this->sendMessage(
            $phoneNumber,
            "HikeConnect verification code: {$code}. Expires in 10 minutes."
        );
    }

    public function sendPasswordResetCode(string $phoneNumber, string $code): bool
    {
        return $this->sendMessage(
            $phoneNumber,
            "HikeConnect password reset code: {$code}. Expires in 10 minutes."
        );
    }

    private function sendMessage(string $phoneNumber, string $content): bool
    {
        $secret = (string) (Config::get('services.unisms.secret_key') ?: env('UNISMS_SECRET_KEY', ''));
        if ($secret === '') {
            Log::warning('UniSMS secret key is missing. Skipping SMS send.');

            return false;
        }

        $recipient = $this->normalizePhilippineNumber($phoneNumber);
        if ($recipient === null) {
            Log::warning("Invalid PH phone number for SMS verification: {$phoneNumber}");

            return false;
        }

        try {
            $response = Http::withBasicAuth($secret, '')
                ->acceptJson()
                ->asJson()
                ->post('https://unismsapi.com/api/sms', [
                    'recipient' => $recipient,
                    'content' => $content,
                ]);

            if (! $response->successful()) {
                Log::error('UniSMS send failed with non-2xx response.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('UniSMS send threw an exception.', [
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function normalizePhilippineNumber(string $value): ?string
    {
        $raw = preg_replace('/\s+|-/', '', trim($value));
        if (! is_string($raw) || $raw === '') {
            return null;
        }

        if (preg_match('/^09\d{9}$/', $raw) === 1) {
            return '+63'.substr($raw, 1);
        }

        if (preg_match('/^\+639\d{9}$/', $raw) === 1) {
            return $raw;
        }

        if (preg_match('/^639\d{9}$/', $raw) === 1) {
            return '+'.$raw;
        }

        return null;
    }
}
