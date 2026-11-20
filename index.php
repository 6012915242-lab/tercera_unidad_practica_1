<?php

session_start();

require_once "Config/Config.php";
require_once "Libraries/Core/Autoload.php";

$url = $_GET["url"] ?? "home";
$parts = array_values(array_filter(explode("/", trim($url, "/")), "strlen"));

// Solo se aceptan nombres alfanuméricos para controlador y método.
$controller = ucfirst(preg_replace('/[^A-Za-z0-9_]/', '', $parts[0] ?? "home"));
$method = preg_replace('/[^A-Za-z0-9_]/', '', $parts[1] ?? "index");
$param = $parts[2] ?? null;

require_once "Libraries/Core/Load.php";
