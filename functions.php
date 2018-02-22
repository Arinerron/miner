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
        $data = array();

        try {
            $msg = sendMessage('{"id":0,"jsonrpc":"2.0","method":"miner_getstat1"}');
            
            if($msg == false) {
                throw new Exception("Failed to connect.");
            }

            $json = json_decode($msg, true)['result'];
            $status = explode(';', $json[2]);

            $data['success'] = true;
            $data['version'] = $json[0];
            $data['time'] = $json[1]; // minutes
            $data['hashrate'] = $status[0] / 1000; // MH/s
            $data['shares'] = $status[1];
            $data['fails'] = $status[2];
            $data['pool'] = $json[7];

            $gpus = array();

            $i = 0;
            foreach(explode(';', $json[3]) as $hashrate) {
                $gpus[$i]['hashrate'] = $hashrate  / 1000; // MH/s
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
        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = "Failed to fetch statistics.";
        }

        return $data;
    }

    /* converts array to xml */
    function toXML($array) {
        $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><root></root>");
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $key = is_numeric($key) ? "item$key" : $key;
                $subnode = $xml->addChild("$key");
                array_to_xml($value, $subnode);
            }
            else {
                $key = is_numeric($key) ? "item$key" : $key;
                $xml->addChild("$key","$value");
            }
        }
        return $xml->asXML();
    }

    /* Dumps the data in an array in the format requested */
    function dump($array) {
        if(isset($_REQUEST['format'])) {
            $type = $_REQUEST['format'];
            if($type == 'dump') {
                header("Content-Type: text/plain");
                print_r($array); // phpdump
                die();
            } else if($type == 'xml') {
                header("Content-Type: application/xml");
                die(toXML($array)); // xml
            }
        }
        header("Content-Type: application/json");
        die(json_encode($array)); // json
    }
?>
