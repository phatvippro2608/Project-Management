<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MyXteamService
{
    protected $client;
    protected $apiUrl;
    protected $token;
    protected $certPath;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = env('MYXTEAM_API_URL'); // URL của API myXteam
        $this->token = env('MYXTEAM_BEARER_TOKEN');
        $this->certPath = storage_path('certs/cacert.pem'); // Đường dẫn đến file cacert.pem
    }

    public function makeRequest($method, $endpoint, $data = [])
    {
        try {
            $response = $this->client->request($method, $this->apiUrl . $endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept' => 'application/json',
                ],
                'json' => $data,
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
