#!/usr/bin/php -q
<?php 
date_default_timezone_set('Asia/Calcutta');
session_start();
error_reporting(E_ALL);
parse_str($argv['1']);     // Uncommment this line if you are hittig from url cli , It's being used in cron setting amazonee		
//$section=$_GET['section'];    // Uncommment this line if you are hittig from url
//echo $section; exit;
include 'mail.php';
$cronobj = new Cron();
$cronobj->sendMailData($section);
?>
