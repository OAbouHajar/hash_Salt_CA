
<?php 

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbName = "projecttest3";
	
	// Connect to MySQL
	$con = new mysqli($servername, $username, $password);
	if ($con->connect_error) {
		die("Connection failed: " . $con->connect_error);
	}
	
	// If database is not exist create one
	if (!mysqli_select_db($con,$dbName)){
		$sql = "CREATE DATABASE ".$dbName;
		if ($con->query($sql) === TRUE) {
			echo "Database created successfully";
		}else {
			echo "Error creating database: " . $con->error;
		}
	} 
?>
