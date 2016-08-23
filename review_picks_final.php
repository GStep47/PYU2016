<?php
session_start();

require 'html_head.inc.php';
require 'login_bar_all.inc.php';
require 'banner.inc.php';
require 'navbar.inc.php';
require 'begin_content.inc.php';

if (!$_SESSION['user']) {
echo "<p>You are not logged in.</p>";
} else {

echo "<h4>Review wagers and enter amounts.</h4>";

echo "<p><b>INSTRUCTIONS</b>: Enter the amount you wish to bet on each
game. If you don't want to bet a game you previously chose, leave blank
or enter 0. Each game you pick will be a straight wager (not a parlay) as the parlay screen is not yet finished.
Until further notice, please email Gary any parlays you wish to place.</p>"; 

// connect to database
require 'db_connect.inc.php'; 

// report amount and current picks
$thequery = "SELECT UNITS, BETLIMIT  FROM pyu_up WHERE USER=" .chr(39) . $_SESSION['user']. chr(39) . ";"; 
$result = mysql_query($thequery);
$theresult = mysql_fetch_row($result);
$units = $theresult[0];
$minbet = max (50, round($units*.05,2));
$maxbet = $theresult[1];

echo "<p>" . $_SESSION['user'] .  ", you have " . $units . " units.</p>";
//echo "<p>You must wager between " . $minbet . " and " . $maxbet . " on all of this week's games.";
echo "<p>You have a total of " . $maxbet . " available to wager on the National Championship game.";

//determine current week
$thequery4 = "SELECT WEEK FROM pyu_games WHERE ISNULL(HOMESCORE) ORDER BY WEEK ASC LIMIT 1;";
$result = mysql_query($thequery4);
$theresult = mysql_fetch_row($result);
$currentweek= $theresult[0];
//echo "current week is " . $currentweek;

//add up wagers already made this week
$thequery2 = "SELECT SUM(UNITS) FROM pyu_bets WHERE RESULT=" . chr(39) . "TBD" . chr(39) . " AND USER=" . chr(39) . $_SESSION['user']. chr(39) . ";"; 
$result2 = mysql_query($thequery2);
$theresult2 = mysql_fetch_row($result2);
$alreadybet=$theresult2[0];
if ($alreadybet == 0) {
echo " You have not made any wagers on the national championship game yet.</p>";
} else {
echo " You have previously wagered " . $alreadybet . " on the national championship game.</p>";
}

// number of bets
$thequery4 = "SELECT COUNT(DISTINCT GID) FROM pyu_bets WHERE RESULT=" . chr(39). "TBD" . chr(39) . " AND USER =" .chr(39) .  $_SESSION['user']. chr(39) . ";"; 
$result4 = mysql_query($thequery4);
$theresult4 = mysql_fetch_row($result4);
$alreadybetgames=$theresult4[0];

//echo "<p>You must bet on at least 3 different games each week. You have previously bet on " . $alreadybetgames . " games.</p>";
//echo "<p><b>For bowl season, you must bet on at least 5 different bowl games.</b> You have previously bet on " . $alreadybetgames . " games.</p>";

//wagered AAC game?
//echo "<p>You must also pick at least one American bowl game.";
//echo "<p>You must also pick at least <b>two</b> American Athletic Conference bowl games.";
$thequery3 = "SELECT SUM(pyu_games.AAC) FROM pyu_games JOIN pyu_bets ON pyu_bets.GID = pyu_games.GID WHERE (pyu_bets.user = " .chr(39) . $_SESSION['user'] . chr(39) . " AND pyu_bets.RESULT=" .chr(39). "TBD" . chr(39) . ");";
$result3 = mysql_query($thequery3);
$theresult3 = mysql_fetch_row($result3);
$alreadyaac=$theresult3[0];
if ($alreadyaac == 0) {
//echo " You have not yet wagered an AAC/Big East game.</p>";
} else {
//echo " You previously met this requirement. </p>";
}

//form
echo '<form name="bettingform" action="submit_picks.php" method="post">';
echo '<div align="center"><input type="submit" value="Submit"></div>';

// start table
echo '<table border="1">'; 
echo "<tr><td><b>GAME</b></td><td><b>AMOUNT</b></td></tr>";

foreach ($_POST as $name=>$value) {  
// split $name into gid and bet type
$thesplit = explode (",", $name, 2);
$gid = $thesplit[0];
$what = $thesplit[1];
//echo "<p>GAME ID: " . $gid . " BET TYPE: " . $what . " VALUE: " . $value . "</p>";

//get game data
$thequery = "SELECT AWAY, HOME FROM pyu_games WHERE GID=".chr(39). $gid . chr(39); 
$result = mysql_query($thequery);
$theresult = mysql_fetch_row($result);
$awayteam=$theresult[0];
$hometeam=$theresult[1];

//print 
   switch ($what) {
      case "AP":
        echo "<tr><td>" . $awayteam . " ";
        if ($value>0) {echo "+";}
        echo $value . " points over " . $hometeam . ".</td>";
        echo "<td><input type=" . chr(39) . "text" . chr(39) . " name=" . chr(39) . $gid . "," . $what . chr(39) . " size=" .chr(39) . "10" .chr(39) . " maxlength=" .chr(39) . "10" .chr(39) .  "></input></td>";
        echo "</td></tr>";
        break;
      case "HP":
        echo "<tr><td>" . $hometeam . " ";
        if ($value>0) {echo "+";}
        echo $value . " points over " . $awayteam . ".</td>";
        echo "<td><input type=" . chr(39) . "text" . chr(39) . " name=" . chr(39) . $gid . "," . $what . chr(39) . " size=" .chr(39) . "10" .chr(39) . " maxlength=" .chr(39) . "10" .chr(39) .  "></input></td>";
        echo "</td></tr>";
        break;
      case "AM":
        echo "<tr><td>" . $awayteam . " ";
        if ($value>0) {echo "+";}
        echo $value . " money line to beat  " . $hometeam . ".</td>";
        echo "<td><input type=" . chr(39) . "text" . chr(39) . " name=" . chr(39) . $gid . "," . $what . chr(39) . " size=" .chr(39) . "10" .chr(39) . " maxlength=" .chr(39) . "10" .chr(39) .  "></input></td>";
        echo "</td></tr>";
        break;
      case "HM":
        echo "<tr><td>" . $hometeam . " ";
        if ($value>0) {echo "+";}
        echo $value . " money line to beat  " . $awayteam . ".</td>";
        echo "<td><input type=" . chr(39) . "text" . chr(39) . " name=" . chr(39) . $gid . "," . $what . chr(39) . " size=" .chr(39) . "10" .chr(39) . " maxlength=" .chr(39) . "10" .chr(39) .  "></input></td>";
        echo "</td></tr>";
        break;
      case "OV":
        echo "<tr><td>Over " . $value . " points in the " . $hometeam . "-" . $awayteam . " game. </td>";
        echo "<td><input type=" . chr(39) . "text" . chr(39) . " name=" . chr(39) . $gid . "," . $what . chr(39) . " size=" .chr(39) . "10" .chr(39) . " maxlength=" .chr(39) . "10" .chr(39) .  "></input></td>";
        echo "</td></tr>";
        break;
      case "UN":
        echo "<tr><td>Under " . $value . " points in the " . $hometeam . "-" . $awayteam . " game. </td>";
        echo "<td><input type=" . chr(39) . "text" . chr(39) . " name=" . chr(39) . $gid . "," . $what . chr(39) . " size=" .chr(39) . "10" .chr(39) . " maxlength=" .chr(39) . "10" .chr(39) .  "></input></td>";
        echo "</td></tr>";
        break;
     }

} //end foreach
echo "</table></form>";
mysql_close();

} // end if session user
?>

<!-- PAGE-SPECIFIC CONTENT ENDS HERE -->
</div>
</body>