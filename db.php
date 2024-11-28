<?php

$hostName = "sql211.infinityfree.com";
$dbUser = "if0_37805840";
$dbPassword = "XULOOACx8DY"; 
$dbName = "if0_37805840_viaje_db";

$conn = new mysqli($hostName, $dbUser, $dbPassword, $dbName);
if($conn->connect_error){
    die("connection failed: ". $conn->connect_error);
}else{
    echo "success";
}
?>
