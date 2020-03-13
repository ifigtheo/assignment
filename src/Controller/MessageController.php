<?php


namespace App\Controller;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Wire\AMQPTable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{

    private $hostname;

    private $port;

    private $username;

    private $password;

    private $exchange;

    private $queue;

    public function __construct()
    {

        $this->hostname = $_ENV['MESSAGE_QUEUE_HOSTNAME'];
        $this->port = $_ENV['MESSAGE_QUEUE_PORT'];
        $this->username = $_ENV['MESSAGE_QUEUE_USERNAME'];
        $this->password = $_ENV['MESSAGE_QUEUE_PASSWORD'];
        $this->exchange = $_ENV['MESSAGE_QUEUE_EXCHANGE'];
        $this->queue = $_ENV['MESSAGE_QUEUE'];

    }

    public function sendMessage($message)
    {

        $connection = new AMQPStreamConnection(
            $this->hostname,
            $this->port,
            $this->username,
            $this->password
        );

        $channel = $connection->channel();

        $ttl = new AMQPTable(["x-message-ttl" => 60000,]);

        list ($queue, $messageCount, $consumerCount) = $channel->queue_declare($this->queue, true, true, false, false, false, $ttl);

        // Message is a concatenation of value and timestamp.
        $msg = implode('', array_slice($message['body'], 0));
        $msg = new AMQPMessage($msg);

        $channel->queue_bind($queue, $this->exchange, $message['routingKey']);

        $channel->basic_publish($msg, $this->exchange, $message['routingKey']);

        $channel->close();
        $connection->close();

    }

    public function receiveMessage()
    {

        $connection = new AMQPStreamConnection(
            $this->hostname,
            $this->port,
            $this->username,
            $this->password
        );

        $channel = $connection->channel();

        $ttl = new AMQPTable(["x-message-ttl" => 60000,]);

        list ($queue, $messageCount, $consumerCount) = $channel->queue_declare($this->queue, true, true, false, false, false, $ttl);

        $callback = function ($msg) {
            // Message is a concatenation of value and timestamp.
            // The timestamp consist of the last 13 numbers of the message, while the rest of them make up the value.
            $value = substr($msg->body, 0, ($msg->body_size) - 13);
            $timestamp = substr($msg->body, ($msg->body_size) - 13);
            $routingKey = $msg->delivery_info['routing_key'];

            $this->redirectToRoute('save_data', [
                'value' => $value, 'timestamp' => $timestamp, 'routingKey' => $routingKey
            ]);

        };

        $channel->queue_bind($queue, $this->exchange);
        while (1) {

            $channel->basic_consume($queue, '', false, true, false, false, $callback);

            if (count($channel->callbacks)) {
                $channel->wait();
            }

        }

        $channel->close();
        $connection->close();

    }

}