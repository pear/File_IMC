<?php

require_once 'File/IMC.php';

$parse = File_IMC::parse('vCalendar');

// parse a vCalendar file and store the data
// in $calinfo
$calinfo = $parse->fromFile('sample.vcs');
    
// view the calendar info array
echo '<pre>';
print_r($calinfo);
echo '</pre>';

?>
