<?php
session_start();

require 'html_head.inc.php';
require 'login_bar_all.inc.php';
require 'banner.inc.php';
require 'navbar.inc.php';
require 'begin_content.inc.php';

echo "<!-- PAGE-SPECIFIC CONTENT STARTS HERE -->";

//load admin list
require "admin_list.inc.php";

// check admin
if (!$_SESSION['user'] OR !in_array($_SESSION['user'], $admins)) {
 die ("Must be an administrator.");
} else {

echo "<table border='1'><tr><td><b>USER</b></td><td><b>WAGER</b></td><td><b>UNITS</b></td></tr>";

// connect to database
require 'db_connect.inc.php'; 

$thequery="SELECT pyu_bets.USER, pyu_games.AWAY, pyu_games.HOME, pyu_bets.BETTYPE, pyu_bets.POINTS, pyu_bets.UNITS FROM pyu_bets JOIN pyu_games ON pyu_bets.gid = pyu_games.gid WHERE pyu_games.WEEK = CurrentWeek() ORDER BY pyu_bets.USER ASC , pyu_bets.UNITS DESC";
$result=mysql_query($thequery);
$count=mysql_num_rows($result);

for ($i = 1; $i <= $count; $i++) {

$theresult=mysql_fetch_row($result);
$user=$theresult[0];
$awayteam=$theresult[1];
$hometeam=$theresult[2];
$bettype=$theresult[3];
$points=$theresult[4];
$units=$theresult[5];

//print user
echo "<tr><td>" . $user . "</td>";

//print bet text
switch ($bettype) {
   case "AP":
    echo "<td><b>" . $awayteam . "</b> "; 
    if ($points > 0) {echo "+";}
    echo $points . " points over <b>" . $hometeam . "</td>";
    break;
   case "HP":
    echo "<td><b>" . $hometeam . "</b> "; 
    if ($points > 0) {echo "+";}
    echo $points . " points over <b>" . $awayteam . "</td>";
    break;
   case "AM":
    echo "<td><b>" . $awayteam . "</b> "; 
    if ($points > 0) {echo "+";}
    echo $points . " money line to beat <b>" . $hometeam . "</td>";
    break;
   case "HM":
    echo "<td><b>" . $hometeam . "</b> "; 
    if ($points > 0) {echo "+";}
    echo $points . " money line to beat <b>" . $awayteam . "</td>";
    break;
   case "OV":
    echo "<td><b>Over " . $points . " points</b> in the <b>" . $awayteam . "-" . $hometeam . " </b>game</td>"; 
    break;
   case "UN":
    echo "<td><b>Under " . $points . " points</b> in the <b>" . $awayteam . "-" . $hometeam . " </b>game</td>"; 
    break;
     }

//print amount
echo "<td>" . $units .  "</td></tr>";

} //end foreach

echo "</table>"; 

mysql_close();

 } // close if !admin loop
?>


<!-- PAGE-SPECIFIC CONTENT ENDS HERE -->
</div>
</body>