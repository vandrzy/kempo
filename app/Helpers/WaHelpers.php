<?php
namespace App\Helpers;

use App\Mail\OtpMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WaHelpers
{
    public static function test() {
        return "yes";
    }

    public static function sendWa($noHp, $msg)
    {
        try {
            $key = env("WOOWA_KEY");
            if (empty($key)) {
                Log::info('WOOWA_KEY belum disetel di .env, lewati pengiriman WA.');
                return false;
            }

            $url = 'https://notifapi.com/send_message';

            //if $noHp doesnt have +62 then add +62
            if (substr($noHp, 0, 1) == '0') {
                $noHp = '+62' . substr($noHp, 1);
            } else if (substr($noHp, 0, 1) == '8') {
                $noHp = '+62' . $noHp;
            }

            $data = array(
                "phone_no" => $noHp,
                "key" => $key,
                "message" => $msg,
            );
            $data_string = json_encode($data);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string)
                )
            );
            $res = curl_exec($ch);
            curl_close($ch);
            return $res;
        } catch (\Throwable $e) {
            Log::warning('Gagal kirim WA: ' . $e->getMessage());
            return false;
        }
    }

    public static function sendFile($noHp, $filePath)
    {
        try {
            $key = env("WOOWA_KEY");
            if (empty($key)) {
                Log::info('WOOWA_KEY belum disetel di .env, lewati pengiriman file WA.');
                return false;
            }

            $url = 'http://116.203.191.58/api/async_send_file_url';
            $file_path = $filePath;

            //if $noHp doesnt have +62 then add +62
            if (substr($noHp, 0, 1) == '0') {
                $noHp = '+62' . substr($noHp, 1);
            } else if (substr($noHp, 0, 1) == '8') {
                $noHp = '+62' . $noHp;
            }

            if ($file_path != null) {
                $data = array(
                    "phone_no" => $noHp,
                    "key" => $key,
                    "url" => $file_path
                );
            } else {
                $data = array(
                    "phone_no" => $noHp,
                    "key" => $key
                );
            }
            $data_string = json_encode($data);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string)
                )
            );
            $res = curl_exec($ch);
            curl_close($ch);
            return $res;
        } catch (\Throwable $e) {
            Log::warning('Gagal kirim file WA: ' . $e->getMessage());
            return false;
        }
    }

    public static function sendEmail($email, $message)
    {
        try {
            Mail::to($email)->send(new OtpMail($message));
            return true;
        } catch (\Throwable $e) {
            Log::warning('Gagal kirim email: ' . $e->getMessage());
            return false;
        }
    }
}
