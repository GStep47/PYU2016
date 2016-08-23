<?php
session_start();

require 'html_head.inc.php';
require 'login_bar_all.inc.php';
require 'banner.inc.php';
// CHANGE LATER TO HAVE DIFFERENT NAVBARS
require 'navbar.inc.php';
require 'begin_content.inc.php';

?>

<div align="center">
<p><a href="update_lines.php">Update Lines</a></p>
<p><a href="update_scores.php">Enter Game Results</a></p>
<p><a href="error_fix.php">Check for Errors (to be implemented)</a></p>
</div>
</div>
</body>