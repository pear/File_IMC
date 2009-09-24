<?php
/**
 * Parse vCard 2.1 and 3.0 text blocks.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 2.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/2_02.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the world-wide-web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  File_Formats
 * @package   File_IMC
 * @author    Till Klampaeckel <till@php.net>
 * @copyright Copyright (c) 2007 Contaxis Limited
 * @license   http://www.php.net/license/2_02.txt  PHP License 2.0
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/File_IMC
 */

/**
 * PHPUnit_Framework_TestCase
 * @ignore
 */
require_once 'PHPUnit/Framework/TestCase.php';

set_include_path(
    realpath(dirname(__FILE__) . '/../')
    . ':' . get_include_path()
);

/**
 * File_IMC
 */
require_once "File/IMC.php";

/**
 * Tests for File_IMC.
 *
 * @category  File_Formats
 * @package   File_IMC
 * @author    Till Klampaeckel <till@php.net>
 * @copyright Copyright (c) 2007 Contaxis Limited
 * @license   http://www.php.net/license/2_02.txt  PHP License 2.0
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/File_IMC
 * @todo      Make protected functions dataproviders.
 */
class File_IMC_BuildTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     * @see self::setUp()
     */
    protected $vcard;

    /**
     * @var File_IMC
     * @see self::setUp()
     */
    protected $parser;

    /**
     * setUp()
     *
     * Setup a vcard (in {@link self::$vcard}.
     *
     * @return void
     * @uses   self::$vcard
     * @uses   self::$parser
     */
    public function setUp()
    {
        $this->vcard = File_IMC::build('vcard');
    }

    /**
     * @expectedException File_IMC_Exception
     */
    public function testExceptionIfNoFormatIsProvided()
    {
        $foo = File_IMC::build('');
    }

    /**
     * @expectedException File_IMC_Exception
     */
    public function testExceptionIfInvalidFormatIsProvided()
    {
        $foo = File_IMC::build('bar');
    }
}
