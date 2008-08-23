<?php
/**
*   Using the dispatcher class
*/
require_once 'Event/SignalEmitter/Dispatcher.php';

$dp = Event_SignalEmitter_Dispatcher::singleton();
$dp->register_signal('download-begin');
$dp->register_signal('download-progress');
$dp->register_signal('download-complete');

class Logger
{
    public function log() {
        $args = func_get_args();
        echo 'Log: ' . implode(' - ', $args) . "\n";
    }
}

class Downloader
{
    public function download($file) {
        echo "Beginning download\n";
        //simulate some downloading process
        $dp = Event_SignalEmitter_Dispatcher::singleton();
        for ($nA = 0; $nA < 100; $nA += 10) {
            $dp->emit('download-progress', $file, $nA);
        }
        $dp->emit('download-complete', $file);
    }
}


$l = new Logger();
$d = new Downloader();

$dp->connect_simple('download-begin'   , array($l, 'log'), 'begin download');
$dp->connect_simple('download-progress', array($l, 'log'), 'progress');
$dp->connect_simple('download-complete', array($l, 'log'), 'download complete');
$dp->connect_simple('download-begin'   , array($d, 'download'));


$dp->emit('download-begin', 'http://some.where/over/the/rainbow.htm');
?>