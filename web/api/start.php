<?php
require_once __DIR__ . '/../_config.php';

if (!isset($_POST['name']))
{
    die('{"error":"Wrong params."}');
}

if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['name']))
    die('{"error":"Couldn\'t use that name."}');

$_POST['name'] = strtolower($_POST['name']);

exec("sudo docker start $container_name_prefix" . $_POST['name'] . ' 2>&1', $res, $ecode);

if ($ecode != 0)
    die(json_encode(array('error'=>"docker exited with $ecode", 'info' => implode(' ', $res))));

print ('{"result":"ok"}');
