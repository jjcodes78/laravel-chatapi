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
    /**
     * @var string
     */
    protected $endPoint;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * ChatApi constructor.
     */
    public function __construct()
    {
        $this->endPoint = config('chat_api.endpoint');
        $this->token = config('chat_api.token');

        $this->client = new \GuzzleHttp\Client(['verify' => true]);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        $response = $this->client->request('GET', $this->endPoint . '/status');

        return $response->getBody()->getContents();
    }

    /**
     * @return string
     */
    public function getQRCode()
    {
        $response = $this->client->request('GET', $this->endPoint . '/qr_code');

        return $response->getBody()->getContents();
    }

    /**
     * @param $groupName
     * @param array $destinations
     * @param $message
     * @return string
     */
    public function sendGroupMessage($groupName, array $destinations, $message)
    {
        $data = $this->buildFormRequest([
            'groupName' => $groupName,
            'messageText' => $message,
            'phones' => $destinations
        ]);

        $response = $this->client->request('POST', $this->endPoint . '/group', $data);

        return $response->getBody()->getContents();
    }

    /**
     * @param $destination
     * @param $message
     * @return string
     */
    public function sendMessage($destination, $message)
    {
        /*
         * chatId parameter its not been used for now
         * See docs: https://app.chat-api.com/docs
         */
        $data = $this->buildFormRequest([
            'body' => $message,
            'phone' => $destination
        ]);

        $response = $this->client->request('POST', $this->endPoint . '/sendMessage', $data);

        return $response->getBody()->getContents();

    }

    /**
     * @param $destination
     * @param $content
     * @param $filename
     * @return string
     */
    public function sendFile($destination, $content, $filename)
    {
        $data = $this->buildFormRequest([
            'body' => $content,
            'phone' => $destination,
            'filename' => $filename
        ]);

        $response = $this->client->request('POST', $this->endPoint . '/sendFile', $data);

        return $response->getBody()->getContents();
    }

    /**
     * @param null $lastMessageNumber
     * @return string
     */
    public function getMessages($lastMessageNumber = null)
    {
        $query = $lastMessageNumber >= 0 ?
            "?token={$this->token}&lastMessageNumber={$lastMessageNumber}" :
            "?token={$this->token}&last";

        $response = $this->client->request('GET', $this->endPoint . '/messages' . $query);

        return $response->getBody()->getContents();
    }

    /**
     * @param $url
     */
    public function setWebhook($url)
    {
        $data = $this->buildFormRequest([
            'webhookUrl' => $url
        ]);

        $this->client->request('POST', $this->endPoint . '/webhook', $data);
    }

    /**
     * @return string
     */
    public function getWebhook()
    {
        $response = $this->client->request('GET', $this->endPoint . '/webhook');

        return $response->getBody()->getContents();
    }

    /**
     * @return string
     */
    public function logout()
    {
        $response = $this->client->request('GET', $this->endPoint . '/logout');

        return $response->getBody()->getContents();
    }

    /**
     * @return string
     */
    public function reboot()
    {
        $response = $this->client->request('GET', $this->endPoint . '/reboot');

        return $response->getBody()->getContents();
    }

    /**
     * @return string
     */
    public function getMessagesQueue()
    {
        $response = $this->client->request('GET', $this->endPoint . '/showMessagesQueue');

        return $response->getBody()->getContents();
    }

    /**
     * @return string
     */
    public function clearMessagesQueue()
    {
        $response = $this->client->request('GET', $this->endPoint . '/clearMessagesQueue');

        return $response->getBody()->getContents();
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