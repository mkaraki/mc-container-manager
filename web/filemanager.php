<?php
require_once __DIR__ . '/_config.php';

header("HTTP/1.1 301 Moved Permanently");
if (isset($_GET['name']) && preg_match('/^[a-zA-Z0-9_]+$/', $_GET['name']))
    header("Location: $filemanager_url?p=" . $_GET['name']);
else
    header("Location: $filemanager_url");