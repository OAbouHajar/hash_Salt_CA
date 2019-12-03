<!--
	Name: loginScreen.html.php
	Purpose: HTML / PHP File to allow a User to login and change their password if desired
	Author: Brendan Browne
	Date: 03/2018
-->
<?php
include 'db.inc.php';
session_start();
require "Sanatize.php";
include "functions/hashAndSalt.php";
#include "functions/chckBlock.php";



$_SESSION['last_time'] = time();

#### for the session id and the cookies set  ###########
$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
$_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
$_SESSION['token'] = sha1($_SESSION['USER_AGENT'] . rand() . $_SERVER['SERVER_NAME']);
setcookie('token', $_SESSION['token']);

// // $_SESSION['lockedOut'] = false ;


### end session id and the cookies

############## to records the attempts when not loged in yet ###########
#time when start record the attaepts
if (!(isset($_POST["userName"]) && isset($_POST["password"]))) {
	$timestamp = date('Y-m-d H:i:s');
}
### End

$first_time_load = true;
if ($first_time_load) {
	$_SESSION['lockedOut'] = false;
	$first_time_load = false;
}

if ($_SESSION['lockedOut']) {
	echo "time out ";
	echo $_SESSION['lockedOutTime'];
	header("location: cantReach.php");
}

##### NOT SUER WHY HERE ########
$salt = uniqid(mt_rand());
$hashed_salted_pass = md5($password . $salt);
#### END NOT SURE ###



if (isset($_POST["userName"]) && isset($_POST["password"])) /* If the username and password are set (if you are logged in) */ {
	$username = new Sanatize($_POST["userName"]);
	$username = $username->sanatize();
	$password = new Sanatize($_POST["password"]);
	$password = $password->sanatize();
	$_SESSION['password'] = $_POST["password"];
	$attempts = $_SESSION['attempts'];
	$_SESSION["username"] = $_POST["userName"];
	#to hash and de hash
	// $salt = "SELECT salt FROM `users` where userName= '$username' ";
	// $querySalt = mysqli_query($con, $salt);
	// $x =  mysqli_fetch_array($querySalt);
	// $hashed_salted_pass = md5($_POST['password'] . $x[0]);



	############ for the cookies and the session work #####
	$_SESSION['userName'] = $_POST["userName"];
	$timestamp = date('Y-m-d H:i:s');
	####### end for the cookies and the session work


	$hashed_salted_pass = salted_hashed_DB($username, $password);

	// Select all users who match this username ans password comvination
	$sql = "SELECT * FROM users WHERE userName = '$username' AND password = '$hashed_salted_pass'";
	echo "<br>";

	/* Error checking for the sql statement */
	if (!mysqli_query($con, $sql))
		echo "Error in selecting username & password (loginScreen.html.php)" . mysqli_error($con);
	else {
		if (mysqli_affected_rows($con) == 0) /* If no rows have changed */ {
			$attempts++;

			if ($attempts <= 5) /* If the attempts are less than or equal to 3 */ {

				$salt = "SELECT salt FROM `users` where userName= '$username' ";
				$querySalt = mysqli_query($con, $salt);
				$x =  mysqli_fetch_array($querySalt);
				$hashed_pass = md5($_POST['password']);
				$hashSalted = $hashed_pass . $x[0];
				$sqlStart = "INSERT INTO `logs` (`userName`, `ipAdderss`, `sessionToken`, `attepmts`, `loginStatus`,`describeLog`, `time`) 
				VALUES                    ('$_SESSION[userName]', '$_SESSION[ip]', '$_SESSION[token]', $attempts, '0','Not Successful Login', '$timestamp')";

				if (!mysqli_query($con, $sqlStart)) {
					echo "Error in selecting username & password (loginScreen.html.php)" . mysqli_error($con);
				}



				$_SESSION['attempts'] = $attempts;
				buildPage($attempts);
				echo "<script>document.getElementById('errorMessage').innerHTML = '<div>The username $username and password could not be authenticated at the moment</div>'</script>";
			} else {
				echo "YOU HAVE 5 ATTEPTS ALREADY ";
				$_SESSION['lockedOut'] = true;
				$_SESSION['lockedOutTime'] = time();
				header("location: cantReach.php");
			}
		} else /* If rows have changed */ {
			$_SESSION['user'] = strtoupper( $_POST['userName'] );
			echo "attemp :: ", $attempts;
			$_SESSION['logged_in'] = true;
			$sqlStart = "INSERT INTO `logs` (`userName`, `ipAdderss`, `sessionToken`, `attepmts`, `loginStatus`,`describeLog`, `time`) 
			VALUES                    ('$_SESSION[userName]', '$_SESSION[ip]', '$_SESSION[token]', $attempts, '1','Successful Login', '$timestamp')";

			if (!mysqli_query($con, $sqlStart)) {
				echo "Error in selecting username & password (loginScreen.html.php)" . mysqli_error($con);
			}

			/* Had to use the below code as I couldnt get script to work to replace the login screen
			I tried document.getElementById() but as the login part was in a form it wouldn't work*/
			header("Location: page-1.php");
		}
	}
} else /* if you are not logged in, build the page with blank fields */ {
	$attempts = 1;
	$_SESSION['attempts'] = $attempts;
	buildPage($attempts);
};



// BuildPage function builds teh page with the parameter passed to it
function buildPage($att)
{
	echo "
	<title>Login Successful</title>

	<head>
				<title>Login Successful</title>
				<link rel='icon' type='image/gif' href='../images/logo.png' />
				<script src = 'login.js'></script>
				<link rel = 'stylesheet' type = 'text/css' href = 'webStyle.css' />
			</head>
			<body>

	<h2>Login Form</h2>
	<div id = 'errorMessage' style='color:#f44336' ></div>

	<form action='/project/loginScreen.html.php' method='post'>
	  <div class='imgcontainer'>
		<img src='img/img_avatar2.png' alt='Avatar' class='avatar'>
	  </div>
	
	  <div class='container'>
	  	<h1>this is Attempt : $att Of 5 </h1>
		<label for='uname'><b>Username</b></label>
		<input type='text' placeholder='Enter Username' name='userName' placeholder='Username must be between 8 and 20 characters'  required>
	
		<label for='psw'><b>Password</b></label>
		<input type='password' placeholder='Enter You Password Please'  name='password' required>
			
		<button type = 'submit' class = 'active' value >Login</button>
		<label>
		  <input type='checkbox' checked='checked' name='remember'> Remember me
		</label>
	  </div>
	
	  <div class='container' style='background-color:#f1f1f1'>
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