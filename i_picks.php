<html>
<head></head>
<body>

<?php

// connect to database
require 'db_connect.inc.php'; 

$thequery="SELECT DATE_FORMAT(CONVERT_TZ(DATE, '-07:00', '-04:00'),'%a, %b %e, %l:%i %p'), AWAY, HOME, SITE, AP, HP, AM, HM, OV, UN, GID, AAC, DATE_FORMAT(CONVERT_TZ(LASTUPDATE, '-07:00', '-04:00'),'%a, %b %e, %l:%i %p') FROM pyu_games WHERE DATE>NOW() AND WEEK = (SELECT WEEK FROM pyu_games WHERE ISNULL(HOMESCORE) LIMIT 1) ORDER BY DATE ASC";
$result=mysql_query($thequery);
$count=mysql_num_rows($result);
echo '<table border="1" style="font-size:small"><tr><td>KICKOFF</td><td>TEAMS</td><td>SPREAD</td><td>MONEY</td><td>O/U</td><td>LAST UPDATED</td></tr>';

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
echo "</td>";

//away money line
if (is_null($theresult[6])) {
echo "<td>&nbsp;";
} else {
   echo "<td>";
   if ($theresult[6] > 0) {echo "+";} //add plus sign if positive
   echo $theresult[6];
}
echo "</td>";

//over
if (is_null($theresult[8])) {
echo "<td>&nbsp;";
} else {
echo "<td>Over " . $theresult[8];
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
echo "</td>";


//home money line
if (is_null($theresult[7])) {
echo "<td>&nbsp;";
} else {
   echo "<td>";
   if ($theresult[7] > 0) {echo "+";} //add plus sign if positive
   echo $theresult[7];
}
echo "</td>";


// under
if (is_null($theresult[9])) {
echo "<td>&nbsp;";
} else {
echo "<td>Under " . $theresult[9]; 
}

echo "</td></tr>";
} //this bracket closes the for loop

mysql_close();

?>

</body>
</html>