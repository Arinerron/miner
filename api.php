<?php
    include_once "functions.php";

    if(isset($_REQUEST['endpoint'])) {
        switch (strtolower($_REQUEST['endpoint'])) {
            case 'getstats':
                dump(getStats());
                break;
            case 'setpower':
                $lockfile = '/tmp/stopminer.lock';
                if(!isset($_REQUEST['on']) || $_REQUEST['on'] === 'true') {
                    // on
                    if(file_exists($lockfile)) {
                        unlink($lockfile);
                    }
                } else {
                    // off
                    if(!file_exists($lockfile)) {
                        fopen($lockfile, "w");
                    }
                }

                dump(array('success'=>true));

                break;
            default:
                dump(array('success'=>false, 'message'=>"Unknown endpoint."));
        }
    } else {
        dump(array('success'=>false, 'message'=>"No endpoint specified."));
    }
?>
