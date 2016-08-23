<?php
session_start();

if (isset($_SESSION['user'])) {
header("location:make_picks.php");
} else {
echo "Session user is not set.";
}

//if(!session_is_registered('theusername')){
//header("location:make_picks.php");

?>