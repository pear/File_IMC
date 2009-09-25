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

set_include_path(
    realpath(dirname(__FILE__) . '/../')
    . ':' . get_include_path()
);

/**
 * File_IMC
 */
require_once "File/IMC.php";

/**
 * Tests for File_IMC_Parse_Vcalendar.
 *
 * @category File_Formats
 * @package  File_IMC
 * @author   Till Klampaeckel <till@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/File_IMC
 */
class File_IMC_Parse_VcalendarTest extends PHPUnit_Framework_TestCase
{
    protected static $parser;
    protected static $calendar;

    public function setUp()
    {
        self::$parser = File_IMC::parse('vcalendar');

        $calinfo = self::$parser->fromFile(dirname(__FILE__) . '/sample.vcs');

        self::$calendar = $calinfo['VCALENDAR'][0];
    }

    public static function vcalendarProvider()
    {
    }

    public function testEventCount()
    {
        $events = self::$calendar['VEVENT'];
        $this->assertEquals(3, count($events));
    }

    public function testVersion()
    {
        $this->assertSame('1.0', self::$calendar['VERSION'][0]['value'][0][0]);
        $this->assertSame(self::$calendar['VERSION'][0]['value'][0][0], self::$parser->getVersion());
    }

    public static function eventProvider()
    {
        $parser   = File_IMC::parse('vcalendar');
        $calinfo  = $parser->fromFile(dirname(__FILE__) . '/sample.vcs');
        $calendar = $calinfo['VCALENDAR'][0];

        $events = $calendar['VEVENT'];

        $event1 = $events[0];
        $event2 = $events[1];
        $event3 = $events[2];

        //var_dump(self::$calendar, $events, $event1, $event2, $event3); exit;
        //var_dump($event2); exit;

        $event1_desc  = "Interested in becoming a volunteer for the Sacramento SPCA? We'd love to have you join our team! Please download a volunteer application from this website, and when you mail it in, indicate that you'd like to attend this orientation.";
        $event1_desc .= "\n\n";
        $event1_desc .= "Contact Dee Dee Drake for more information.";

        $event2_desc = "Blah blah blah! This one doesn't have any linebreaks, so it's not quoted-printable";

        $event3_desc = "Lorem ipsum dolor sit amet!";

        return array(

        // event 1
            array('New Volunteer Orientation', $event1['SUMMARY'][0]['value'][0][0]),
            //array($event1_desc, $event1['DESCRIPTION'][0]['value'][0][0]),

        // event 2
            array('Test Event 2', $event2['SUMMARY'][0]['value'][0][0]),
            array($event2_desc, $event2['DESCRIPTION'][0]['value'][0][0]),
            array('"http://www.example.com"', $event2['DESCRIPTION'][0]['param']['ALTREP'][0]),

        // event 3
            array('Test Event 3', $event3['SUMMARY'][0]['value'][0][0]),
            array($event3_desc, $event3['DESCRIPTION'][0]['value'][0][0]),
        );
    }

    /**
     * @dataProvider eventProvider
     */
    public function testEvents($assert, $actual)
    {
        $this->assertSame($assert, $actual);
    }
}
