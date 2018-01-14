<?php
    function sendMessage($in) {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            return false;
        } else {
            $result = socket_connect($socket, '127.0.0.1', 3333);
            if ($result === false) {
                return false;
            } else {
                socket_write($socket, $in, strlen($in));

                $out = '';
                while ($outmsg = socket_read($socket, 2048)) {
                    $out .= $outmsg;
                }
                socket_close($socket);

                return $out;
            }
        }
    }
?>

<html>
    <head>
        <title>Mining Monitor</title>
    </head>
    <body>
        <h1><?php echo sendMessage('{"id":0,"jsonrpc":"2.0","method":"miner_getstat1"}');?></h1>
    </body>
</html>
