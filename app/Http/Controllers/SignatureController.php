<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;

class SignatureController extends Controller
{
    public function generate(Request $request)
    {
        $apiKey = env('ZOOM_API_KEY');
        $apiSecret = env('ZOOM_API_SECRET');
        $meetingNumber = $request->meetingNumber;
        $role = $request->role;

        $payload = [
            'sdkKey' => $apiKey,
            'mn' => $meetingNumber,
            'role' => $role,
            'iat' => time(),
            'exp' => time() + 60 * 60 * 2, // Token valid for 2 hours
            'appKey' => $apiKey,
            'tokenExp' => time() + 60 * 60 * 2
        ];

        $jwt = JWT::encode($payload, $apiSecret, 'HS256');

        return response()->json(['signature' => $jwt]);
    }
}
