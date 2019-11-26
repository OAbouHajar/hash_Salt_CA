<?php
session_start();
include 'db.inc.php';

?>
<!DOCTYPE html>
<html>
<body>

<?php


############ for the cookies and the session work #####
$timestamp = date('Y-m-d H:i:s');
$sqlStart = "INSERT INTO `logs` (`userName`, `ipAdderss`, `sessionToken`, `attepmts`, `loginStatus`,`describeLog`, `time`) 
                    VALUES     ('$_SESSION[userName]', '$_SESSION[ip]', '$_SESSION[token]', '-/1', '0', 'LogOut' ,  '$timestamp')";

echo $sqlStart;

if (!mysqli_query($con, $sqlStart)) {
    echo "Error in selecting username & password (loginScreen.html.php)" . mysqli_error($con);
}
####### end for the cookies and the session work
// remove all session variables
session_unset(); 

// destroy the session 
session_destroy();

echo "All session variables are now removed, and the session is destroyed." ;

header("Location: loginScreen.html.php");


?>

</body>
</html>