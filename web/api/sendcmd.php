<?php
require_once __DIR__ . '/../_config.php';
require_once __DIR__ . '/../_dockercontroller.php';

if (!isset($_POST['name']) || !isset($_POST['cmd']))
    die('{"error":"Wrong params."}');
if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['name']))
    die('{"error":"Wrong params."}');

$log = runExecCommand($_POST['name'], "mc-send-to-console " . $_POST['cmd']);

print('{"result":"ok"}');
