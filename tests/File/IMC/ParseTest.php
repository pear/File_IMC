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
 * File_IMC
 */
require_once "File/IMC.php";

/**
 * Tests for File_IMC.
 *
 * @category File_Formats
 * @package  File_IMC
 * @author   Till Klampaeckel <till@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/File_IMC
 */
class File_IMC_ParseTest extends \PHPUnit\Framework\TestCase
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
    public function setUp(): void
    {
        $familyName      = "FamilyName";
        $givenName       = "GivenName";
        $additionalNames = "Additional Names";
        $prefix          = "Prefix.";
        $suffix          = "Suffix";
        $name            = "A.N";

        $param1Name  = "PARAM1";
        $param1Value = "PARAMVALUE1";
        $param2Name  = "X-PARAM";
        $param2Value = "X-VALUE";
        $param3Value = "PARAMVALUE3";

        $this->vcard = "BEGIN:VCARD\n\r" .
            $name .
            ";" . $param1Name . "=" . $param1Value .
            ";" . $param2Name . "=" . $param2Value .
            ";" . $param3Value .
            ":" .
            $familyName . ";" .
            $givenName . ";" .
            $additionalNames . ";" .
            $prefix . ";" .
            $suffix . "\n\r" .
            'TZ;VALUE=text:-05:00; EST; Raleigh/North America'."\r\n" .
            "END:VCARD";

        $this->parser = File_IMC::parse('vcard');
    }

    /**
     * @link http://pear.php.net/manual/en/package.fileformats.contact-vcard-parse.data.php
     *
     * @return string
     */
    protected static function getExampleVcard()
    {
        $vcard  = "BEGIN:VCARD" . PHP_EOL;
        $vcard .= "VERSION:3.0" . PHP_EOL;
        $vcard .= "N:Shagnasty;Bolivar;Odysseus;Mr.;III,B.S." . PHP_EOL;
        $vcard .= "FN:Bolivar Shagnasty" . PHP_EOL;
        $vcard .= "ADR;TYPE=HOME,WORK:;;123 Main,Apartment 101;Beverly Hills;CA;90210" . PHP_EOL;
        $vcard .= "EMAIL;TYPE=HOME;TYPE=WORK:boshag@example.com" . PHP_EOL;
        $vcard .= "EMAIL;TYPE=PREF:boshag@ciaweb.net" . PHP_EOL;
        $vcard .= "END:VCARD";

        return $vcard;
    }

    /**
     * @link http://bugs.horde.org/view.php?actionID=view_file&type=vcf&file=SFMA.vcf&ticket=8366
     *
     * @return array
     */
    public static function getPropertyGroupVcard()
    {
        $vcard  = "BEGIN:VCARD" . "\n";
        $vcard .= "VERSION:3.0" . "\n";
        $vcard .= "N:Braunstein;Sharon;;;" . "\n";
        $vcard .= "FN:Sharon Braunstein" . "\n";
        $vcard .= "ORG:Seed & Feed\, Inc.;" . "\n";
        $vcard .= "EMAIL;type=INTERNET;type=WORK;type=pref:scrooge@seedandfeed.org" . "\n";
        $vcard .= "TEL;type=WORK;type=pref:404-688-6688" . "\n";
        $vcard .= "item1.ADR;type=HOME;type=pref:;;Attn\: Sharon Braunstein\nP.O. Box 5396;Atlanta;GA;31107;" . "\n";
        $vcard .= "item1.X-ABLabel:bill to" . "\n";
        $vcard .= "item1.X-ABADR:us" . "\n";
        $vcard .= "CATEGORIES:Customers:Verendus LLC" . "\n";
        $vcard .= "X-ABUID:8291364B-FCBF-4577-8294-166AC0E8B9C7\:ABPerson" . "\n";
        $vcard .= "END:VCARD";
    	return $vcard;
    }

    /**
     * @param  string $vcard
     * @return void
     */
    public function testPropertyGroups()
    {
        $parsed = $this->parser->fromText($this->vcard);
        $vcardData = $parsed['VCARD'][0];
		$nameValues = $vcardData['N'][0]['value'];
        $this->assertEquals("FamilyName",	$nameValues[0][0]);
        $this->assertEquals("GivenName",	$nameValues[1][0]);
        $this->assertEquals("A",			$vcardData['N'][0]['group']);
    }

    /**
     * Test parameter parsing.
     *
     * @uses self::$parser
     * @uses self::$vcard
     */
    public function testParameters()
    {
        $parsed = $this->parser->fromText($this->vcard);
        $vcardData = $parsed['VCARD'][0];

        $expected = array(
			'PARAM1'		=> array('PARAMVALUE1'),
			'X-PARAM'		=> array('X-VALUE'),
			'PARAMVALUE3'	=> array('PARAMVALUE3'),
		);
        $this->assertSame($expected, $vcardData['N'][0]['param']);
    }

    /**
     * Data provider for {@link self::testExampleParser()}.
     *
     * @return array
     */
    public function exampleProvider()
    {
        $data   = self::getExampleVcard();
        $parser = File_IMC::parse('vcard');
        $parsed = $parser->fromText($data);
        $vcardData = $parsed['VCARD'][0];
        $data = array(
		// VERSION
            array('3.0',				$vcardData['VERSION'][0]['value'][0][0]),
		// N
            array('Shagnasty',			$vcardData['N'][0]['value'][0][0]),
            array('Bolivar',			$vcardData['N'][0]['value'][1][0]),
            array('Odysseus',			$vcardData['N'][0]['value'][2][0]),
            array('Mr.',				$vcardData['N'][0]['value'][3][0]),
            array('III',				$vcardData['N'][0]['value'][4][0]),
            array('B.S.',				$vcardData['N'][0]['value'][4][1]),
		// FN
            array('Bolivar Shagnasty',	$vcardData['FN'][0]['value'][0][0]),
        // ADR
            array('HOME',				$vcardData['ADR'][0]['param']['TYPE'][0]),
            array('WORK',				$vcardData['ADR'][0]['param']['TYPE'][1]),
            array('123 Main',			$vcardData['ADR'][0]['value'][2][0]),
            array('Apartment 101',		$vcardData['ADR'][0]['value'][2][1]),
            array('Beverly Hills',		$vcardData['ADR'][0]['value'][3][0]),
            array('CA',					$vcardData['ADR'][0]['value'][4][0]),
            array('90210',				$vcardData['ADR'][0]['value'][5][0]),
            array('',					$vcardData['ADR'][0]['value'][6][0]),
        // EMAIL
            array('HOME',				$vcardData['EMAIL'][0]['param']['TYPE'][0]),
            array('WORK',				$vcardData['EMAIL'][0]['param']['TYPE'][1]),
            array('boshag@example.com',	$vcardData['EMAIL'][0]['value'][0][0]),
            array('PREF',				$vcardData['EMAIL'][1]['param']['TYPE'][0]),
            array('boshag@ciaweb.net',	$vcardData['EMAIL'][1]['value'][0][0]),

        );
        return $data;
    }

    /**
     * This tests asserts that Contact_Vcard_Parse still behaves just like
     * the example online advertises.
     *
     * @param string $expect The value to expect.
     * @param string $actual The value returned.
     *
     * @return       void
     * @dataProvider exampleProvider
     * @uses         self::getExampleVcard()
     */
    public function testExampleParser($expect, $actual)
    {
        $this->assertSame($expect, $actual);
    }

    /**
     * Small test to cover {@link File_IMC_Parse_Vcard::fromFile()}.
     *
     * More details are covered in {@link self::testExampleParser()} because
     * internally {@link File_IMC_Parse_Vcard::fromText()} is used.
     *
     * @return void
     */
    public function testFromFile()
    {
        $parser = File_IMC::parse('vcard');
        $parsed = $parser->fromFile(dirname(__FILE__) . '/../../sample.vcf');

        $vcard = $parsed['VCARD'][0];

        $this->assertSame('3.0',       $vcard['VERSION'][0]['value'][0][0]);
        $this->assertSame('Shagnasty', $vcard['N'][0]['value'][0][0]);
        $this->assertSame('Bolivar',   $vcard['N'][0]['value'][1][0]);
        $this->assertSame('Odysseus',  $vcard['N'][0]['value'][2][0]);
    }

    /**
     * Test version in array data, vs. new public method getVersion()
     */
    public function testGetVersion()
    {
        $data  = self::getExampleVcard();
        $vcard = $this->parser->fromText($data);

        $this->assertSame(
            $vcard['VCARD'][0]['VERSION'][0]['value'][0][0],
            $this->parser->getVersion()
        );
    }

    /**
     * Data provider
     *
     * @see self::testFluentInterface()
     */
    public static function provideInterfaceTestData()
    {
        $data = array(
            array('setVersion', 'getVersion', '3.0'),
            array('setName', 'getName', array('Doe', 'John')),
        );
        return $data;
    }

    /**
     * Test Exception
     */
    public function testExceptionIfNoFormatIsProvided()
    {
        $this->expectException('File_IMC_Exception');
        $foo = File_IMC::parse('');
    }

    /**
     * Test Exception
     */
    public function testExceptionIfInvalidFormatIsProvided()
    {
        $this->expectException('File_IMC_Exception');
        $foo = File_IMC::parse('bar');
    }
}
