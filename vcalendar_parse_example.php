<?php

require_once 'File/IMC.php';

$parse = File_IMC::parse('vCalendar');

// parse a vCard file and store the data
// in $cardinfo
$calinfo = $parse->fromFile('sample.vcs');
    
// view the card info array
echo '<pre>';
print_r($calinfo);
echo '</pre>';

?>
