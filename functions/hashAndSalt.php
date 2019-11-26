<?php


include 'db.inc.php';

function hashAndSalt($passW1, $salt)
{
    $hashed_salted_pass = md5($passW1 .  $salt);
    return $hashed_salted_pass;
}

function salted_hashed_DB($usern, $pass)
{
    include 'db.inc.php';

    $salt = "SELECT salt FROM `users` where userName= '$usern' ";
  

    $querySalt = mysqli_query($con, $salt);
    $x =  mysqli_fetch_array($querySalt);
    $hashed_salted_pass = md5($pass . $x[0]);

    return $hashed_salted_pass;
}
