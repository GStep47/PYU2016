<?php
session_start();

require 'html_head.inc.php';
require 'login_bar_all.inc.php';
require 'banner.inc.php';
require 'navbar.inc.php';
require 'begin_content.inc.php';

echo "<!-- PAGE-SPECIFIC CONTENT STARTS HERE -->";
echo "<table border='1'><tr><td><b>USER</b></td><td><b>WAGER</b></td><td><b>UNITS</b></td><td><b>RESULT</b></td><td><b>NET</b></td></tr>";

// connect to database
require 'db_connect.inc.php'; 

$thequery="SELECT USER, pyu_games.AWAY, pyu_games.HOME, pyu_bets.BETTYPE, pyu_bets.POINTS, pyu_bets.UNITS, pyu_bets.RESULT, pyu_bets.PAYOFF, pyu_games.AWAYSCORE, pyu_games.HOMESCORE FROM pyu_bets JOIN pyu_games ON pyu_bets.gid = pyu_games.gid WHERE USER=" . chr(34) . $_SESSION['user'] . chr(34) . " ORDER BY pyu_games.week DESC, pyu_bets.units DESC";
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
$betresult=$theresult[6];
$payoff=$theresult[7];
$awayscore = $theresult[8];
$homescore = $theresult[9];

if (!is_null($awayscore)) {
 if ($awayscore > $homescore) {
	  $scoretext = $awayteam . " " . $awayscore . ", " . $hometeam . " " . $homescore;
   } else {
   $scoretext = $hometeam . " " . $homescore . ", " . $awayteam . " " . $awayscore;
  }
  } else {
  $scoretext= "";
}

//print user
echo "<tr><td>" . $user . "</td>";

//print bet text
switch ($bettype) {
   case "AP":
    echo "<td><b>" . $awayteam . "</b> "; 
    if ($points > 0) {echo "+";}
    echo $points . " points over <b>" . $hometeam;
    break;
   case "HP":
    echo "<td><b>" . $hometeam . "</b> "; 
    if ($points > 0) {echo "+";}
    echo $points . " points over <b>" . $awayteam;
    break;
   case "AM":
    echo "<td><b>" . $awayteam . "</b> "; 
    if ($points > 0) {echo "+";}
    echo $points . " money line to beat <b>" . $hometeam;
    break;
   case "HM":
    echo "<td><b>" . $hometeam . "</b> "; 
    if ($points > 0) {echo "+";}
    echo $points . " money line to beat <b>" . $awayteam;
    break;
   case "OV":
    echo "<td><b>Over " . $points . " points</b> in the <b>" . $awayteam . "-" . $hometeam . " </b>game"; 
    break;
   case "UN":
    echo "<td><b>Under " . $points . " points</b> in the <b>" . $awayteam . "-" . $hometeam . " </b>game"; 
    break;
     }

//print score in each cell where available
echo "</b><br /><i>" . $scoretext . "</i>";
echo "</td>";

//print amount
echo "</td><td>" . $units .  "</td>";

//print betresult
echo "</td><td>" . $betresult .  "</td>";

//print net
echo "</td><td>" . number_format(round($units * $payoff,2),2) .  "</td></tr>";

} //end foreach

echo "</table>"; 

mysql_close();
?>


<!-- PAGE-SPECIFIC CONTENT ENDS HERE -->
</div>
</body>