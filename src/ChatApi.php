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
        $data = $this->buildFormRequest([
            'body' => $message,
            'phone' => $destination
        ]);

        $response = $this->client->request('POST', $this->endPoint . '/message', $data);

    }

    public function setWebhook($url)
    {
        $data = $this->buildFormRequest([
            'webhookUrl' => $url
        ]);

        $this->client->request('POST', $this->endPoint . '/webhook', $data);
    }

    public function getWebhook()
    {
        $response = $this->client->request('GET', $this->endPoint . '/webhook');

        return json_encode($response->getBody()->getContents());
    }

    public function logout()
    {
        $response = $this->client->request('GET', $this->endPoint . '/logout');

        return json_encode($response->getBody()->getContents());
    }

    public function reboot()
    {
        $response = $this->client->request('GET', $this->endPoint . '/reboot');

        return json_encode($response->getBody()->getContents());
    }

    /**
     * @param null $lastMessageNumber
     * @return string
     */
    public function getMessages($lastMessageNumber = null)
    {
        $query = $lastMessageNumber ?
            "?token={$this->token}&lastMessageNumber={$lastMessageNumber}" :
            "?token={$this->token}&last";

        $response = $this->client->request('GET', $this->endPoint . '/messages' . $query);

        return json_encode($response->getBody()->getContents());
    }

    protected function buildFormRequest(array $body)
    {
        return [
            'query' => [
                'token' => $this->token
            ],
            'form_params' => $body
        ];
    }
}