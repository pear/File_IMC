<?php
/**
 * Parse vCard 2.1 and 3.0 text blocks.
 *
 * PHP versions 5
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
 * @license   http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version   SVN: $Id$
 * @link      http://pear.php.net/package/File_IMC
 */

/**
 * PHPUnit_Framework_TestCase
 * @ignore
 */
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * File_IMC
 */
require_once "File/IMC.php";

/**
 * Tests for File_IMC_Build.
 *
 * @category File_Formats
 * @package  File_IMC
 * @author   Till Klampaeckel <till@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/File_IMC
 */
class File_IMC_BuildTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var File_IMC_Build_Vcard
     * @see self::setUp()
     */
    protected $vcard;

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

    /**
     * Test the fluent interface.
     */
    public function testFluentInterface()
    {
        $this->assertType('File_IMC_Build_Vcard', $this->vcard->setName('Doe', 'John'));
        $this->assertType('File_IMC_Build_Vcard', $this->vcard->setSource('Your mom.'));
    }

    /**
     * Test formatted name set and get.
     */
    public function testFormattedName()
    {
        $name = 'Jane Doe';

        $this->vcard->setFormattedName($name);
        $this->assertSame("FN:{$name}", $this->vcard->getFormattedName());
    }

    /**
     * @expectedException File_IMC_Exception
     */
    public function testVersionException()
    {
        $this->vcard->setVersion('4.0');
    }

    /**
     * Test version set and get.
     */
    public function testVersion()
    {
        $version = '2.1';
        $this->vcard->setVersion($version);

        $this->assertSame("VERSION:{$version}", $this->vcard->getVersion());
    }
}
