<?php 
session_start();
ini_set('display_errors', 1); 
$conn = new mysqli('localhost','root','admin') or die($conn->connect_errno);
$conn->select_db('bwcms');
if ($result = $conn->query("SELECT DATABASE()")) {
    $row = $result->fetch_row();
    printf("Default database is: %s.\n", $row[0]);
    $result->close();
}
$conn->select_db('bwcms_db');
if ($result = $conn->query("SELECT DATABASE()")) {
    $row = $result->fetch_row();
    printf("<br>Default database is: %s.\n", $row[0]);
    $result->close();
}
$db='bw_education_db';
$conn->select_db($db);
if ($result = $conn->query("SELECT DATABASE()")) {
    $row = $result->fetch_row();
    printf("<br>Default database is: %s.\n", $row[0]);
    $result->close();
}

?>
