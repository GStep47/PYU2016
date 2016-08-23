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

// query for earliest week games with no final scores entered
$thequery="SELECT DATE_FORMAT(CONVERT_TZ(DATE, '-07:00', '-04:00'),'%a, %b %e, %l:%i %p'), AWAY, HOME, SITE, AP, HP, AM, HM, OV, UN, GID FROM pyu_games WHERE DATE>NOW() AND WEEK = (SELECT WEEK FROM pyu_games WHERE ISNULL(HOMESCORE) ORDER BY WEEK ASC LIMIT 1) ORDER BY DATE ASC";
$result=mysql_query($thequery);
$count=mysql_num_rows($result);

echo '<form name="updatelinesForm" action="check_upd_lines.php" method="post">';
echo '<div align="center"><input type="submit" value="Submit"></div>';
echo '<table border="1" style="font-size:small"><tr><td>KICKOFF</td><td>TEAMS</td><td>SPREAD</td><td>MONEY</td><td>O/U</td></tr>';

for ($i = 1; $i <= $count; $i++) {$theresult=mysql_fetch_row($result);
echo '<td rowspan="2">' . $theresult[0];
//add location if there is one
if (!is_null($theresult[3])) {echo "<br />at " . $theresult[3];} 
echo "</td>"; //date
echo "<td>" . $theresult[1] . "</td>"; //away team

//away pointspread
echo "<td><input type=" . chr(39) . "text" . chr(39) . " name=" . chr(39) . $theresult[10] . ",AP" . chr(39) . " value=" .chr(39) . $theresult[4] . chr(39) . " size=" .chr(39) . "5" .chr(39) . " maxlength=" .chr(39) . "5" .chr(39) .  "></input></td>";

//away money line
echo "<td><input type=" . chr(39) . "text" . chr(39) . " name=" . chr(39) . $theresult[10] . ",AM" . chr(39) . " value=" .chr(39) . $theresult[6] . chr(39) . " size=" .chr(39) . "10" .chr(39) . " maxlength=" .chr(39) . "10" .chr(39) .  "></input></td>";

//over
echo "<td><input type=" . chr(39) . "text" . chr(39) . " name=" . chr(39) . $theresult[10] . ",OV" . chr(39) . " value=" .chr(39) . $theresult[8] . chr(39) . " size=" .chr(39) . "4" .chr(39) . " maxlength=" .chr(39) . "4" .chr(39) .  "></input></td>";

// home team name
echo "<tr><td>" . $theresult[2] . "</td>"; 

//home pointspread
echo "<td><input type=" . chr(39) . "text" . chr(39) . " name=" . chr(39) . $theresult[10] . ",HP" . chr(39) . " value=" .chr(39) . $theresult[5] . chr(39) . " size=" .chr(39) . "5" .chr(39) . " maxlength=" .chr(39) . "5" .chr(39) .  "></input></td>";

//home money line
echo "<td><input type=" . chr(39) . "text" . chr(39) . " name=" . chr(39) . $theresult[10] . ",HM" . chr(39) . " value=" .chr(39) . $theresult[7] . chr(39) . " size=" .chr(39) . "10" .chr(39) . " maxlength=" .chr(39) . "10" .chr(39) .  "></input></td>";

// under
echo "<td><input type=" . chr(39) . "text" . chr(39) . " name=" . chr(39) . $theresult[10] . ",UN" . chr(39) . " value=" .chr(39) . $theresult[9] . chr(39) . " size=" .chr(39) . "4" .chr(39) . " maxlength=" .chr(39) . "4" .chr(39) .  "></input></td>";

echo "</tr>";
} // close for loop

//update last edited
$thequery="UPDATE pyu_games SET DATE=NOW(), HOME=" .chr(39) . $_SESSION['user'] . chr(39) . "WHERE GID=" . chr(39) . "55555" . chr(39) . ";";
$result=mysql_query($thequery);

mysql_close();

 } // close if !admin loop
?>

</table><br />
<div align="center"><input type="submit" value="Submit"></div>
</form>
<!-- PAGE-SPECIFIC CONTENT ENDS HERE -->
</div>
</body>