<?php
$files = $rebel->getJSFiles();

foreach ($files as $file) {
    // debug
    // $contents = $rebel->readFileContents( "../rebelmouse/static/js/widgets/views/dashboard/select.js", 10);
    $contents = $rebel->readFileContents( $file['path'], 10 );

    $isOld = $rebel->isOldSyntax( $contents );

    $echoContents = $rebel::prettyPrintParse($contents);
    if ($isOld) {
        $return = $rebel->convertSyntax($contents, $file['path']);
        // if ($return['status'] === 1) continue;

        // die();
        echo "<span style=\"color:#f00\">OLD SYNTAX ! ". $file['path'] . "</span><br /><br />";
        echo "<pre>{$echoContents}</pre>";
        echo "<hr />";
    } else {
        // echo "<span style=\"color:#0b0\">NEW SYNTAX ! " . $file['path'] . "</span><br /><br />";
        // echo "<pre>{$echoContents}</pre>";
        // echo "<hr />";
    }
    // break;
}
// print_r($files);


?>