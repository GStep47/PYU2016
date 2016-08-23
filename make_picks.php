<?php
session_start();
require 'html_head.inc.php'; // beginning of HTML page
require 'login_bar_all.inc.php'; //login bar
require 'banner.inc.php'; // banner
require 'navbar.inc.php'; //navbar
require 'begin_content.inc.php'; //HTML through beginning of content

if (!$_SESSION['user']) {
echo "<p>You are not logged in. Please log in above to enter your picks. ";
} else {
echo "<p>Make selections below! ";
}

echo 'American Athletic Conference/former Big East games in <span style="color: E77471;">red</span> or <span style="color: 56A5EC;">blue</span>. ';
echo "You may enter bets until one hour before kickoff time. ";
echo "All times Eastern. ";
echo "</p>";
echo "<p><b>INSTRUCTIONS</b>: Check each game you want to wager on, and click
Submit. On the next screen, you will enter the amounts. Each game you pick
will be a straight wager (not a parlay) as the parlay screen is not yet finished.
Until further notice, please email Gary any parlays you wish to place.</p>"; 
// connect to database
require 'db_connect.inc.php'; 

// query for earliest week games with no final scores entered
$thequery="SELECT DATE_FORMAT(CONVERT_TZ(DATE, '-07:00', '-04:00'),'%a, %b %e, %l:%i %p'), AWAY, HOME, SITE, AP, HP, AM, HM, OV, UN, GID, AAC, DATE_FORMAT(CONVERT_TZ(LASTUPDATE, '-07:00', '-04:00'),'%a, %b %e, %l:%i %p') FROM pyu_games WHERE DATE>(NOW() - INTERVAL 1 HOUR) AND WEEK = (SELECT WEEK FROM pyu_games WHERE ISNULL(HOMESCORE) ORDER BY WEEK ASC LIMIT 1) ORDER BY DATE ASC";
$result=mysql_query($thequery);
$count=mysql_num_rows($result);
echo '<form name="bettingform" action="review_picks_final.php" method="post">';

// show submit button only if logged in
if ($_SESSION['user']) {
echo '<div align="center"><input type="submit" value="Submit"></div>';
//echo "fixing errors with lines. Check back in a few minutes. - Gary";
}

echo '<table border="1" style="font-size:small"><tr><td>KICKOFF</td><td>TEAMS</td><td>SPREAD</td><td>MONEY</td><td>O/U</td><td>LAST UPDATED</td></tr>';

