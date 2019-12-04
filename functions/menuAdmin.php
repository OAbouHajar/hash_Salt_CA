<!DOCTYPE html>
<html>

<head>
	<style>
		ul {
			list-style-type: none;
			margin: 0;
			padding: 0;
			overflow: hidden;
			background-color:darkolivegreen;
					}

		li {
			float: left;
		}

		li a {
			display: block;
			color: white;
			text-align: center;
			padding: 14px 16px;
			text-decoration: none;
		}

		li a:hover {
			background-color: #111;
		}
		.userMenuHead
			{
				color : #aa4942 ;
				font-size: 20px;
				text-align: left;
				
			}	
	</style>
</head>
<?php

if (isset($_SESSION['user'])) {
	//$userName = $_SESSION['user'];
	echo "<div class='userMenuHead' >YOU ARE LOGED IN AS :   " . $_SESSION['sana_username'] . "</div>";
}

?>

<body>
	<ul>

		<li><a class="active" href="page-1.php">Page-1</a></li>
		<li><a href="page-2.php">page-2</a></li>
		<li><a href="logs.php">Show Logs</a></li>
		<li><a href="changePass.php">Change Password</a></li>
		<li><a href="logout.php">Logout</a></li>
	</ul>

</body>

</html>