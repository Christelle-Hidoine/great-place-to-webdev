<?php

namespace App\Services;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GoogleApi
{
    /**
     * Clé pour accéder à l'API
     *
     * @var string
     */
    private $apiKey;

    /**
     * Service client HTTP
     *
     * @var HttpClientInterface
     */
    private $client;

    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    public function fetch(string $city, string $country)
    {
        $response = $this->client->request(
            'GET',
            'https://www.google.com/maps/embed/v1/place', [
                'query' => [
                    'q' => $city . '+' . $country,
                    'key' => $this->apiKey,
                ],
            ]
        );

        // Check for a successful response (status code 200)
        if ($response->getStatusCode() === 200) {

            $url = $response->getInfo('url');

            return $url;
            
        }

        return null;
    }
}