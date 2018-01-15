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
            $gpus[$i]['hashrate'] = $hashrate;
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

        $data['gpus'] = $gpus;

        return $data;
    }
?>

<html>
    <head>
        <title>Mining Monitor</title>
        <style>
            body {
                background-color: black;
                color: green;
                font-family: monospace;
            }

            .container {
                width: 60%;
                margin: 0 auto;
            }

            #right {
                text-align: right;
            }
        </style>
    </head>
    <body>
        <center>
            <div class="container">
                <?php $stats = getStats(); ?>
                <h1>Mining Monitor</h1><br><hr><nr>
                <h2>Statistics</h2>
                <table>
                    <tr>
                        <th id="right">Hashrate</th>
                        <td><?php echo htmlspecialchars($stats['hashrate'] / 1000); ?> MH/s</td>
                    </tr>
                    <tr>
                        <th id="right">Shares/Hour</th>
                        <td><?php echo htmlspecialchars($stats['shares'] / $stats['time'] * 60); ?> shares/hour</td>
                    </tr>
                    <tr>
                        <th id="right">Failures</th>
                        <td><?php echo htmlspecialchars($stats['fails']); ?> fails</td>
                    </tr>
                </table><br><br>

                <h2>Graphics Cards</h2>
                <table>
                    <tr>
                        <th>GPU</th>
                        <th>Hashrate</th>
                        <th>Temp.</th>
                        <th>Fan</th>
                    </tr>
                    <?php
                        $i = 0;
                        foreach($stats['gpus'] as $gpu) {
                            $i++;
                            echo '<tr><td>#' . $i . '</td><td>' . ($gpu['hashrate'] / 1000) . ' MH/s</td><td' . ($gpu['temperature'] >= 85 ? ' style="color: red;"' : '') . '>' . $gpu['temperature'] . '°C</td><td' . ($gpu['fan'] >= 80 ? ' style="color: orange;"' : '') . '>' . $gpu['fan'] . '%</td></tr>';
                        }
                    ?>
                </table>
            </div>
        </center>
    </body>
</html>
