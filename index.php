<?php
include 'db.inc.php';


$sqlCreate  = "CREATE DATABASE IF NOT EXISTS projecttest";
$sql_user_table = "CREATE TABLE IF NOT EXISTS projecttest.users ( 
    id int, userName varchar(255), 
    password varchar(255), 
    salt varchar(255), 
    PRIMARY KEY (id,userName) 
)";
$sql_logs_table = "CREATE TABLE IF NOT EXISTS projecttest.logs ( 
    id int, 
    userName varchar(255), 
    ipAdderss varchar(500), 
    sessionToken varchar(500), 
    attepmts int,
    loginStatus tinyint,
    describeLog varchar(500),
    time 	datetime(6)	,
    PRIMARY KEY (id) 
)";
if (!mysqli_query($con, $sqlCreate)) {
    echo "Error Creatign Database" . mysqli_error($con);
}

if (!mysqli_query($con, $sql_user_table)) {
    echo "Error Creatign Database" . mysqli_error($con);
}
if (!mysqli_query($con, $sql_logs_table)) {
    echo "Error Creatign Database" . mysqli_error($con);
}

header("Location: loginScreen.html.php");
