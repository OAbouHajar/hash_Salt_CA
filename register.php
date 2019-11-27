<?php
session_start();
$attemptsReg = 1;

if ($_SESSION['lockedOut']) {
  echo "time out ";
  echo $_SESSION['lockedOutTime'];
  header("location: cantReach.php");
}
else
  {
    if ($attemptsReg <= 5) {
      buildPage($attemptsReg);
    } else {
      header("location: cantReach.php");
  }
}



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

    <form action='/project/registersend.php', method='post'>
      <div class='container'>
        <h1>Register</h1>
        
        <p>Please fill in this form to create an account.</p>
        <hr>
        <h1>YOU HAVE 5 ATTEMPT :: this is Attempt : $att Of 5 </h1>
        <label for='username'><b>User Name</b></label>
        <input type='text' placeholder='Enter User Name' name='username'  title='Three letter or more (No charecters)' required>

        <label for='psw'><b>Password</b></label>
        <input type='password' placeholder='Password must contain 1 uppercase, lowercase and number, and 4 char long' pattern='(?=^.{3,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]{3,}).*$'  name='psw' required>

        <label for='psw-repeat'><b>Repeat Password</b></label>
        <input type='password' placeholder='Password must contain 1 uppercase, lowercase and number, and 4 char long' pattern='(?=^.{3,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]{3,}).*$' name='psw-repeat' required>
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
