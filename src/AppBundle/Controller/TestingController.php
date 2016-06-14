<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class TestingController extends Controller
{
    /**
     * @Route("/testing", name="testing_list")
     */
    public function indexAction(Request $request)
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        $msg = new AMQPMessage('Hello World!');
        $channel->basic_publish($msg, '', 'hello');
        $result = array("msg" => "[x] Sent 'Hello World!'");

        $channel->close();
        $connection->close();

        return $this->render('testing/index.html.twig',
            array('testings' => $result)
        );

    }
}