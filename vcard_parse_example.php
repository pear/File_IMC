<?php

require_once 'File/IMC.php';

$parse = File_IMC::parse('vCard');

// parse a vCard file and store the data
// in $cardinfo
$cardinfo = $parse->fromFile('sample.vcf');
    
// view the card info array
echo '<pre>';
print $cardinfo[0]["N"][0]["value"][0][0];
print_r($cardinfo);
echo '</pre>';

?>
