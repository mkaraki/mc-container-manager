<?php
require_once __DIR__ . '/../_config.php';
require_once __DIR__ . '/../_dockercontroller.php';
header("Content-Type: application/json; charset=utf-8");

if (!isset($_GET['name']))
    die('{"error":"Wrong params."}');
if (!preg_match('/^[a-zA-Z0-9_]+$/', $_GET['name']))
    die('{"error":"Wrong params."}');
$name = $_GET['name'];

$container = getContainerInfo($name);

if ($container == null)
    die('{"error": "Could not get any informations."}');

$ret = array(
    "env" => getEnvsFromContainerInfo($container),
    "healthy" => ($container['State']['Health']['Status'] == 'healthy'),
    'log' => array(),
    'exposedPorts' => array(),
    'volume' => ($container['Mounts'][0]['Type'] == 'bind')
);

foreach($container['State']['Health']['Log'] as $log)
{
    $ret['log'][] = array('time' => $log['Start'], 'code' => $log['ExitCode'], 'message' => $log['Output']);
}

foreach($container['NetworkSettings']['Ports'] as $port => $portmaps)
{
    if ($portmaps == null) continue;
    $maps = array();
    foreach($portmaps as $hostport)
    {
        $maps[] = array(
            'ip' => $hostport['HostIp'],
            'port' => $hostport['HostPort']
        );
    }
    $ret['exposedPorts'][$port] = $maps;
}

//var_dump($container);
//var_dump($ret);

print(json_encode($ret));