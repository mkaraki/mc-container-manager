<?php
require_once __DIR__ . '/../_config.php';
require_once __DIR__ . '/../_dockercontroller.php';
header("Content-Type: application/json; charset=utf-8");

$ret = array();

$p = getProcesses();

if ($p === null)
    die('{"error": "Could not get any informations."}');

foreach($p as $proc) {
    $name = substr($proc['Names'], strlen($container_name_prefix));
    $ret[] = array(
        'name' => $name,
        'port' => parsePort($proc, '80/tcp'),
        'state' => $proc['State'],
        'status' => $proc['Status'],
    );
}

print(json_encode($ret));