#!/usr/bin/php
<?php
/**
 * Cruft flake - simple ZMQ req/rep loop
 * 
 * Usage:
 * 
 *  -u      ZeroMQ url to bind to, default 'tcp://*:5599'
 *  -z      ZooKeeper hostname:port to connect to, eg: localhost:2181
 *  -m      Specify a particular machine ID (If specified, -z will be ignored)
 */

$opts = getopt('u:z:m:');
$url = isset($opts['u']) ? $opts['u'] : 'tcp://*:5599';
$zks = isset($opts['z']) ? $opts['z'] : 'localhost:2181';
$machine = isset($opts['m']) ? $opts['m'] : NULL;

include_once __DIR__ . '/../vendor/autoload.php';

$timer = new \Davegardnerisme\CruftFlake\Timer;
if ($machine !== NULL) {
    $config = new \Davegardnerisme\CruftFlake\FixedConfig($machine);
} else {
    $config = new \Davegardnerisme\CruftFlake\ZkConfig($zks);
}
$generator = new \Davegardnerisme\CruftFlake\Generator($config, $timer);
$zmqRunner = new \Davegardnerisme\CruftFlake\ZeroMq($generator, $url);

$zmqRunner->run();
