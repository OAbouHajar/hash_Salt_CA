<?php
session_start();
$attemptsReg = 1;
include 'db.inc.php';
require "Sanatize.php";
include "functions/hashAndSalt.php";

################## redirect new
$sql_ip_blocked_select = " SELECT MAX(time) FROM `logs` WHERE ipAdderss = '$_SESSION[ip]' and describeLog = 'blocked' AND userAgent = '$_SESSION[USER_AGENT]' ";
$query_time = mysqli_query($con, $sql_ip_blocked_select);
$time_bocked =  mysqli_fetch_array($query_time);
$time_blocked_check = strtotime($time_bocked[0]);
if ($query_time->num_rows > 0) {
  if (time() - $time_blocked_check < 60) {
    header("location: cantReach.php");
  }
}
############################

if (isset($_POST["username"]) && isset($_POST["psw"])) {

  $username = new Sanatize($_POST["username"]);
  $username = $username->sanatize();
  $attempts = $_SESSION['attempts'];
  $sql_check_user_name = "SELECT * FROM `users` where userName = '$username'";

  /* Error checking for the sql statement */
  if (!mysqli_query($con, $sql_check_user_name))
    echo "Error in selecting username & password" . mysqli_error($con);
  else {
    if (mysqli_affected_rows($con) == 0) /* If no rows have changed */ {
      $salt = uniqid(mt_rand());
      $hashed_salted_pass = hashAndSalt($password, $salt);
      $sql = "INSERT INTO `users` ( `userName`, `password`, `salt`) VALUES ('$username', '$hashed_salted_pass', '$salt')";
      if (!mysqli_query($con, $sql)) {
        echo "Error in selecting username & password" . mysqli_error($con);
      }
      header("location: loginScreen.html.php");
    } else {
      $attempts++;
      if ($attempts <= 5) /* If the attempts are less than or equal to 5 */ {
        $_SESSION['attempts'] = $attempts;
        buildPage($attempts);
        echo "<script>document.getElementById('errorMessage').innerHTML = '<div>The username $username is already taken</div>'</script>";
      } else {
        $sql_ip_blocked_insert = "INSERT INTO `logs` (`userName`, `ipAdderss`, `sessionToken`, `attepmts`, `loginStatus`,`describeLog`, `time`,'userAgent') 
				VALUES                    ('$_SESSION[userName]', '$_SESSION[ip]', '$_SESSION[token]', '$_SESSION[attempts]', '0','blocked', '$timestamp', '$_SESSION[USER_AGENT]')";

        if (!mysqli_query($con, $sql_ip_blocked_insert)) {
          echo "Error in selecting username & password (loginScreen.html.php)" . mysqli_error($con);
        }
        echo "YOU HAVE 5 ATTEPTS ALREADY ";
        $_SESSION['lockedOut'] = true;
        $_SESSION['lockedOutTime'] = time();
        header("location: cantReach.php");
      }
    }
  }
} else /* if you are not logged in, build the page with blank fields */ {
  $attempts = 1;
  $_SESSION['attempts'] = $attempts;
  buildPage($attempts);
};










function buildPage($att)
{
  echo "
    <!DOCTYPE html>
    <html>
    <head>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel = 'stylesheet' type = 'text/css' href = 'webStyle.css' />

    </head>
    <body>

    <form action='/project/register.php', method='POST'>
      <div class='container'>
        <h1>Register</h1>
        <div id = 'errorMessage' style='color:#f44336' ></div>

        <p>Please fill in this form to create an account.</p>
        <hr>
        <h1>YOU HAVE 5 ATTEMPT :: this is Attempt : $att Of 5 </h1>
        <label for='username'><b>User Name</b></label>
        <input type='text' placeholder='Enter User Name' name='username'  title='Three letter or more (No charecters)' required>

        <label for='psw'><b>Password</b></label>
        <input type='password' placeholder='Password (UpperCase, LowerCase, Number/SpecialChar and min 8 Chars)' pattern='(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$'  name='psw' required>
        <br>Password must contain at least one : 
        <ul>
        <li>uppercase</li>
        <li>lowercase</li>
        <li>number</li>
        <li>charecter</li>
        <li>and more than 4 char long</li>
        </ul>  
      
       
        <label for='psw-repeat'><b>Repeat Password</b></label>
        <input type='password' placeholder='Password (UpperCase, LowerCase, Number/SpecialChar and min 8 Chars)' pattern='(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$' name='psw-repeat' required>
        <hr>
        <p>By creating an account you agree to our <a href='#'>Terms & Privacy</a>.</p>

        
        <button type='submit' class='registerbtn'>Register</button>
      </div>
      
      <div class='container signin'>
        <p>Already have an account? <a href='loginScreen.html.php'>Sign in</a>.</p>
      </div>
    </form>

    </body>
    </html>";
};
