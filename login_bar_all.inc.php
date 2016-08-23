<?php
// determine if loginbar contains login form or "you are logged in as" text
if (isset($_SESSION['user'])) {
// they are logged in so load text and logout button 
	echo "\n" . '<div class="login" align="center">';
	echo "\n" . "You are logged in as: <b> " . $_SESSION['user'] . "</b>";
	echo "\n" . '<form name="form2" method="post" action="logout.php"><input type="submit" value="Logout"></form></div>';
} else { 
//they are not logged in so load login bar
echo "\n" . '<div class="login" align="center">';
echo "\n" . '<form name="form1" method="post" action="checklogin.php">';
echo "\n" . '<b>Username:</b>&nbsp;<input name="theusername" type="text" id="theusername">';
echo "\n" . '<b>Password:</b>&nbsp;<input name="thepassword" type="password" id="thepassword">';
echo "\n" . '<input type="submit" name="Submit" value="Login"></form></div>';
}

?>