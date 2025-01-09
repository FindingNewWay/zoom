<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\Meeting;
use Firebase\JWT\JWT;

class ZoomController extends Controller
{
    public function scheduleMeeting(Request $request)
    {
        $client = new Client();
        $token = $this->getAccessToken();

        try {
            $response = $client->post('https://api.zoom.us/v2/users/me/meetings', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'topic' => $request->input('topic'),
                    'type' => 2, // Scheduled meeting
                    'start_time' => $request->input('start_time'),
                    'duration' => $request->input('duration'),
                    'timezone' => 'UTC+7', // Default to UTC+7
                    'agenda' => $request->input('agenda'),
                    'settings' => [
                        'host_video' => true,
                        'participant_video' => true,
                        'join_before_host' => false,
                        'mute_upon_entry' => true,
                        'waiting_room' => true,
                        'password' => $request->input('password'),
                    ],
                ],
                'verify' => false, // Disable SSL verification
            ]);

            $meetingData = json_decode($response->getBody(), true);

            // Save meeting details to the database
            $meeting = new Meeting();
            $meeting->topic = $request->input('topic');
            $meeting->meeting_id = $meetingData['id'];
            $meeting->password = $meetingData['password'];
            $meeting->start_time = $request->input('start_time');
            $meeting->duration = $request->input('duration');
            $meeting->timezone = 'UTC+7';
            $meeting->agenda = $request->input('agenda');
            $meeting->save();

            return response()->json([
                'success' => true,
                'message' => 'Meeting scheduled successfully',
                'data' => $meetingData
            ]);
        } catch (RequestException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule meeting',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getAccessToken()
    {
        $client = new Client();
        $response = $client->post('https://zoom.us/oauth/token', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(env('ZOOM_CLIENT_ID') . ':' . env('ZOOM_CLIENT_SECRET')),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'grant_type' => 'account_credentials',
                'account_id' => env('ZOOM_ACCOUNT_ID'),
            ],
            'verify' => false, // Disable SSL verification
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['access_token'];
    }

    public function getMeetings()
    {
        $meetings = Meeting::all();
        return response()->json($meetings);
    }

    public function joinMeeting($meeting_id)
    {
        $meeting = Meeting::where('meeting_id', $meeting_id)->firstOrFail();
        $signature = $this->generateSignature($meeting->meeting_id, 1);

        return view('join-meeting', [
            'meetingNumber' => $meeting->meeting_id,
            'passWord' => $meeting->password,
            'signature' => $signature
        ]);
    }

    private function generateSignature($meetingNumber, $role)
    {
        $apiKey = env('ZOOM_CLIENT_ID');
        $apiSecret = env('ZOOM_CLIENT_SECRET');

        $payload = [
            'sdkKey' => $apiKey,
            'mn' => $meetingNumber,
            'role' => $role,
            'iat' => time(),
            'exp' => time() + 60 * 60 * 2, // Token valid for 2 hours
            'appKey' => $apiKey,
            'tokenExp' => time() + 60 * 60 * 2
        ];

        return JWT::encode($payload, $apiSecret, 'HS256');
    }
}
