<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Paul M. Jones <pmjones@ciaweb.net>                          |
// |          Marshall Roch <mroch@php.net>                               |
// +----------------------------------------------------------------------+
//
// $Id$

require_once 'File/IMC.php';

// create vCard parser
$parse = File_IMC::parse('vCard');

// parse a vCard file and store the data in $cardinfo
$cardinfo = $parse->fromFile('sample.vcf');

// view the card info array
echo '<pre>';
print_r($cardinfo);
echo '</pre>';

?>
