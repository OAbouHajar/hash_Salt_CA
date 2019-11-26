<?php 
include 'db.inc.php';
echo '<link rel="stylesheet" href="webStyle.css" type="text/css">';
session_start();
include "./menu.php";
include "functions/hashAndSalt.php";

if (isset($_SESSION["username"]))
{
	if (isset($_POST['oldPass']) && isset($_POST['newPass']) && isset($_POST['confirmPass']))
	{
		$old = $_POST['oldPass'];
		$new = $_POST['newPass'] ; 
		$confirm = $_POST['confirmPass'];

	
		#to getr the old hashed password 
		$user = $_SESSION['user'] ; 
		$oldHashed = salted_hashed_DB($user, $old );

		
		$sql = "SELECT * FROM `users` WHERE userName = '$user' AND password = '$oldHashed' ";
		if(!mysqli_query($con, $sql))
			echo "Error in Select query ".mysqli_error($con);
		else
		{
			if(mysqli_affected_rows($con) == 0 )
			{
				buildPage($old, $new , $confirm );
				echo "<div class='errorstyle'> Old Password incorrect</div>" ;
			}
			else 
			{
				if($_POST['newPass'] != $_POST['confirmPass'] )
				{
					buildPage($old, $new , $confirm );
					echo "<div class='errorstyle'> new pass not matched please try again</div>" ;
				}
				else 
				{
					$todayDate = date("Y-m-d");
					$salt = uniqid(mt_rand());
					$new_salted_hashed = hashAndSalt($new, $salt);

					$sql = "UPDATE `users` SET `password` = '$new_salted_hashed' WHERE `userName` = '$user'";
					$sql2 = "UPDATE `users` SET `salt` = '$salt' WHERE `userName` = '$user'";

					if(!mysqli_query($con, $sql) || !mysqli_query($con, $sql2))
					{
					echo "Error in Select query ".mysqli_error($con);
					}
					else
					{
						if(mysqli_affected_rows($con) == 0 )
						{
							buildPage($old, $new , $confirm );
							echo "<div class='errorstyle'>Sorry! Update not successful</div>" ;
						}
						else 
						{
							header('Location:logout.php');

						}
					}
					
				}
			}	
		}
	}
	else
	{
		buildPage("", "" , "" );
	}
}
else 
{
	header('Location:loginScreen.html.php');
}

function buildPage($o,$n,$c)
{
	echo "<body>
	<div class='container'>

	<form action = 'changePass.php' method = 'post'>
		<h1> my system</h1>
		<h3>change password </h3>
			<label for='oldPass'>Old pass</label> 
			<input type = 'password' name = 'oldPass' id = 'oldPass' autocomplete = 'off' value=$o><br><br>
			
			<label for='newPass'>new pass</label> 
			<input type = 'password' name = 'newPass' id = 'newPass' autocomplete = 'off' value=$n><br><br>
		
		<label for='confirmPass'>confiem pass</label> 
			<input type = 'password' name = 'confirmPass' id = 'confirmPass' autocomplete = 'off' value=$c><br><br>
			<button class='button button5' name='submit' value='Submit'><span>submit </span></button>
			</form>


			</div>
			</body>";
}

mysqli_close($con);


?>