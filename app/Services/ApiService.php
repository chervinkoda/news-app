<?php

namespace App\Services;

use GuzzleHttp\Client as GuzzleClient;

class ApiService
{
    public function searchGuardianArticle($searchQuery, $page)
    {
        $apiKey = config('services.guardian.api_key');
        $endpoint = config('services.guardian.endpoint');
        $guzzleClient = new GuzzleClient(['verify' => false]);
        $response = $guzzleClient->get($endpoint, [
            'query' => [
                'page' => $page,
                'q' => $searchQuery,
                'api-key' => $apiKey,
            ],
        ]);
        $data = json_decode($response->getBody(), true);

        return $data;
    }
}
