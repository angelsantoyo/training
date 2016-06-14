<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;


class  ReceiveController extends Controller
{
    /**
     * @Route("/receiving", name="receive_action")
     */
    public function indexAction(Request $request)
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

//        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";


        $callback = function($msg) {
            echo " [x] Received ", $msg->body, "\n";
        };
        $channel->basic_consume('hello', '', false, true, false, false, $callback);

        print_r($channel->callbacks);
//        while(count($channel->callbacks) > 0) {
//            sleep(1);
//        }
        $channel->close();
        $connection->close();
        die();

    }
}