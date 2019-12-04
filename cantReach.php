<?php
include 'db.inc.php';
session_start();
$timestamp = date('Y-m-d H:i:s');
$page = $_SERVER['PHP_SELF'];
$sec = "1";


?>
<html>

<head>
    <meta http-equiv="refresh" content="<?php echo $sec ?>;URL='<?php echo $page ?>'">
</head>

<body>
    <div class='container'>";

        <?php
        $sql_ip_blocked_select = " SELECT MAX(time) FROM `logs` WHERE ipAdderss = '$_SESSION[ip]' and describeLog = 'blocked' AND userAgent = '$_SESSION[USER_AGENT]' ";
        $query_time = mysqli_query($con, $sql_ip_blocked_select);
        $time_bocked =  mysqli_fetch_array($query_time);
        $time_blocked_check = strtotime($time_bocked[0]);
        if (!mysqli_query($con, $sql_ip_blocked_select)) {
            echo "" . mysqli_error($con);
        }

        // echo "time_bocked " .$time_bocked[0];

        // echo "<br><br>YOU ARE OUT FOR 60 Second TIME  :::  ";
        // echo (time() - $time_blocked_check);


        echo "<h1>YOU ARE LOCKED OUT YOU HAVE TO WAIT 60 SEC</h1>";
        echo "<br><h1> >>>  YOUR WAITING TIME IS ::::  " . (time()- $time_blocked_check) . "</h1>";

        if (time()- $time_blocked_check > 60 ) {
           
            header("location: loginScreen.html.php");
        }
        ?>
    </div>
</body>

</html>