#!/usr/bin/php -q
<?php 
session_start();
ini_set('display_errors', 1); 
error_reporting(E_ALL & ~E_NOTICE);
parse_str($argv['1']);      // Uncommment this line if you are hittig from url cli , It's being used in cron setting amazonee	
//$section=$_GET['section'];    // Uncommment this line if you are hittig from url
include 'const.php';
include 'cron.php';
$cronobj = new Cron();
$cronobj->migrateData($section);

?>
