<!--
	Name: loginScreen.html.php
	Purpose: HTML / PHP File to allow a User to login and change their password if desired
	Author: Brendan Browne
	Date: 03/2018
-->
<?php 
include 'db.inc.php';
session_start();



if (ISSET($_POST['userName']) && ISSET($_POST['password'])) /* If the username and password are set (if you are logged in) */
{
	$attempts = $_SESSION['attempts'];
	
	#to hash and de hash
	$salt = "SELECT salt FROM `users` where userName= '$_POST[userName]' ";
	$querySalt = mysqli_query($con, $salt);
	
	$x =  mysqli_fetch_array($querySalt) ;
	echo $x[0];
	echo "<br>";
	echo $_POST['password'];
	echo "<br>";
	$hashed_pass = md5 ($_POST['password']);
	$hashSalted = $hashed_pass.$x[0];
	echo $hashSalted;
	echo "<br>";

	// Select all users who match this username ans password comvination
	$sql = "SELECT * FROM users WHERE userName = '$_POST[userName]' AND password = '$hashSalted'";
	echo $sql;
	echo "<br>";

	/* Error checking for the sql statement */
	if(!mysqli_query($con,$sql))
		echo "Error in selecting username & password (loginScreen.html.php)" . mysqli_error($con);
	else
	{
		if(mysqli_affected_rows($con) == 0) /* If no rows have changed */
		{
			$attempts++;

			if($attempts <=3) /* If the attempts are less than or equal to 3 */
			{

				$salt = "SELECT salt FROM `users` where userName= '$_POST[userName]' ";
				$querySalt = mysqli_query($con, $salt);
				
				$x =  mysqli_fetch_array($querySalt) ;
				echo $x[0] ;
				$hashed_pass = md5 ($_POST['password']);
  				$hashSalted = $hashed_pass.$x[0];


				$_SESSION['attempts'] = $attempts;
				echo "SELECT * FROM users WHERE userName = '$_POST[userName]' AND password =$hashSalted  ";
				buildPage($attempts);

				echo "<script>document.getElementById('errorMessage').innerHTML = '<div>NO RECORDS FOUND WITH THIS USERNAME AND PASSWORD</div>'</script>";
			}
			else
			{
				echo "<div class = 'attemptsUsed'>Sorry - You have used all 3 attempts</div>";
			}
		}
		else /* If rows have changed */
		{
			$_SESSION['user'] = $_POST['userName'];
			echo "attemp :: ", $attempts;
			/* Had to use the below code as I couldnt get script to work to replace the login screen
			I tried document.getElementById() but as the login part was in a form it wouldn't work*/
			echo"	<!DOCTYPE html>
			<html>
			<head>
			<title>Page Title</title>
			</head>
			<body>
			
			<h1>yeahhhhhhh</h1>
			<p>hey.</p>
			
			</body>
			</html>
			";

		}
	}
}
else /* if you are not logged in, build the page with blank fields */
{
	$attempts = 1;
	$_SESSION['attempts'] = $attempts;
	buildPage($attempts);
};

// BuildPage function builds teh page with the parameter passed to it
function buildPage($att)
{
	echo"
	<title>Login Successful</title>

	<head>
				<title>Login Successful</title>
				<link rel='icon' type='image/gif' href='../images/logo.png' />
				<script src = 'login.js'></script>
				<link rel = 'stylesheet' type = 'text/css' href = 'login.css' />
			</head>
			<body>

	<h2>Login Form</h2>

	<form action='/project/loginScreen.html.php' method='post'>
	  <div class='imgcontainer'>
		<img src='img/img_avatar2.png' alt='Avatar' class='avatar'>
	  </div>
	
	  <div class='container'>
	  	<h1>Attempt Number: $att</h1>
		<label for='uname'><b>Username</b></label>
		<input type='text' placeholder='Enter Username' name='userName' required>
	
		<label for='psw'><b>Password</b></label>
		<input type='password' placeholder='Enter Password' name='password' required>
			
		<button type = 'submit' class = 'active' value >Login</button>
		<label>
		  <input type='checkbox' checked='checked' name='remember'> Remember me
		</label>
	  </div>
	
	  <div class='container' style='background-color:#f1f1f1'>
		<button type='button' class='cancelbtn'>Cancel</button>
		<span class='psw'>Forgot <a href='#'>password?</a></span>
		<span class='psw'>Create An <a href='register.php'>Account?    </a></span>

	  </div>
	</form>
	</body>

				";
}

/* Close the database connection */
mysqli_close($con);
?>