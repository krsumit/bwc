#!/usr/bin/php -q
<?php 
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
//parse_str($argv['1']);     // Uncommment this line if you are hittig from cli , It's being used in cron setting at amazone		
$section=$_GET['section'];   // Uncommment this line if you are hittig from url
include 'const.php';
include 'cron.php'; 
$cronobj = new Cron();
$cronobj->migrateData($section);
?>

