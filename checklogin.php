<?php
session_start();

// connect to database
require 'db_connect.inc.php'; 

// username and password sent from form 
$theusername=$_POST['theusername']; 
$thepassword=$_POST['thepassword']; 

// sanitize
$theusername = filter_var($theusername,FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_LOW);
$thepassword = filter_var($thepassword,FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_LOW);
$theusername = addslashes($theusername);
$thepassword = addslashes($thepassword);

// hash password
$thepassword = hash("ripemd160", $thepassword);

$thequery="SELECT * FROM pyu_up WHERE user=" . chr(39) .  $theusername . chr(39) . " and pass=" . chr(39) . $thepassword . chr(39);
$result=mysql_query($thequery);

// Mysql_num_row is counting table row
$count=mysql_num_rows($result);

// If result matched $theusername and $thepassword, table row must be 1 row

if($count==1){

// Register $theusername, $thepassword and redirect to file "login_success.php"
$_SESSION['user'] = $theusername;
$_SESSION['pass'] = $thepassword;
header("location:login_success.php");
 }
else {
 echo "Wrong Username or Password";
}

mysql_close();

?>