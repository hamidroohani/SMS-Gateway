<?php

namespace App\Services\AMQP;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQP
{

    public AMQPStreamConnection $connection;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'), env('RABBITMQ_LOGIN'), env('RABBITMQ_PASSWORD'));
    }

    public function send($queue, $message, $exchange = ''): void
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queue, false, false, false, false);

        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg, $exchange, $queue);
        $channel->close();
        $this->connection->close();
    }
}
