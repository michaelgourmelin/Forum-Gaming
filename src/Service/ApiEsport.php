<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiEsport
{
    private $client;
    private $apikey;

    /**
     * Dépendances nécessaires au fonctionnement de OmdbApi
     */
    public function __construct(HttpClientInterface $client, string $apikey)
    {
        $this->client = $client;
        $this->apikey = $apikey;
    }


    public function fetchValorantTournament(): JsonResponse
    {
        // @see https://symfony.com/doc/5.4/http_client.html
        $response = $this->client->request('GET', 'https://api.pandascore.co/videogames/valorant/tournaments', [

            'headers' => [
                'Accept' => 'application/json',
                'Authorization: Bearer iLAeT9OrdVeS8eBodzDmoxzkS1elXFo4UwP0uo3nzNjOqeujlqs'
            ],
        ]);

        $content = $response->toArray();

        // Transformez le tableau associatif en une réponse JSON
        return new JsonResponse($content);
    }




    public function fetchgame(string $identifier): JsonResponse
    {
        // Vérifiez si l'identifiant est un nombre (ID) ou une chaîne (slug)
        if (is_numeric($identifier)) {
            // Si c'est un nombre, construisez l'URL avec l'ID
            $url = 'https://api.rawg.io/api/games/' . $identifier . '?key=' . $this->apikey;
        } else {
            // Sinon, construisez l'URL avec le slug
            $url = 'https://api.rawg.io/api/games/' . $identifier . '?key=' . $this->apikey;
        }

        // Utilisez l'URL construite pour effectuer la requête
        $response = $this->client->request('GET', $url);

        $content = $response->toArray();

        // Transformez le tableau associatif en une réponse JSON
        return new JsonResponse($content);
    }
}
