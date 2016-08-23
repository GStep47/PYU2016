<?php
session_start();

echo '<!-- NAVBAR -->';
echo '<div class="navbar" align="center">';
echo '<a href="pyu.php">Home</a>';
echo '<img src="spacer.png" />';
echo '<a href="news.php">News</a>';
echo '<img src="spacer.png" />';
echo '<a href="http://www.voodoofive.com/2013/8/26/4614620/protect-your-unit-version-4-0-upgraded-to-net" target="_new">Rules</a>';
echo '<img src="spacer.png" />';
echo '<a href="standings.php">Standings</a>';
echo '<img src="spacer.png" />';
echo '<a href="make_picks.php">Make Picks</a>';
echo '<img src="spacer.png" />';
echo '<a href="view_picks.php">View Picks</a>';
echo '<img src="spacer.png" />';
echo '<a href="http://southflorida.247sports.com" target="_new">SouthFlorida.247Sports.com</a>';
echo '<img src="spacer.png" />';
echo '<a href="mailto:angerthespy@gmail.com">Email Gary</a>';
//load admin list
require "admin_list.inc.php";

// check admin
if (!$_SESSION['user'] OR !in_array($_SESSION['user'], $admins)) {
// do nothing
} else {
echo '<img src="spacer.png" />';
echo '<a href="admin.php">Admin</a>';
}
?>

</div>