<?php

namespace App\Http\Traits;

trait BearerToken
{
    public function getBearerToken($authorization)
    {
        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $authorization, $matches)) {
            $token = $matches[1];
        }
        return $token;
    }
}
