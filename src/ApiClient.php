<?php

namespace nuffic\sendsmaily;

use GuzzleHttp\Client;

class ApiClient extends Configurable
{

    public $baseUrl;
    public $apiUser;
    public $apiPassword;

    private $_client;

    public function getClient()
    {
        if (!isset($this->_client)) {
            $this->_client = new Client([
                'base_uri' => $this->baseUrl,
                'auth' => [$this->apiUser, $this->apiPassword]
            ]);
        }
        return $this->_client;
    }

    public function addSubscriber($email, array $additionalParams = [])
    {
        $payload = [
            'email' => $email,
            'remote' => 1,
        ];
        $payload = array_merge_recursive($payload, $additionalParams);
        return json_decode($this->sendRequest('contact.php', $payload));
    }

    public function triggerAutoresponder($responderId, $email, array $additionalParams = [])
    {
        $addresses = [
            'email' => $email,
        ];
        $addresses = array_merge($addresses, $additionalParams);

        $payload = [
            'autoresponder' => $responderId,
            'addresses' => [
                $addresses
            ],
        ];
        return json_decode($this->sendRequest('autoresponder.php', $payload));
    }

    private function sendRequest($resource, array $payload)
    {
        return $this->getClient()->post(
            $resource,
            ['form_params'    => $payload]
        )->getBody()->getContents();
    }
}
