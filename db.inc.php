
<?php 
	$hostname = "localhost";		// name of host or ip address
	$username = "root";			// mySQL username
	$password = "";		// mySQL password
	
	$dbname  = "projecttest";
	
	$con = mysqli_connect($hostname, $username, $password, $dbname);


	if (!$con)
		{
			die("Failed to connect: " .mysqli_connect_error());
		}
?>
