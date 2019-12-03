<?php
include 'db.inc.php';
echo '<link rel="stylesheet" href="webStyle.css" type="text/css">';
session_start();
echo "Time Left for this session :::";
echo 6000 - (time() - $_SESSION['max_session_live']);
echo " Seconds";
if ($_SESSION['user'] == 'ADMIN' && $_SESSION['password'] == 'SAD_2019!') {
	include "functions/menuAdmin.php";

} else {
	include "functions/menu.php";

	include "functions/hashAndSalt.php";
	if ((time() - $_SESSION['max_session_live']) < 6000) {
		if (isset($_SESSION["username"])) {
			if (isset($_POST['oldPass']) && isset($_POST['newPass']) && isset($_POST['confirmPass'])) {
				$old = $_POST['oldPass'];
				$new = $_POST['newPass'];
				$confirm = $_POST['confirmPass'];


				#to getr the old hashed password 
				$user = $_SESSION['user'];
				$oldHashed = salted_hashed_DB($user, $old);


				$sql = "SELECT * FROM `users` WHERE userName = '$user' AND password = '$oldHashed' ";
				if (!mysqli_query($con, $sql))
					echo "Error in Select query " . mysqli_error($con);
				else {
					if (mysqli_affected_rows($con) == 0) {
						buildPage($old, $new, $confirm);
						#echo "<div class='errorstyle'> Old Password incorrect</div>";
						echo "<script type='text/javascript'>alert('Old Password incorrect');</script>";
					} else {
						if ($old == $new) {
							buildPage($old, $new, $confirm);
							echo "<script type='text/javascript'>alert('NEW PASSWORD SHOULD BE DIFFERENT TO THE OLD ONE');</script>";
						} else if ($_POST['newPass'] != $_POST['confirmPass']) {
							buildPage($old, $new, $confirm);
							#echo "<div class='errorstyle'> new pass not matched please try again</div>";
							echo "<script type='text/javascript'>alert('new pass not matched please try again');</script>";
						} else {
							$salt = uniqid(mt_rand());
							$new_salted_hashed = hashAndSalt($new, $salt);

							$sql = "UPDATE `users` SET `password` = '$new_salted_hashed' WHERE `userName` = '$user'";
							$sql2 = "UPDATE `users` SET `salt` = '$salt' WHERE `userName` = '$user'";

							if (!mysqli_query($con, $sql) || !mysqli_query($con, $sql2)) {
								echo "Error in Select query " . mysqli_error($con);
							} else {
								if (mysqli_affected_rows($con) == 0) {
									buildPage($old, $new, $confirm);
									echo "<div class='errorstyle'>Sorry! Update not successful</div>";
								} else {
									echo "<script type='text/javascript'>alert('YOUR PASS WORD HAS BEEN CHANGED');</script>";
									echo "<script>
								alert('YOU HAVE TO LOG IN AGAIN PLEASE !!');
								window.location.href='logout.php';
								</script>";
								}
							}
						}
					}
				}
			} else {
				buildPage("", "", "");
			}
		} else {
			header('Location:loginScreen.html.php');
		}
	} else {
		header('Location:logout.php');
	}
}
function buildPage($o, $n, $c)
{
	echo "<body>
	<div class='container'>

	<form action = 'changePass.php' method = 'post'>
		<h1> my system</h1>
		<h3>change password </h3>
			<label for='oldPass'>Old pass</label> 
			<input type = 'password' name = 'oldPass' id = 'oldPass' placeholder='Password must contain 1 uppercase, lowercase and number, and 4 char long' pattern='(?=^.{3,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]{3,}).*$' autocomplete = 'off' value=$o><br><br>
			
			<label for='newPass'>new pass</label> 
			<input type = 'password' name = 'newPass' id = 'newPass' placeholder='Password must contain 1 uppercase, lowercase and number, and 4 char long' pattern='(?=^.{3,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]{3,}).*$' autocomplete = 'off' value=$n><br><br>
		
		<label for='confirmPass'>confiem pass</label> 
			<input type = 'password' name = 'confirmPass' id = 'confirmPass' autocomplete = 'off' value=$c><br><br>
			<button class='button button5' name='submit' value='Submit'><span>submit </span></button>
			</form>


			</div>
			</body>";
}
mysqli_close($con);
