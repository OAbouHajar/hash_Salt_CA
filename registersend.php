<?php
	include 'db.inc.php';
  date_default_timezone_set("UTC");
  
  $username = $_POST['username'] ; 	
  $password = $_POST['psw'] ; 	


  $hashPass = hash('sha512', $password);


  $salt = uniqid(mt_rand());
  $hashed_pass = md5 ( $password);
  $hashSalted = $hashed_pass.$salt;

  $sql = "INSERT INTO `users` ( `userName`, `password`, `salt`) VALUES ('$username', '$hashSalted', '$salt')";

  echo $sql;

   if(!mysqli_query($con , $sql))
    {
      die ("An Error in the SQL Query: " .mysqli_error());
    }


  	
	mysqli_close($con);


?>