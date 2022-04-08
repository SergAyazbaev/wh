<?php
namespace console\controllers;

//namespace yii\console\controllers;

//use app\servers\AppServer;
//use yii\console\Controller;

//use Ratchet\Http\HttpServer;
//use Ratchet\WebSocket\WsServer;
//use Ratchet\Server\IoServer;
use yii\console\Controller;
use yii\console\controllers\AppServer;


class AppController extends Controller
{
    public $io_port = 6380;
//    public $io_port = 8809;


    /**
     * Start a Web Soket server
     * @return null
     */
//    function setInstance() {
//        $server = IoServer::factory(
//            new HttpServer(
//                new WsServer(
//                    new AppServer()
//                )
//            ),
//            $this->io_port
//        );
//        $server->run();
//    }
}