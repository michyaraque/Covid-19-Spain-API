<?php
require_once('corona_classes/downloader.php');

$down = new Download;
$down->request($down->getDownloadUrl());
$down->saveFile();