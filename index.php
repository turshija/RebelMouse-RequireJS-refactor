<?php
include "lib/rebel_parser.class.php";
$rebel = new RebelParser();

$task = "make_changes";

switch ($task) {
    case "check_mismatch": include "arguments_mismatch.php";break;
    case "show_changes": include "show_changes.php";break;
    case "make_changes": include "make_changes.php";break;
}

