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
 * @category File_Formats
 * @package  File_IMC
 * @author   Till Klampaeckel <till@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version  SVN: $Id$
 * @link     http://pear.php.net/package/File_IMC
 */

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
    /**
     * @var File_IMC_Parse_Vcalendar
     */
    protected static $parser;

    /**
     * @var array
     */
    protected static $calendar;

    /**
     * Setup for all test methods
     *
     * @uses self::$parser
     * @uses self::$calendar
     */
    public function setUp()
    {
        self::$parser = File_IMC::parse('vcalendar');

        $calinfo = self::$parser->fromFile(dirname(__FILE__) . '/../../../sample.vcs');

        self::$calendar = $calinfo['VCALENDAR'][0];
    }

    /**
     * To be used.
     */
    public static function vcalendarProvider()
    {
    }

    /**
     * Test ArrayIterator::count()
     */
    public function testEventCount()
    {
        $events = self::$calendar['VEVENT'];
        $this->assertEquals(3, count($events));

        $events = self::$parser->getEvents();
        $this->assertEquals(3, count($events));
    }

    /**
     * Test and compare {@link File_IMC_Parse_Vcalendar::getVersion()} to the array
     * structure internally used.
     *
     * @uses self::$calendar
     * @uses self::$parser
     */
    public function testVersion()
    {
        $this->assertSame('1.0', self::$calendar['VERSION'][0]['value'][0][0]);
        $this->assertSame(self::$calendar['VERSION'][0]['value'][0][0], self::$parser->getVersion());
    }

    /**
     * Data provider for {@link self::testEvents()}.
     *
     * @return array
     * @see    self::testEvents()
     */
    public static function eventProvider()
    {
        $parser = File_IMC::parse('vcalendar');
        $parser->fromFile(dirname(__FILE__) . '/../../../sample.vcs');

        $events = $parser->getEvents();

        $event1Obj = $events->current();
        $event1    = $event1Obj->toArray();

        $events->next();
        $event2Obj = $events->current();
        $event2    = $event2Obj->toArray();

        $events->next();
        $event3Obj = $events->current();
        $event3    = $event3Obj->toArray();

        //var_dump(self::$calendar, $events, $event1, $event2, $event3); exit;

        $event1_desc  = "Interested in becoming a volunteer for the Sacramento SPCA? We'd love to have you join our team! Please download a volunteer application from this website, and when you mail it in, indicate that you'd like to attend this orientation.";
        $event1_desc .= "\n\n";
        $event1_desc .= "Contact Dee Dee Drake for more information.";

        $event2_desc = "Blah blah blah! This one doesn't have any linebreaks, so it's not quoted-printable";

        $event3_desc = "Lorem ipsum dolor sit amet!";

        return array(

        // event 1
            array('New Volunteer Orientation', $event1['SUMMARY'][0]['value'][0][0]),
            //array($event1_desc, $event1['DESCRIPTION'][0]['value'][0][0]),
            array($event1Obj->getSummary(), $event1['SUMMARY'][0]['value'][0][0]),

        // event 2
            array('Test Event 2', $event2['SUMMARY'][0]['value'][0][0]),
            array($event2Obj->getSummary(), $event2['SUMMARY'][0]['value'][0][0]),
            array($event2_desc, $event2['DESCRIPTION'][0]['value'][0][0]),
            array($event2Obj->getDescription(), $event2['DESCRIPTION'][0]['value'][0][0]),
            array('"http://www.example.com"', $event2['DESCRIPTION'][0]['param']['ALTREP'][0]),

        // event 3
            array('Test Event 3', $event3['SUMMARY'][0]['value'][0][0]),
            array($event3Obj->getSummary(), $event3['SUMMARY'][0]['value'][0][0]),
            array($event3_desc, $event3['DESCRIPTION'][0]['value'][0][0]),
            array($event3Obj->getDescription(), $event3['DESCRIPTION'][0]['value'][0][0]),
        );
    }

    /**
     * @dataProvider eventProvider
     */
    public function testEvents($assert, $actual)
    {
        $this->assertSame($assert, $actual);
    }

    /**
     * Test looping with valid(), next() and current() on
     * {@link File_IMC_Parse_Vcalendar_Events}.
     *
     * @return void
     * @uses   self::$parser
     */
    public function testValid()
    {
        $events = self::$parser->getEvents();

        $i = 0;
        while ($events->valid()) {
            $event = $events->current();
            //var_dump($event); exit;

            $this->assertInstanceOf('File_IMC_Parse_Vcalendar_Event', $event);

            $events->next();

            ++$i;
        }

        $this->assertEquals(3, $i);
    }
}
