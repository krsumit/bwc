<?php
session_start();
error_reporting(E_ALL);
include 'cron.php';
include 'const.php';
$cronobj = new Cron();
$cronobj->migrateData();

//print_r($_SERVER);exit;
