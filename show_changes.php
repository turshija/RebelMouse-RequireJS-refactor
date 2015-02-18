<?php
$files = $rebel->getJSFiles();

foreach ($files as $file) {
    // $contents = $rebel->readFileContents( "../rebelmouse/static/js/widgets/views/dashboard/select.js", 10);
    $contents = $rebel->readFileContents( $file['path'], 10 );

    $isOld = $rebel->isOldSyntax( $contents );

    $echoContents = $rebel::prettyPrintParse($contents);
    if ($isOld) {
        $return = $rebel->convertSyntax($contents, $file['path']);
        if ($return['status'] === 0) continue;

        $prettyPrintMessage = $rebel::prettyPrintParse($return['message']);
        echo "<span style=\"color:#f00\">OLD SYNTAX ! ". $file['path'] . "</span><br /><br />";
        echo "<pre style='background-color:#caa'>{$echoContents}</pre>";
        echo "------ >";
        echo "<pre style='background-color:#aca'>{$prettyPrintMessage}</pre>";
        echo "<hr />";
    } else {
        continue; // dont display new syntax
        echo "<span style=\"color:#0b0\">NEW SYNTAX ! " . $file['path'] . "</span><br /><br />";
        echo "<pre>{$echoContents}</pre>";
        echo "<hr />";
    }
}
?>