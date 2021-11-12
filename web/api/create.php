<?php
require_once __DIR__ . '/../_config.php';
header("Content-Type: application/json; charset=utf-8");

/*
This API will create container:
    https://github.com/itzg/docker-minecraft-server
*/

/*

POST /create-container.php

params:
- name (container name)
- port (forward port)
- eula (must be 'TRUE')
- rconport (optional, rcon forward port)
- volume (optional, if set, volume will create at `$volume_base_path/name`)
- env-* (optional, environment variables)

*/

if (!isset($_POST['name'], $_POST['port']))
{
    die('{"error":"Wrong params."}');
}

if (!isset($_POST['eula']) || $_POST['eula'] !== 'TRUE')
    die('{"error":"You have to accept Minecraft EULA. Read https://account.mojang.com/documents/minecraft_eula and decide to accept."}');

if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['name']))
    die('{"error":"Couldn\'t use that name."}');

$_POST['name'] = strtolower($_POST['name']);

function validatePort($port)
{
    return is_numeric($port) && ($port >= 1 && $port <= 65535);
}

if (!validatePort($_POST['port']))
    die('{"error":"port config is wrong"}');

if (isset($_POST['rconport']) && !validatePort($_POST['rconport']))
    die('{"error":"rconport config is wrong"}');

$conf = array(
    "name" => $_POST['name'],
    "containerName" => $container_name_prefix . $_POST['name'],
    "port" => intval($_POST['port']),
    "rcon" => isset($_POST['rconport']),
    "volume" => true, // isset($_POST['volume']),
    "env" => array(
        "EULA" => 'TRUE',
        'INIT_MEMORY' => '32M',
    )
);

if ($conf['rcon'])
    $conf['rconport'] = intval($_POST['rconport']);

foreach($_POST as $name => $value) {
    if (!preg_match('/^env-([A-Z0-9][A-Z0-9_]*$)/', $name, $valname))
    continue;
    
    if (empty($value))
    continue;
    
    $conf['env'] += array($valname[1] =>$value);
}

$docker_cmds = [
    'sudo docker',
    'run',
    '-i',
    '-d',
    '-t',
    '--name ' . $conf['containerName'],
    '-p ' . $conf['port'] . ':' . '25565'
];

if ($conf['rcon'])
    $docker_cmds[] = '-p ' . $conf['rconport'] . ':' . '25575';

foreach($conf['env'] as $name => $val)
    $docker_cmds[] = '-e ' . $name . '=' . $val;

if ($conf['volume'] && is_dir($watch_volume_base_path . '/' . $conf['name']))
    die('{"error":"You have to delete old server data first."}');
elseif ($conf['volume'])
    $docker_cmds[] = '-v ' . ($volume_base_path . '/' . $conf['name']) . ':' . ('/data');

$docker_cmds[] = 'itzg/minecraft-server';

exec(implode(' ', $docker_cmds) . ' 2>&1', $res, $ecode);

if ($ecode != 0)
    die(json_encode(array('error'=>"docker exited with $ecode", 'info' => implode(' ', $res), 'command' => implode(' ', $docker_cmds))));

print (json_encode($conf));