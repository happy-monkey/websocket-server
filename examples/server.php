<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once dirname(__FILE__) . '/../vendor/autoload.php';

use HappyMonkey\WebSocket\Client;
use HappyMonkey\WebSocket\Message;
use HappyMonkey\WebSocket\Room;
use HappyMonkey\WebSocket\Server;
use HappyMonkey\WebSocket\ServerCore;
use HappyMonkey\WebSocket\ServerEvent;
use HappyMonkey\WebSocket\ServerEventListener;
use HappyMonkey\WebSocket\ServerEventNames;

class GameServer extends ServerEventListener
{
    use ServerCore;

    public function init()
    {
        $this->log('Interface is ready');
    }

    public function preventMysqlGoneAway()
    {
        // $this->log('Implements some code to prevent mysql gone away error');
    }

    public function onStartGame(Client &$client)
    {
        $room = new Room();
        $this->log("Room {$room->getUid()}");
        $this->attachRoom($room);
        $room->attachClient($client);
    }

    public function onJoinGame(Client &$client, $uid)
    {
        if( $room = $this->findRoom($uid) )
        {
            $room->attachClient($client);
        }
        else
        {
            $client->send(new Message('error', 'Room not found'));
        }
    }

    public function onTest(Client &$client, $data)
    {
        $client->send(new Message('action', 'Hello world 🖐'));
//        throw new Exception('Throw exception');
        return 'Hello world 🖐';
    }

    public function canClientJoin(Client &$client, $path, $query)
    {
        echo 'Connection required on path '.$path.' with query : ';
        print_r($query);
        return true;
    }

    public function catchClientError(Client &$client, Exception $exception)
    {
    }

    public function handleServerEvent(ServerEvent $event, $client, $room)
    {
        switch ( $event->getName() )
        {
            case ServerEventNames::ClientDidJoinRoom : {
                $room->send(new Message('client_join_game', $client));
                if( $room->getClientsCount()==2 )
                {
                    $room->send(new Message('success', '2 clients in room !'));
                }
                break;
            }
            case ServerEventNames::ClientDidLeaveRoom : {
                $room->send(new Message('client_leave_game', $client));
                break;
            }
        }
    }
}

try {
    $server = new Server(GameServer::create());
    $server->run('8282');
} catch ( Exception $exception ) {
    echo $exception->getMessage();
}