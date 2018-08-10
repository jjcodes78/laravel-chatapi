<?php
/**
 * Created by PhpStorm.
 * User: jjsquady
 * Date: 8/10/18
 * Time: 2:56 PM
 */

namespace ChatApi;


class ChatApi
{
    protected $endPoint;

    protected $token;

    protected $client;

    public function __construct()
    {
        $this->endPoint = config('chat_api.endpoint');
        $this->token = config('chat_api.token');

        $this->client = new \GuzzleHttp\Client(['verify' => true]);
    }

    public function sendMessage($destination, $message, array $options = [])
    {
        $response = $this->client->request('POST', $this->endPoint . '/message', [
            'query' => [
                'token' => $this->token
            ],
            'form_params' => [
                'body' => $message,
                'phone' => $destination
            ]
        ]);

    }
}