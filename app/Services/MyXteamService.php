<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MyXteamService
{
    protected $client;
    protected $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = 'https://public.myxteam.com/'; // URL của API myXteam
        $this->certPath = storage_path('certs/cacert.pem'); // Đường dẫn đến file cacert.pem
    }

    public function makeRequest($method, $endpoint, $data = [])
    {
        $token = env('MYXTEAM_BEARER_TOKEN');

        try {
            $response = $this->client->request($method, $this->apiUrl . $endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'json' => $data,
//                'verify' => false, // Thêm dòng này để bỏ qua kiểm tra SSL
                'verify' => $this->certPath, // Cấu hình Guzzle để sử dụng chứng chỉ gốc
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            $errorResponse = [];

            if ($e->hasResponse()) {
                $errorResponse['status_code'] = $e->getResponse()->getStatusCode();
                $errorResponse['body'] = json_decode($e->getResponse()->getBody(), true);
            } else {
                $errorResponse['error'] = 'Request failed';
            }

            $errorResponse['message'] = $e->getMessage();
            $errorResponse['request'] = [
                'method' => $method,
                'endpoint' => $endpoint,
                'data' => $data,
            ];

            return $errorResponse;
        }
    }
}
