<?php
require_once('src/coronavirus.php');
header('Content-Type: application/json');

$coronapi = new Coronavirus('coronavirus/');
echo $coronapi->router();
