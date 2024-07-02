<?php
use Illuminate\Support\Facades\Http;

function getUser($userId)
{
    $url = env("SERVICE_USER_URL") . "users/" . $userId;

    try {
        $response = Http::timeout(10)->get($url);
        $data = $response->json();
        $data["http_code"] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            "status" => "error",
            "message" => $th->getMessage(),
            "http_code" => 500
        ];
    }
}

function getUserIds($userIds = [])
{
    $url = env("SERVICE_USER_URL") . "users/";

    try {
        if (count($userIds) === 0) {
            return [
                'status' => 'success',
                'data' => [],
                'http_code' => 200
            ];
        }

        $response = Http::timeout(10)->get($url, ['user_ids[]' => $userIds]);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            "status" => "error",
            "message" => $th->getMessage(),
            "http_code" => 500
        ];
    }
}