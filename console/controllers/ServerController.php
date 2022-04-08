<?php
namespace console\controllers;

use console\Chat;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use yii\console\Controller;



/**
 * WebSocket Chat GuideJet.
 *
 */
class ServerController extends Controller
{
    /**
     * Start Server
     */
    public function actionRun()
    {

        $server = IoServer::factory(new HttpServer(new WsServer(new Chat())), 8809);  ///ws://79.143.22.33:8809

        //$server = IoServer::factory(new HttpServer(new WsServer(new Chat())), 8081);
        $server->run();

    }

}


