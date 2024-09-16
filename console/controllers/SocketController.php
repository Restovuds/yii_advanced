<?php
namespace console\controllers;

use backend\daemons\WSServer;
use consik\yii2websocket\WebSocketServer;
use yii\console\Controller;

class SocketController extends Controller
{
    public function actionStart($port = null)
    {
        $server = new WSServer();
        $server->port = ($port ? $port : 8085);
        echo $server->port;

        $server->on(WebSocketServer::EVENT_WEBSOCKET_OPEN_ERROR, function($e) use($server) {
            echo "Error opening port " . $server->port . "(" . $e->exception->getMessage() . ")" . "\n";
        });

        $server->on(WebSocketServer::EVENT_CLIENT_ERROR, function($e) use($server) {
            echo "Error " . $e->exception->getMessage() . "\n";
        });

        $server->on(WebSocketServer::EVENT_WEBSOCKET_OPEN, function($e) use($server) {
            echo "Server started at port " . $server->port . "\n";
        });

//        $server->on(NotificationServer::EVENT_CLIENT_CONNECTED, function($e) use ($server) {
//            echo "\nClient connected!\n";
//        });

        $server->start();
    }
}