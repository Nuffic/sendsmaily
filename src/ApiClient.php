<?php

namespace nuffic\sendsmaily;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class ApiClient extends Configurable {

    public $baseUrl;
    public $apiUser;
    public $apiPassword;
    public $apiKey;

    private $client;

    public function getClient() {
        if(!isset($this->client)) {
            $this->client = new Client([
                'base_uri' => $this->baseUrl,
                #'auth' => [$this->apiUser, $this->apiPassword]
            ]);
        }
        return $this->client;
    }

    public function addSubcscriber($email) {
        $payload = [
            'key'       => $this->apiKey,
            'email'       => $email,
        ];

        die(var_dump($this->sendRequest('contact.php', $payload)));
    }

    private function sendRequest($resource, array $payload) {
        try {
            return $this->getClient()->post(
                $resource,
                [
                    'body'    => $payload
                ]
            )->getBody()->getContents();
        } catch (BadResponseException $e) {
            return $e->getResponse()->getBody()->getContents();
        }
    }
}