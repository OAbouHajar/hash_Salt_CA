<?php
session_start();

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
    echo "YOU ARE OUT FOR 60 Second TIME  :::  " ;
    echo (time() - $_SESSION['lockedOutTime'] );
    
    if ((time() - $_SESSION['lockedOutTime']) > 50) {
        $_SESSION['lockedOutTime'] = 0;
        $_SESSION['lockedOut'] = false;
        $_SESSION['attempts'] = 0;
        header("location: loginScreen.html.php");
    }
    ?>
    </div>
</body>

</html>




