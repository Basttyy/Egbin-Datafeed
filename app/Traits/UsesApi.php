<?php

namespace App\Traits;

use App\User;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

trait UsesApi {
    private function authorizeOnApi(User $user)
    {
        $resp = Http::post(Env::get('api_base_url').'/extintegration/api/', [
            "grant_type" => "password",
            "username" => $user->email,
            "password" => Crypt::decryptString($user->api_pass)
        ]);

        if ($resp->status() === 200) {
            if (!$user->update(['api_token' => $resp['data']['access_token']])) {
                return false;
            } else { return true; }
        }
        return false;
    }

    private function syncData(array $data)
    {
        
    }
}