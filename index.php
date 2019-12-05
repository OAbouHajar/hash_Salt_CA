<?php
include 'db.inc.php';
include 'functions/hashAndSalt.php';
################## redirect new
$sql_ip_blocked_select = " SELECT MAX(time) FROM `logs` WHERE ipAdderss = '$_SESSION[ip]' and describeLog = 'blocked' AND userAgent = '$_SESSION[USER_AGENT]' ";
$query_time = mysqli_query($con, $sql_ip_blocked_select);
$time_bocked =  mysqli_fetch_array($query_time);
$time_blocked_check = strtotime($time_bocked[0]);
if ($query_time->num_rows > 0) {
	if (time()- $time_blocked_check < 60 ) {
		header("location: cantReach.php");
	}	
}
############################


// $sqlCreate  = "CREATE DATABASE IF NOT EXISTS projecttest3";
$sql_user_table = "CREATE TABLE  projecttest3.users ( 
    id INT(11) NOT NULL AUTO_INCREMENT, 
    userName varchar(255), 
    password varchar(255), 
    salt varchar(255), 
    PRIMARY KEY (id,userName) 
)";
$sql_logs_table = "CREATE TABLE projecttest3.logs ( 
    id INT(11) NOT NULL AUTO_INCREMENT, 
    userName varchar(255), 
    ipAdderss varchar(500), 
    sessionToken varchar(500), 
    attepmts int,
    loginStatus tinyint,
    describeLog varchar(500),
    time 	datetime(6)	,
    userAgent	varchar(500),
    PRIMARY KEY (id) 
)";

$password_admin = 'SAD_2019!'; 
$salt = uniqid(mt_rand());
$hashed_salted_pass = md5($password_admin .  $salt);
$sql = "INSERT INTO `users` ( `id`, `userName`, `password`, `salt`) VALUES ('101','admin', '$hashed_salted_pass', '$salt')";

// $sql_create_Admin = " INSERT INTO projecttest3.users (`id`, `userName`, `password`, `salt`) 
//                     VALUES                          ('101', 'ADMIN', '$new_salted_hashed', '$salt')";

    
if (!mysqli_query($con, $sql_user_table)) {
    echo "Error Creatign Database" . mysqli_error($con);
}
if (!mysqli_query($con, $sql_logs_table)) {
    echo "Error Creatign Database" . mysqli_error($con);
}
if (!mysqli_query($con, $sql)) {
    echo "Error Creatign Database" . mysqli_error($con);
}


header("Location: loginScreen.html.php");
