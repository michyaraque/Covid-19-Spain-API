<?php
require_once('src/coronavirus.php');
header('Content-Type: application/json');
$coronapi = new Coronavirus;

if(!empty($_GET['type']) && in_array($_GET['type'], ['general', 'all'])) {
    $type = $_GET['type'];
    $cm_autonoma = null;
} elseif (!empty($_GET['type']) && $_GET['type'] == 'ca') {
    $type = "ca";
    if(!empty($_GET['ca']) && is_string($_GET['ca'])) {
        $cm_autonoma = $_GET['ca'];
    } else {
        $cm_autonoma = null;
    }
} else {
    $type = 'hint';
    $cm_autonoma = null;
}

echo $coronapi->getCases($type, $cm_autonoma);
