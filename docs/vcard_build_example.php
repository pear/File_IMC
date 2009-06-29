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

// include the class file
require_once 'File/IMC.php';

// instantiate a builder object
// (defaults to version 3.0)
$vcard = File_IMC::build('vCard');

// set a formatted name
$vcard->setFormattedName('Bolivar Shagnasty');

// set the structured name parts
$vcard->setName('Shagnasty', 'Bolivar', 'Odysseus', 'Mr.', 'III');

// add a work email.  note that we add the value
// first and the param after -- Contact_Vcard_Build
// is smart enough to add the param in the correct
// place.
$vcard->addEmail('boshag@example.com');
$vcard->addParam('TYPE', 'WORK');

// add a home/preferred email
$vcard->addEmail('bolivar@example.net');
$vcard->addParam('TYPE', 'HOME');
$vcard->addParam('TYPE', 'PREF');

// add a work address
$vcard->addAddress('POB 101', 'Suite 202', '123 Main',
                   'Beverly Hills', 'CA', '90210', 'US');
$vcard->addParam('TYPE', 'WORK');

// get back the vCard and print it
$text = $vcard->fetch();
echo '<pre>';
print_r($text);
echo '</pre>';
    
?>
