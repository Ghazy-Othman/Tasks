<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Message;
use GuzzleHttp\Client;

class ChatBotService
{
    /**
     * 
     * @var array
     */
    protected $config;

    /**
     * 
     * @var Client
     */
    protected $client;

    /**
     * Current Model 
     * @var string
     */
    protected $MODEL = "deepseek/deepseek-r1:free";

    /** **/
    public function __construct()
    {
        $this->config = [
            "token" => env("AI_TOKEN"),
            "base_url" => env("BASE_URL"),
        ];
        $this->client = new Client();
    }

    /**
     * Send new message to AI boot 
     * @param int $chat_id
     * @param string $content
     * @param bool $with_history
     * @return Message
     */
    public function sendMessage(int $chat_id, string $content, $prefix_content, bool $with_history = true)//: Message
    {

        //
        $messages = $prefix_content;
        if ($with_history == true) {
            $messages = array_merge($messages, $this->getChatHistory($chat_id));
        }

        array_push($messages,  [
            'role' => 'user',
            'content' => $content
        ]);

        //
        $headers = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $this->config["token"]
        ];

        //
        $body = [
            'model' => $this->MODEL,
            'messages' => $messages
        ];

        $body = json_encode(value: $body, flags: true);

        //
        $response = $this->client->request("POST", $this->config['base_url'], [
            'headers' => $headers,
            'body' => $body
        ]);

        //
        Message::create([
            'chat_id' => $chat_id,
            'role' => 'user',
            'content' => $content
        ]);

        //  
        $bot_message = $this->getBotMessage(
            response: json_decode(json: $response->getBody(), associative: true)['choices'][0],
            chat_id: $chat_id
        );

        //
        return $bot_message;
    }

    /**
     * Get AI bot response message
     * @param string $response
     * @param int $chat_id
     * @return Message
     */
    public function getBotMessage($response, $chat_id): Message
    {
        $bot_message = $response['message'];

        $message = Message::create(attributes: [
            'chat_id' => $chat_id,
            'role' => $bot_message['role'],
            'content' => $bot_message['content'],
        ]);

        return $message;
    }

    /**
     * Get user chat history 
     * @param int $chat_id
     * @return array
     */
    public function getChatHistory(int $chat_id): array
    {
        $chat = Chat::find($chat_id);
        $messages = $chat->messages;

        $chat_history = [];

        foreach ($messages as $message) {
            array_push( $chat_history, [
                'role' => $message->role,
                'content' => $message->content
            ]);
        }

        return $chat_history;
    }
}
