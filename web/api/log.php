<?php
require_once __DIR__ . '/../_config.php';
require_once __DIR__ . '/../_dockercontroller.php';

if (!isset($_GET['name']))
    die('{"error":"Wrong params."}');
if (!preg_match('/^[a-zA-Z0-9_]+$/', $_GET['name']))
    die('{"error":"Wrong params."}');

if (isset($_GET['after']) && !is_numeric($_GET['after']))
    die('{"error":"Wrong params: `after`."}');

if (isset($_GET['last']) && !is_numeric($_GET['last']))
    die('{"error":"Wrong params: `last`."}');

$after = $_GET['after'] ?? 0;
$last = $_GET['last'] ?? 50;

$name = $_GET['name'];

$log = getContainerTtyLog($name, $last, $after);

$ret = array(
    'loglen' => count($log),
    'log' => implode("\n", $log),
);

print(json_encode($ret));
