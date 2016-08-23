<?php
session_start();

require 'html_head.inc.php';
require 'login_bar_all.inc.php';
require 'banner.inc.php';
// CHANGE LATER TO HAVE DIFFERENT NAVBARS
require 'navbar.inc.php';
require 'begin_content.inc.php';

echo "<!-- PAGE-SPECIFIC CONTENT STARTS HERE -->";

//load admin list
require "admin_list.inc.php";

// check admin
if (!$_SESSION['user'] OR !in_array($_SESSION['user'], $admins)) {
 die ("Must be an administrator.");
} else {

// connect to database
require 'db_connect.inc.php'; 

// query for earliest week games, with no scores entered, kickoff more than 3 hours ago
//old $thequery="SELECT AWAY, HOME, GID FROM pyu_games WHERE (ISNULL(AWAYSCORE) OR ISNULL(HOMESCORE)) AND DATE<SUBTIME(NOW(),'03:00:00') AND WEEK = (SELECT WEEK FROM pyu_games WHERE ISNULL(HOMESCORE) LIMIT 1) ORDER BY DATE ASC";
$thequery="SELECT AWAY, HOME, GID FROM pyu_games WHERE (ISNULL(AWAYSCORE) OR ISNULL(HOMESCORE)) AND DATE<SUBTIME(NOW(),'03:00:00') AND WEEK = (SELECT MIN(WEEK) FROM pyu_games WHERE ISNULL(HOMESCORE) LIMIT 1) ORDER BY DATE ASC";
$result=mysql_query($thequery);
$count=mysql_num_rows($result);
//echo $thequery;
echo '<form name="updatescoresForm" action="check_upd_scores.php" method="post">';
echo '<div align="center"><input type="submit" value="Submit"></div>';
echo '<table border="1" style="font-size:small"><tr><td>AWAY</td><td>SC</td><td>HOME</td><td>SC</td></tr>';

for ($i = 1; $i <= $count; $i++) {
	$theresult=mysql_fetch_row($result);

//away team
echo "<td>" . $theresult[0] . "</td>";

//away score
echo "<td><input type=" . chr(39) . "text" . chr(39) . " name=" . chr(39) . $theresult[2] . ",AWAYSCORE" . chr(39) . " value=" .chr(39) . "" . chr(39) . "></input></td>";

//home team
echo "<td>" . $theresult[1] . "</td>";

//home score
echo "<td><input type=" . chr(39) . "text" . chr(39) . " name=" . chr(39) . $theresult[2] . ",HOMESCORE" . chr(39) . " value=" .chr(39) . "" . chr(39) . "></input></td>";//home score

echo "</tr>";
} // close for loop

mysql_close();

} // close if !admin loop
?>

</table><br />
<div align="center"><input type="submit" value="Submit"></div>
</form>
<!-- PAGE-SPECIFIC CONTENT ENDS HERE -->
</div>
</body>