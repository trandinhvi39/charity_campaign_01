<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Contracts\Services\GoogleInterface;

class GoogleMap
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client;
    }

    private function getUrl($path, $parameters = [])
    {
        $googleApiUrl = env('GOOGLE_MAP_URL');

        return $googleApiUrl . '/' . $path . '?' . http_build_query($parameters);
    }

    public function getAddress(array $params)
    {
        $params = [
            'address' => $params['address'],
            'sensor' => false,
        ];
        $response = $this->client->get($this->getUrl('maps/api/geocode/json', $params));
        $response = json_decode($response->getBody());

        if ($response->status == 'OK') {
            $location = $response->results[0]->geometry->viewport->northeast;

            return [
                'latitude' => $location->lat,
                'longitude' => $location->lng,
            ];
        }

        return null;
    }
}
