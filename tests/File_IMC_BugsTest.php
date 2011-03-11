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
 * Tests to verify bugs in File_IMC stay fixed.
 *
 * @category File_Formats
 * @package  File_IMC
 * @author   Till Klampaeckel <till@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/File_IMC
 */
class File_IMC_BugsTest extends PHPUnit_Framework_TestCase
{
    public function testBug17656()
    {
        $buf = 'BEGIN:VCALENDAR
METHOD:REQUEST
BEGIN:VEVENT
ATTENDEE;CN="Sky A Stebnicki (email@example.org)";RSVP=TRUE:mailto:email@example.org
CLASS:PUBLIC
CREATED:20100804T193456Z
DESCRIPTION:This\n\nIs\n\nMy\n\nNotes\n\nTRE\n
DTEND;TZID="Pacific Standard Time":20100804T113000
DTSTAMP:20100804T193456Z
DTSTART;TZID="Pacific Standard Time":20100804T110000
LAST-MODIFIED:20100804T193456Z
ORGANIZER;CN="Sky Stebnicki":mailto:sky@teamromito.com
PRIORITY:5
SEQUENCE:1
SUMMARY;LANGUAGE=en-us:Test New Line from OL
TRANSP:OPAQUE
END:VEVENT
END:VCALENDAR';

        $parser   = File_IMC::parse('vCalendar');
        $calendar = $parser->fromText($buf);
        $event    = $calendar['VCALENDAR'][0]['VEVENT'][0];

        $description = $event['DESCRIPTION'][0]['value'][0][0];

        $this->assertEquals(
            $description,
            'This\n\nIs\n\nMy\n\nNotes\n\nTRE\n'
        );
	}
}
