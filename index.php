<?php
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
    } else {
        $result = socket_connect($socket, '127.0.0.1', 3333);
        if ($result === false) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
        } else {
            $in = '{"id":0,"jsonrpc":"2.0","method":"miner_getstat1"}';
            socket_write($socket, $in, strlen($in));
            while ($out = socket_read($socket, 2048)) {
                echo $out;
            }
            socket_close($socket);
        }
    }
?>

<html>
    <head>
        <title>Mining Monitor</title>
    </head>
    <body>
    </body>
</html>