for ($i = 1; $i <= $count; $i++) {
	$theresult=mysql_fetch_row($result);

// add row coloring for away team
if ($i % 2 != 0) {
  if ($theresult[11] == 0 ) {
      echo '<tr><td rowspan="2">' . $theresult[0];
  } else {
      echo '<tr style="background-color: #E77471"><td rowspan="2">' . $theresult[0];
  }
} else { 
  if ($theresult[11] == 0 ) {
      echo '<tr style="background-color: #E0E0E0"><td rowspan="2">' . $theresult[0];
  } else {
      echo '<tr style="background-color: #56A5EC"><td rowspan="2">' . $theresult[0];
  }
} //end if


//add location if there is one
if (!is_null($theresult[3])) {echo "<br />at " . $theresult[3];} 
echo "</td>"; //date
echo "<td>" . $theresult[1] . "</td>"; //away team

//away pointspread
if (is_null($theresult[4])) {
echo "<td>&nbsp;</td>";
} elseif ($theresult[4] > 0) {
echo "<td>+" . $theresult[4]; 
} else {
echo "<td>" . $theresult[4]; 
}
if (isset($_SESSION['user']) AND !is_null($theresult[4])) {
//echo "<input type=" . chr(39) . "checkbox" . chr(39) . " value=" . chr(39) . "twgForm[]" . chr(39) . " value=" .chr(39) . $theresult[10] . "AP" . chr(39) . "></input>";
echo "<input type=" . chr(39) . "checkbox" . chr(39) . " name=" . chr(39) . $theresult[10] . ",AP" . chr(39) . " value=" .chr(39) . $theresult[4] . chr(39) . " size=" .chr(39) . "5" .chr(39) . " maxlength=" .chr(39) . "5" .chr(39) .  "></input></td>";
}
echo "</td>";

//away money line
if (is_null($theresult[6])) {
echo "<td>&nbsp;";
} else {
   echo "<td>";
   if ($theresult[6] > 0) {echo "+";} //add plus sign if positive
   echo $theresult[6];
}
if (isset($_SESSION['user']) AND !is_null($theresult[6])) {
//echo "<input type=" . chr(39) . "checkbox" . chr(39) . " name=" . chr(39) . "twgForm[]" . chr(39) . " value=" .chr(39) . $theresult[10] . "AM" . chr(39) . "></input>";
echo "<input type=" . chr(39) . "checkbox" . chr(39) . " name=" . chr(39) . $theresult[10] . ",AM" . chr(39) . " value=" .chr(39) . $theresult[6] . chr(39) . " size=" .chr(39) . "5" .chr(39) . " maxlength=" .chr(39) . "5" .chr(39) .  "></input></td>";
}
echo "</td>";

//over
if (is_null($theresult[8])) {
echo "<td>&nbsp;";
} else {
echo "<td>Over " . $theresult[8];
}
if (isset($_SESSION['user']) AND !is_null($theresult[8])) {
//echo "<input type=" . chr(39) . "checkbox" . chr(39) . " name=" . chr(39) . "twgForm[]" . chr(39) . " value=" .chr(39) . $theresult[10] . "OV" . chr(39) . "></input>";
echo "<input type=" . chr(39) . "checkbox" . chr(39) . " name=" . chr(39) . $theresult[10] . ",OV" . chr(39) . " value=" .chr(39) . $theresult[8] . chr(39) . " size=" .chr(39) . "5" .chr(39) . " maxlength=" .chr(39) . "5" .chr(39) .  "></input></td>";
}

echo "</td>";

//last updated
echo '<td rowspan="2">';
echo $theresult[12];
echo "</td>";

// add row coloring for home team
if ($i % 2 != 0) {
  if ($theresult[11] == 0 ) {
      echo '<tr>';
  } else {
      echo '<tr style="background-color: #E77471">';
  }
} else { 
  if ($theresult[11] == 0 ) {
      echo '<tr style="background-color: #E0E0E0">';
  } else {
      echo '<tr style="background-color: #56A5EC">';
  }
} //end if

// home team name
echo "<td>" . $theresult[2] . "</td>"; 

//home pointspread
if (is_null($theresult[5])) {
echo "<td>&nbsp;</td>";
} elseif ($theresult[5] > 0) {
echo "<td>+" . $theresult[5]; 
} else {
echo "<td>" . $theresult[5]; 
}
if (isset($_SESSION['user']) AND !is_null($theresult[5])) {
//echo "\n<input type=" . chr(39) . "checkbox" . chr(39) . " value=" . chr(39) . "twgForm[]" . chr(39) . " value=" .chr(39) . $theresult[10] . "HP" . chr(39) . "></input>";
echo "<input type=" . chr(39) . "checkbox" . chr(39) . " name=" . chr(39) . $theresult[10] . ",HP" . chr(39) . " value=" .chr(39) . $theresult[5] . chr(39) . " size=" .chr(39) . "5" .chr(39) . " maxlength=" .chr(39) . "5" .chr(39) .  "></input></td>";
}
echo "</td>";


//home money line
if (is_null($theresult[7])) {
echo "<td>&nbsp;";
} else {
   echo "<td>";
   if ($theresult[7] > 0) {echo "+";} //add plus sign if positive
   echo $theresult[7];
}
if (isset($_SESSION['user']) AND !is_null($theresult[7])) {
//echo "<input type=" . chr(39) . "checkbox" . chr(39) . " name=" . chr(39) . "twgForm[]" . chr(39) . " value=" .chr(39) . $theresult[10] . "HM" . chr(39) . "></input>";
echo "<input type=" . chr(39) . "checkbox" . chr(39) . " name=" . chr(39) . $theresult[10] . ",HM" . chr(39) . " value=" .chr(39) . $theresult[7] . chr(39) . " size=" .chr(39) . "5" .chr(39) . " maxlength=" .chr(39) . "5" .chr(39) .  "></input></td>";
}
echo "</td>";


// under
if (is_null($theresult[9])) {
echo "<td>&nbsp;";
} else {
echo "<td>Under " . $theresult[9]; 
}
if (isset($_SESSION['user']) AND !is_null($theresult[9])) {
//echo "<input type=" . chr(39) . "checkbox" . chr(39) . " name=" . chr(39) . "twgForm[]" . chr(39) . " value=" .chr(39) . $theresult[10] . "UN" . chr(39) . "></input>";
echo "<input type=" . chr(39) . "checkbox" . chr(39) . " name=" . chr(39) . $theresult[10] . ",UN" . chr(39) . " value=" .chr(39) . $theresult[9] . chr(39) . " size=" .chr(39) . "5" .chr(39) . " maxlength=" .chr(39) . "5" .chr(39) .  "></input></td>";
}

echo "</td></tr>";
} //this bracket closes the for loop

mysql_close();

?>
</table><br /></form>

<!-- PAGE-SPECIFIC CONTENT ENDS HERE -->
</div>
</body>