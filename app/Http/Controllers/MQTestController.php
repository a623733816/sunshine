<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MQTestController extends Controller
{
    /**
     * 发送消息
     */
    public function send()
    {
        //建立一个连接通道，声明一个可以发送消息的队列hello
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('hello', false, false, false, false);

        //定义一个消息，消息内容为Hello World!
        $msg = new AMQPMessage('Hello World!');
        $channel->basic_publish($msg, '', 'hello');

        //发送完成后打印消息告诉发布消息的人：发送成功
        echo " [x] Sent 'Hello World!'\n";
        //关闭连接
        $channel->close();
        $connection->close();


    }

    /**
     * 接收消息
     */
    public function reveive()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
        $callback = function ($msg) {
            echo " [x] Received ", $msg->body, "\n";
        };

        //在接收消息的时候调用$callback函数
        $channel->basic_consume('hello', '', false, true, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }
}
