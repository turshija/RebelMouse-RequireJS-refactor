<?php
include "rebel_parser.class.php";
$rebel = new RebelParser();

$task = "show_changes";

switch ($task) {
    case "check_mismatch": include "arguments_mismatch.php";break;
    case "show_changes": include "show_changes.php";break;
}

