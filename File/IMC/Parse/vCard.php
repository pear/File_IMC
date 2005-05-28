<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */ 
// +----------------------------------------------------------------------+ 
// | PHP version 4                                                        | 
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
// +----------------------------------------------------------------------+ 
// 
// $Id$ 


/**
*
* The common IMC parser is needed
*
*/

require_once 'File/IMC/Parse.php';


/**
* 
* This class is a parser for vCards.
*
* Parses vCard 2.1 and 3.0 sources from file or text into a structured
* array.
* 
* Usage:
* 
* <code>
*     // include this class file
*     require_once 'File/IMC.php';
*     
*     // instantiate a parser object
*     $parse = new File_IMC::parse('vCard');
*     
*     // parse a vCard file and store the data
*     // in $cardinfo
*     $cardinfo = $parse->fromFile('sample.vcf');
*     
*     // view the card info array
*     echo '<pre>';
*     print_r($cardinfo);
*     echo '</pre>';
* </code>
* 
*
* @author Paul M. Jones <pmjones@ciaweb.net>
*
* @package File_IMC
* 
*/

class File_IMC_Parse_vCard extends File_IMC_Parse {
    
    
    /**
    *
    * Parses a vCard line value identified as being of the "N"
    * (structured name) type-defintion.
    *
    * @access private
    *
    * @param string $text The right-part (after-the-colon part) of a
    * vCard line.
    * 
    * @return array An array of key-value pairs where the key is the
    * portion-name and the value is the portion-value.  The value itself
    * may be an array as well if multiple comma-separated values were
    * indicated in the vCard source.
    *
    */
    
    function _parseN($text)
    {
    	// array_pad makes sure there are the right number of elements
        $tmp = array_pad($this->splitBySemi($text), 5, '');
        return array(
            $this->splitByComma($tmp[FILE_IMC_VCARD_N_FAMILY]),
            $this->splitByComma($tmp[FILE_IMC_VCARD_N_GIVEN]),
            $this->splitByComma($tmp[FILE_IMC_VCARD_N_ADDL]),
            $this->splitByComma($tmp[FILE_IMC_VCARD_N_PREFIX]),
            $this->splitByComma($tmp[FILE_IMC_VCARD_N_SUFFIX])
        );
    }
    
    
    /**
    *
    * Parses a vCard line value identified as being of the "ADR"
    * (structured address) type-defintion.
    *
    * @access private
    *
    * @param string $text The right-part (after-the-colon part) of a
    * vCard line.
    * 
    * @return array An array of key-value pairs where the key is the
    * portion-name and the value is the portion-value.  The value itself
    * may be an array as well if multiple comma-separated values were
    * indicated in the vCard source.
    *
    */
    
    function _parseADR($text)
    {
    	// array_pad makes sure there are the right number of elements
        $tmp = array_pad($this->splitBySemi($text), 7, '');
        return array(
            $this->splitByComma($tmp[FILE_IMC_VCARD_ADR_POB]),
            $this->splitByComma($tmp[FILE_IMC_VCARD_ADR_EXTEND]),
            $this->splitByComma($tmp[FILE_IMC_VCARD_ADR_STREET]),
            $this->splitByComma($tmp[FILE_IMC_VCARD_ADR_LOCALITY]),
            $this->splitByComma($tmp[FILE_IMC_VCARD_ADR_REGION]),
            $this->splitByComma($tmp[FILE_IMC_VCARD_ADR_POSTCODE]),
            $this->splitByComma($tmp[FILE_IMC_VCARD_ADR_COUNTRY])
        );
    }
    
    
    /**
    * 
    * Parses a vCard line value identified as being of the "NICKNAME"
    * (informal or descriptive name) type-defintion.
    *
    * @access private
    * 
    * @param string $text The right-part (after-the-colon part) of a
    * vCard line.
    * 
    * @return array An array of nicknames.
    *
    */
    
    function _parseNICKNAME($text)
    {
        return array($this->splitByComma($text));
    }
    
    
    /**
    * 
    * Parses a vCard line value identified as being of the "ORG"
    * (organizational info) type-defintion.
    *
    * @access private
    *
    * @param string $text The right-part (after-the-colon part) of a
    * vCard line.
    * 
    * @return array An array of organizations; each element of the array
    * is itself an array, which indicates primary organization and
    * sub-organizations.
    *
    */
    
    function _parseORG($text)
    {
        $tmp = $this->splitbySemi($text);
        $list = array();
        foreach ($tmp as $val) {
            $list[] = array($val);
        }
        
        return $list;
    }
    
    
    /**
    * 
    * Parses a vCard line value identified as being of the "CATEGORIES"
    * (card-category) type-defintion.
    *
    * @access private
    * 
    * @param string $text The right-part (after-the-colon part) of a
    * vCard line.
    * 
    * @return mixed An array of categories.
    *
    */
    
    function _parseCATEGORIES($text)
    {
        return array($this->splitByComma($text));
    }
    
    
    /**
    * 
    * Parses a vCard line value identified as being of the "GEO"
    * (geographic coordinate) type-defintion.
    *
    * @access private
    *
    * @param string $text The right-part (after-the-colon part) of a
    * vCard line.
    * 
    * @return mixed An array of lat-lon geocoords.
    *
    */
    
    function _parseGEO($text)
    {
    	// array_pad makes sure there are the right number of elements
        $tmp = array_pad($this->splitBySemi($text), 2, '');
        return array(
            array($tmp[FILE_IMC_VCARD_GEO_LAT]), // lat
            array($tmp[FILE_IMC_VCARD_GEO_LON])  // lon
        );
    }
}

?>
