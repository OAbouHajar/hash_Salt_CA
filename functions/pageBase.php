<!doctype html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Welcome to Time Session Login</title>
	<link rel="stylesheet" href="webStyle.css" type="text/css">




</head>


<body>
	<?php
	$page = "";
	session_start();
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

	if (!isset($_SESSION['max_session_live'])) {
		$_SESSION['max_session_live'] = time();
	}


	if ((time() - $_SESSION['max_session_live']) < 6000) {
		echo "Time Left for this session :::";
		$timeLeft = 6000 - (time() - $_SESSION['max_session_live']);
		$timemaxSession = $_SESSION['max_session_live'];
		echo $timeLeft;
		echo " Seconds";

		if (strtoupper($_SESSION['user']) == 'ADMIN' && $_SESSION['password'] == 'SAD_2019!') {
			include 'functions/menuAdmin.php';
		} else {
			include 'functions/menu.php';
		}

		if (isset($_SESSION['user'])) {
			if ((time() - $_SESSION['last_time']) > 300) // Time in Seconds
			{
				echo "<script>alert('15 Minutes over!');</script>";
				header("location:logout.php");
			} else {
				$_SESSION['last_time'] = time();
				builPage($page);
			}
		} else {
			header('Location:loginScreen.html.php');
		}
	} else {
		header('Location:logout.php');
	}



	function builPage($page)
	{
		echo $page;
	};


	?>
</body>


</html>