<?php
require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


$conn = [
    // Rabbitmq 服务地址
    'host' => '127.0.0.1',
    // Rabbitmq 服务端口
    'port' => '5672',
    // Rabbitmq 帐号
    'login' => 'guest',
    // Rabbitmq 密码
    'password' => 'guest',
    'vhost'=>'/'
];

//创建连接和channel
$conn = new AMQPConnection($conn);
if(!$conn->connect()) {
    die("Cannot connect to the broker!\n");
}
$channel = new AMQPChannel($conn);

// 用来绑定交换机和队列
$routingKey = 'key_1';

$ex = new AMQPExchange($channel);
//  交换机名称
$exchangeName = 'ex1';
$ex->setName($exchangeName);

// 设置交换机类型
$ex->setType(AMQP_EX_TYPE_DIRECT);
// 设置交换机是否持久化消息
$ex->setFlags(AMQP_DURABLE);
$ex->declare();

function fib($num=5) {
    if ($num == 0)
        return 1;
    else if ($num == 1)
        return 1;
    else
        return fib($num - 1) + fib($num - 2);
}

for($i=0; $i<15; $i++){
    echo "Send Message:".$ex->publish(date('H:i:s')."用户".$i."注册" , $routingKey )."\n";
    echo $i.'黄金分割：'.fib($i)."\n";
}
