<?php

namespace App\Traits;

use App\User;
use Exception;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait UsesApi {
    private function authorizeOnApi(User $user)
    {
        Log::info(env('api_username'));
        Log::info(Crypt::decryptString(env('api_pass')));
        $resp = $this->makeRequestAndGetResponse('extintegration/api/', 'post', [
            "grant_type" => "password",
            "username" => env('api_username'),
            "password" => Crypt::decryptString(env('api_pass'))
        ]);

        if ($resp->status() === 200) {
            //Log::debug($resp['data']['company_id']);
            if (!$user->update(['api_token' => $resp['data']['access_token'], 'companyId' => $resp['data']['company_id']])) {
                return false;
            } else { return true; }
        }
        return false;
    }

    private function syncDataWithApi(array $data, string $token): bool
    {
        // Log::debug($data);
        // Log::debug($token);
        $resp = $this->makeRequestAndGetResponse('metricentries/push/', 'post', $data, $token);

        if ($resp->status() === 200) {
            return true;
        }
        //Log::debug($resp);
        return false;
    }

    private function makeRequestAndGetResponse(string $path = "", string $method = 'get', array $data = null, string $token = null)
    {
        try {
            $resp = \is_null($token) ? Http::{$method}(Env::get('api_base_url').'/'.$path, $data) : Http::withToken($token)->{$method}(Env::get('api_base_url').'/'.$path, $data);
            Log::info($resp);
            return $resp;
        } catch (Exception $ex) {
            Log::debug($ex->getMessage());
            return $resp;
        }
    }
}