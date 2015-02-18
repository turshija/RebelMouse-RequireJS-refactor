<?php
class RebelParser {
    /* Path to root of the RebelMouse project */
    private $rebelMousePath = "../rebelmouse";

    /* Path to JS files to scan recursively */
    private $staticPath = "/static/js";

    /* When ignoring folder, put "/" before and after name */
    private $ignoredPaths = array(
        "/libs/",
        "/js/whitelabel/apps/404.js",
        "/js/widgets/views/simple_redactor.js",
        "/js/widgets/views/upload_image_form.js",
        "/js/utils/croptool.js",
        "/js/apps/embedded_widget_horizontal.js"
    );

    /* Regex - http://www.regexr.com/3aeoq */
    private $oldSyntaxRegex = "/^(define|require)\(\[(.+?)\],(\s+?)function(.+?)?\((.+?)\)(.+?)?\{/mi";

    /* Tab size */
    private $tabSize = "    ";

    /* Returns all JS files from project */
    public function getJSFiles() {
        $dir_iterator = new RecursiveDirectoryIterator($this->rebelMousePath . $this->staticPath);
        $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);

        $files = array();
        foreach ($iterator as $file) {
            if ( !$file->isFile() ) continue;
            if ( $file->getExtension() !== 'js' ) continue;

            $skipFile = false;
            foreach ($this->ignoredPaths as $folder) {
                if (strpos($file->__toString(), $folder) !== false) {
                    $skipFile = true;
                    break;
                }
            }
            if ( $skipFile ) continue;

            $files[] = array(
                'path'=> $file->__toString(),
                'filename' => $file->getFilename(),
                'isWritable' => $file->isWritable(),
                'extension' => $file->getExtension()
            );
        }

        return $files;
    }

    public function readFileContents( $path, $numLines = 0 ) {
        $fileinfo = new SplFileObject( $path, "r" );
        $charsSkip = array('/','*');

        if ($fileinfo->isReadable()) {
            if (!$numLines) {
                $contents = $fileinfo->fread($fileinfo->getSize());
            } else {
                $contents = "";
                while (!$fileinfo->eof() && $numLines>0) {
                    $line = $fileinfo->fgets();
                    // skip lines starting with chars from $charsSkip
                    // if (in_array(trim($line)[0], $charsSkip)) continue;
                    // if (empty(trim($line))) continue;
                    $contents .= $line;
                    $numLines--;
                }
            }

            return $contents;
        } else {
            return false;
        }
    }

    public function saveFileContents( $file ) {
        // $fileinfo = new SplFileInfo('/tmp/foo.txt');
        // if ($fileinfo->isWritable()) {
        //     $fileobj = $fileinfo->openFile('a');
        //     $fileobj->fwrite("appended this sample text");
        // }
    }

    public function isOldSyntax( $contents ) {
        preg_match($this->oldSyntaxRegex, $contents, $matches);

        if ( count($matches) ) return true;
        else return false;
    }

    public static function prettyPrintParse($text) {
        $text = preg_replace("/</", "&lt;", $text);
        $text = preg_replace("/\n/", "<br />", $text);
        return $text;
    }
    /**
     * Script converts old syntax to new one
     *
     * Old syntax:
     * define(['jquery', 'backbone', 'underscore', 'hogan', 'widgets/views/selector', 'hgn!widgets/templates/river/sidebar'],
     *       function ($, BB, _, Hogan, SelectorView, template) {
     *
     * New syntax:
     *   define(function (require) {
     *       var $ = require('jquery'),
     *           BB = require('backbone'),
     *           _ = require('underscore'),
     *           Hogan = require('hogan'),
     *           SelectorView = require('widgets/views/selector'),
     *           template = require('hgn!widgets/templates/river/sidebar');
     */
    public function convertSyntax( $contents, $path = "" ) {
        $defineOrRequire = array('define', 'require');
        preg_match_all($this->oldSyntaxRegex, $contents, $matches);

        $paths = array_map('trim', explode(',', $matches[2][0]));
        $vars = array_map('trim', explode(',', $matches[5][0]));
        $function = array_map('trim', explode(',', $matches[1][0]))[0];

        if (!in_array($function, $defineOrRequire)) {
            return $this->arrayReturn(0, "\$function is not one of the: " . implode(', ', $defineOrRequire));
        }
        if (!count($paths) || !count($vars)) {
            return $this->arrayReturn(0, "Path or Vars is empty !");
        }
        if ( count($paths) != count($vars) ) {
            return $this->arrayReturn(0, "Paths and Vars differ in size in file " . $path);
        }
        $newSyntax = "define(function (require) {\n";
        $newSyntax .= $this->tabSize . "var ";

        for ($i = 0; $i < count($vars); $i++ ) {
            if ($i > 0) {
                $newSyntax .= $this->tabSize . $this->tabSize;
            }
            $newSyntax .= $vars[$i] . " = require(" . $paths[$i] . "),\n";
        }
        // remove new line and , from the end
        $newSyntax = substr($newSyntax,0,-2);
        $newSyntax .= ";\n";

        // echo $newSyntax;
        $newContents = preg_replace($this->oldSyntaxRegex, $newSyntax, $contents);
        // echo $newContents;
        // print_r($contents);
        // print_r($matches);
        // print_r($paths);
        // print_r($vars);
        return $this->arrayReturn(1, $newContents);

    }

    private function arrayReturn( $status, $message ) {
        return array(
            "status" => (int)$status,
            "message" => $message
        );
    }
}

?>