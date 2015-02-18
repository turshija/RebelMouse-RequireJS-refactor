<?php
$files = $rebel->getJSFiles();

foreach ($files as $file) {
    $contents = $rebel->readFileContents( $file['path'], 10 );

    $isOld = $rebel->isOldSyntax( $contents );

    $echoContents = $rebel::prettyPrintParse($contents);
    if ($isOld) {
        $return = $rebel->convertSyntax($contents, $file['path']);
        if ($return['status'] === 1) continue;
        echo $return['message'] . "<br />\n";
    }
}
