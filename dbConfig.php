<?php
// Database configuration
$dbHost     = "localhost";
$dbUsername = "ganjamla_dba";
$dbPassword = "hanumanji!@#";
$dbName     = "ganjamla_db";

//Create connection and select DB
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($db->connect_error) {
    die("Unable to connect database: " . $db->connect_error);
}

date_default_timezone_set('Asia/Kolkata');
?>