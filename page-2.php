<?php


include "functions/pageBase.php";
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

$page = "<!DOCTYPE html>
<html>
<head>
<title>Login Successful</title>
<link rel='icon' type='image/gif' href='../images/logo.png' />
<link rel = 'stylesheet' type = 'text/css' href = 'webStyle.css' />
<title>Page Title</title>
</head>
<body>
<div class='container'>
<h1>this is page two</h1>
<p>hey.</p>
</div>
</body>
</html>
";

builPage($page);
