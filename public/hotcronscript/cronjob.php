<?php 
session_start();
error_reporting(E_ALL);
parse_str($argv['1']);      // Uncommment this line if you are hittig from  cli , It's being used in cron setting amazon		
//$section=$_GET['section'];    // Uncommment this line if you are hittig from url
include 'cron.php';
include 'const.php';
$cronobj = new Cron();
$cronobj->migrateData($section);
?>
