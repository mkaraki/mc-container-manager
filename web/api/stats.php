<?php
require_once __DIR__ . '/../_config.php';
require_once __DIR__ . '/../_dockercontroller.php';

if (!isset($_GET['name']))
    die('{"error":"Wrong params."}');
if (!preg_match('/^[a-zA-Z0-9_]+$/', $_GET['name']))
    die('{"error":"Wrong params."}');
$name = $_GET['name'];

$container = getContainerStats($name);

if ($container == null)
    die('{"error": "Could not get any informations."}');

$res = array(
    'memperc' => rtrim($container['MemPerc'], '%') / 100,
    'memstr' => $container['MemUsage'],
    'netio' => $container['NetIO'],
    'blockio' => $container['BlockIO'],
    'cpuperc' => rtrim($container['CPUPerc'], '%') / 100,
);

// var_dump($res);

print(json_encode($res));