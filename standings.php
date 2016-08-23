<?php
session_start();
require 'html_head.inc.php'; // beginning of HTML page
require 'login_bar_all.inc.php'; //login bar
require 'banner.inc.php'; // banner
require 'navbar.inc.php'; //navbar
require 'begin_content.inc.php'; //HTML through beginning of content

echo "<!-- PAGE-SPECIFIC CONTENT STARTS HERE -->";
echo '<table border="1" align="center"><tr style="font-weight: bold;"><td>PLAYER</td><td>UNITS</td></tr>';

// connect to database
require 'db_connect.inc.php'; 

// query
$thequery="SELECT USER, AFFIL, BLOG, UNITS FROM pyu_up WHERE ACTIVE=1 ORDER BY UNITS DESC, USER ASC";
$result=mysql_query($thequery);
$count=mysql_num_rows($result);

// loop through results
for ($i = 1; $i <= $count; $i++) {
$theresult=mysql_fetch_row($result);

// alternate line colors
if ($i % 2 != 0) {
echo '<tr>';
} else {
echo '<tr style="background-color: #E0E0E0">';
}

//add so far this week units to count
//$thequery2="SELECT ROUND(SUM(UNITS*PAYOFF)-SUM(UNITS),2) FROM pyu_bets WHERE USER = " . chr(39) . $theresult[0] . chr(39) ." GROUP BY USER";
//$result2=mysql_query($thequery2);
//$theresult2=mysql_fetch_row($result2);
//$amount = $theresult[3] + $theresult2[0]; //there is only one result
$amount = $theresult[3];

// echo query results
echo "<td>" . $theresult[0] . " "; //name
echo "<img src=" .chr(39) . $theresult[1] . ".gif" . chr(39) . "/ > "; //logo
echo "<span style='font-size:small'><a href='http://www." . $theresult[2]. "' target='_new'>" . $theresult[2] . "</a></span></td>"; //blog
echo "<td>" . $amount . "</td>"; //units
echo "</tr>";

} //end for

// HTML end
echo "</table></body></html>";
?>