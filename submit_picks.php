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

// connect to database
require 'db_connect.inc.php'; 

//determine their max weekly bet
$thequery4 = "SELECT BETLIMIT FROM pyu_up WHERE USER=" .chr(39) . $_SESSION['user']. chr(39) .";"; 
$result4 = mysql_query($thequery4);
$theresult4 = mysql_fetch_row($result4);
$betlimit = $theresult4[0];
//$betlimit =  max (250, round($betlimit*.25,2));

foreach ($_POST as $name=>$value) {  
// split $name into gid and bet type
$thesplit = explode (",", $name, 2);
$gid = $thesplit[0];
$what = $thesplit[1];

//validate
$value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
if ($value<1) {$value="0";}  //no negative numbers
if (!$value) {$value="0";} //if fails filter, set to 0
$value = round($value,2);

//make sure they have enough money left
//see how much they've bet already this week
$thequery6 = "SELECT SUM(UNITS) FROM pyu_bets WHERE RESULT =" . chr(39) . "TBD" .chr(39) . " AND USER=" .chr(39) . $_SESSION['user']. chr(39) .";"; 
//echo $thequery6;
$result6 = mysql_query($thequery6);
$theresult6 = mysql_fetch_row($result6);
$betsofar = $theresult6[0];

//set bet value to 0 if this would put them over their limit
if ($betsofar + $value > $betlimit) {$value=0;}

//we have to pull existing pointspread/moneyline value from pyu_games table as it is not passed in the form
$thequery5 = "SELECT " . $what . ", AWAY, HOME FROM pyu_games WHERE GID=" . $gid . ";"; 
$result5 = mysql_query($thequery5);
$theresult5 = mysql_fetch_row($result5);
$points = $theresult5[0];
$awayteam = $theresult5[1];
$hometeam = $theresult5[2];

// prepare query to insert bet
$thequery6 = "INSERT INTO pyu_bets (PARLAYID, USER, GID, BETTYPE, POINTS, UNITS, WAGERTIME) VALUES (0," .chr(39) . $_SESSION['user']. chr(39) . chr(44) . chr(39) . $gid . chr(39) . chr(44) . chr(39) . $what . chr(39) . chr(44) . chr(39) . $points . chr(39) . chr(44) . chr(39) . $value . chr(39) . chr(44) . "NOW());";
echo "</p>" . $thequery[6] . "</p>";

// run the query only if value>1
if ($value >= 1) {
 $result6 = mysql_query($thequery6);

   //tailor message to type of bet
   switch ($what) {
      case "AP":
       echo "<p>" . $value . "-unit wager on " . $awayteam . " ";
       if ($points > 0) {echo "+";}
       echo $points . " over " . $hometeam . " accepted!</p>";
       break;
      case "HP":
       echo "<p>" . $value . "-unit wager on " . $hometeam . " ";
       if ($points > 0) {echo "+";}
       echo $points . " over " . $awayteam . " accepted!</p>";
       break;
      case "AM":
       echo "<p>" . $value . "-unit wager on " . $awayteam . " ";
       if ($points > 0) {echo "+";}
       echo $points . " money line to beat " . $hometeam . " accepted!</p>";        
       break;
      case "HM":
       echo "<p>" . $value . "-unit wager on " . $hometeam . " ";
       if ($points > 0) {echo "+";}
       echo $points . " money line to beat " . $awayteam . " accepted!</p>";        
       break;
      case "OV":
       echo "<p>" . $value . "-unit wager on Over " . $points . " in the " . $awayteam . "-" . $hometeam . " game accepted!</p>";
       break;
      case "UN":
       echo "<p>" . $value . "-unit wager on Under " . $points . " in the " . $awayteam . "-" . $hometeam . " game accepted!</p>";
       break;
     }
} else { 
echo "<p>Wager on " . $awayteam . "-" . $hometeam . " game not accepted, most likely because you are over your limit.</p>";
}

} //end foreach

echo "</table></form>";
mysql_close();

} // end if session user
?>

<!-- PAGE-SPECIFIC CONTENT ENDS HERE -->
</div>
</body>