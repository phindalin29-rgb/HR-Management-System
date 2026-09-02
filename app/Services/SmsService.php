<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(string $to, string $message): bool
    {
        $driver = config('services.sms.driver', 'log');

        if ($driver === 'log') {
            Log::info("SMS (stub) to {$to}: {$message}");
            return true;
        }

        if ($driver === 'twilio') {
            return $this->sendTwilio($to, $message);
        }

        return false;
    }

    protected function sendTwilio(string $to, string $message): bool
    {
        $sid   = config('services.sms.twilio_sid');
        $token = config('services.sms.twilio_token');
        $from  = config('services.sms.twilio_from');

        if (!$sid || !$token || !$from) {
            return false;
        }

        try {
            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                    'To'   => $to,
                    'From' => $from,
                    'Body' => $message,
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('SMS send failed: ' . $e->getMessage());
            return false;
        }
    }
}
