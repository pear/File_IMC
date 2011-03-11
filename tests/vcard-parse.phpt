--TEST--
A simple test to parse a sample vcard with File_IMC
--SKIPIF--
include_once 'File/IMC.php';
if (!class_exists('File_IMC')) {
    die('SKIP File_IMC is not installed, or your include_path is borked.');
}
--FILE--
<?php
require_once 'File/IMC.php';

// create vCard parser
$parse = File_IMC::parse('vCard');

// parse a vCard file and store the data in $cardinfo
$cardinfo = $parse->fromFile(dirname(__FILE__) . '/sample.vcf');

// view the card info array
var_dump($cardinfo['VCARD'][0]['N'][0]['value']);
--EXPECT--
array(5) {
  [0]=>
  array(1) {
    [0]=>
    string(9) "Shagnasty"
  }
  [1]=>
  array(1) {
    [0]=>
    string(7) "Bolivar"
  }
  [2]=>
  array(1) {
    [0]=>
    string(8) "Odysseus"
  }
  [3]=>
  array(1) {
    [0]=>
    string(3) "Mr."
  }
  [4]=>
  array(1) {
    [0]=>
    string(6) "Senior"
  }
}
