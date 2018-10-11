<?php
    require dirname(__DIR__) . '/vendor/autoload.php';

    use Ratchet\Server\IoServer;
    use Ratchet\Http\HttpServer;
    use Ratchet\WebSocket\WsServer;
    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;

    class Chat implements MessageComponentInterface {
        protected $users;

        public function __construct() {
            $this->users = new \SplObjectStorage;
        }

        public function onOpen(ConnectionInterface $conn) {
            // Store the new connection to send messages to later
            $this->users->attach($conn);
            
            echo "New connection! ({$conn->resourceId})\n";
        }

        public function onMessage(ConnectionInterface $from, $msg) {
            $numRecv = count($this->users) - 1;
            echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
                , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

            foreach ($this->users as $client) {
                /*if ($from !== $client) {
                    // The sender is not the receiver, send to each client connected
                    $client->send($msg);
                }*/
                $client->send($msg);
            }
        }

        public function onClose(ConnectionInterface $conn) {
            // The connection is closed, remove it, as we can no longer send it messages
            $this->users->detach($conn);
            
            echo "Connection {$conn->resourceId} has disconnected\n";
        }

        public function onError(ConnectionInterface $conn, \Exception $e) {
            echo "An error has occurred: {$e->getMessage()}\n";

            $conn->close();
        }
    }

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat()
            )
        ),
        3000
    );

    $server->run();
?>