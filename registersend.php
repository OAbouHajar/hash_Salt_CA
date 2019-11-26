<?php
include 'db.inc.php';
require "Sanatize.php";
include "functions/hashAndSalt.php";




date_default_timezone_set("UTC");

#$username = $_POST['username'] ; 	
#$password = $_POST['psw'] ; 	

$username = new Sanatize($_POST["username"]);
$username = $username->sanatize();

	$password = new Sanatize( $_POST["psw"] );
	$password = $password->sanatize();

# $salt = uniqid(mt_rand());
# $hashed_salted_pass = md5 ( $password.$salt);

$salt = uniqid(mt_rand());
$hashed_salted_pass = hashAndSalt($password , $salt);

  $sql = "INSERT INTO `users` ( `userName`, `password`, `salt`) VALUES ('$username', '$hashed_salted_pass', '$salt')";

echo $sql;

if (!mysqli_query($con, $sql)) {
  die("An Error in the SQL Query: " . mysqli_error());
}



mysqli_close($con);

header("Location: loginScreen.html.php");
