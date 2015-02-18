<?php
include "src/lib/rebel_parser.class.php";
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
    case "check_mismatch": include "src/arguments_mismatch.php";break;
    case "show_changes": include "src/show_changes.php";break;
    case "make_changes": include "src/make_changes.php";break;
}
