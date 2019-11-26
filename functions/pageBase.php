<!doctype html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Welcome to Time Session Login</title>
</head>

<body>
	<?php
	$page = "";
	session_start();
	include './menu.php';		

	if (isset($_SESSION['user'])) {
		if ((time() - $_SESSION['last_time']) > 30) // Time in Seconds
		{
			echo"<script>alert('15 Minutes over!');</script>";
			header("location:logout.php");
		} else {
			$_SESSION['last_time'] = time();
			builPage($page);
		}
	} else {
		header('Location:loginScreen.html.php');
	}


function builPage($page){
	echo $page;
};


	?>
</body>

</html>


