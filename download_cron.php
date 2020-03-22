<?php
require_once('src/downloader.php');

$down = new Download;
$down->request($down->getDownloadUrl());
$down->saveFile();
