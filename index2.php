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
                        <td><?php echo htmlspecialchars(round($stats['shares'] / $stats['time'] * 60)); ?> shares/hour</td>
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
