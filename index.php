<?php
include "lib/rebel_parser.class.php";
$rebel = new RebelParser();
?>
<a href="?task=check_mismatch">Check files for arguments mismatch</a>
<br />
<a href="?task=show_changes">Show changes</a>
<br />
<a href="?task=make_changes">Make changes (WRITE, beware!)</a>
<hr />
<?php
if (isset($_GET['task'])) $task = $_GET['task'];
else $task = "check_mismatch";

switch ($task) {
    case "check_mismatch": include "arguments_mismatch.php";break;
    case "show_changes": include "show_changes.php";break;
    case "make_changes": include "make_changes.php";break;
}
