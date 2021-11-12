<?php
require_once __DIR__ . '/_config.php';

function getProcesses()
{
    global $container_name_prefix;

    exec('sudo docker ps -sa --format "{{json .}}" 2>&1', $res, $exitcode);
    
    if ($exitcode !== 0)
        die(json_encode(array('error'=>"docker exited with $exitcode", 'info' => implode(' ', $res))));

    $ret = array();

    foreach($res as $l)
    {
        $v = json_decode($l, true);
        if (str_starts_with($v['Names'], $container_name_prefix))
            $ret[] = $v;
    }

    return $ret;
}

function getContainerTtyLog($name, $line, $after)
{
    global $container_name_prefix;

    exec("sudo docker logs $container_name_prefix$name | tail --lines=+$after | tail -n $line", $res, $exitcode);

    if ($exitcode !== 0)
        die(json_encode(array('error'=>"docker exited with $exitcode", 'info' => implode(' ', $res))));

    return $res;
}

function getContainerStats($name)
{
    global $container_name_prefix;

    exec("sudo docker stats --format '{{json .}}' --no-stream $container_name_prefix$name 2>&1", $res, $exitcode);

    if ($exitcode !== 0)
        die(json_encode(array('error'=>"docker exited with $exitcode", 'info' => implode(' ', $res))));

    $res = implode("\n", $res);

    $data = json_decode($res, true);

    return $data;
}

function getContainerInfo($name)
{
    global $container_name_prefix;

    exec("sudo docker inspect $container_name_prefix$name 2>&1", $res, $exitcode);

    if ($exitcode !== 0)
        die(json_encode(array('error'=>"docker exited with $exitcode", 'info' => implode(' ', $res))));

    $res = implode("\n", $res);

    $data = json_decode($res, true);

    return $data[0];
}

function runExecCommand($name, $cmd) {
    global $container_name_prefix;

    exec("sudo docker exec --user minecraft $container_name_prefix$name $cmd 2>&1", $res, $exitcode);

    if ($exitcode !== 0)
        die(json_encode(array('error'=>"docker exited with $exitcode", 'info' => implode(' ', $res))));

    return true;
}

function getEnvsFromContainerInfo($cinfo)
{
    $data = array();
    foreach($cinfo['Config']['Env'] as $v)
    {
        $env = explode('=', $v);
        switch($env[0])
        {
            case "EULA":
            case "PATH":
            case "LANG":
            case "LANGUAGE":
            case "LC_ALL":
            case "JAVA_HOME":
            case "UID":
            case "GID":
                continue 2;
        }
        $data[$env[0]]=$env[1];
    }

    return $data;
}

function parsePort($proc, $port)
{
    foreach(explode(',', $proc['Ports']) as $p)
    {
        $pstr = trim($p);
        if (!str_ends_with($pstr, $port))
            continue;

        $fromandto = explode('->', $pstr);

        $fromportproctemp = explode(':', $fromandto[0]);
        $fromport = $fromportproctemp[count($fromportproctemp) - 1];

        return intval($fromport);
    }
}