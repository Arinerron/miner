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

    function getStats() {
        $json = json_decode(sendMessage('{"id":0,"jsonrpc":"2.0","method":"miner_getstat1"}'), true)['result'];

        $data = array();
        $status = explode(';', $json[2]);

        $data['version'] = $json[0];
        $data['time'] = $json[1]; // minutes
        $data['hashrate'] = $status[0]; // H/s
        $data['shares'] = $status[1];
        $data['fails'] = $status[2];
        $data['pool'] = $json[7];

        $gpus = array();

        $i = 0;
        foreach(explode(';', $json[3]) as $hashrate) {
            $gpus[$i] = array('hashrate'=>$hashrate);
            $i++;
        }

        $i = 0;
        $switch = false;
        foreach(explode(';', $json[6]) as $point) {
            if($switch) {
                $gpus[$i]['fan'] = $point;
                $switch = false;
                $i++;
            } else {
                $gpus[$i]['temperature'] = $point;
                $switch = true;
            }
        }

        return $data;
    }
?>

<html>
    <head>
        <title>Mining Monitor</title>
    </head>
    <body>
        <h1><?php echo print_r(getStats());?></h1>
    </body>
</html>
