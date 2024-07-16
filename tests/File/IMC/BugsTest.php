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
 * Tests to verify bugs in File_IMC stay fixed.
 *
 * @category File_Formats
 * @package  File_IMC
 * @author   Till Klampaeckel <till@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/File_IMC
 */
class File_IMC_BugsTest extends \PHPUnit\Framework\TestCase
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
            "This\n\nIs\n\nMy\n\nNotes\n\nTRE\n",
			$description
        );
	}

    /**
     * Weird escaping going on.
     *
     * @return void
     */
    public function test18155()
    {
        $testVcard = '
BEGIN:VCARD
VERSION:3.0
N:Someone;Someone;;;
FN:Someone
ADR;type=WORK;type=pref:;;22221 W\, Unit 3;Somewhere;IL;60002;
END:VCARD
';

        $parser   = File_IMC::parse('vCard');
        $cardinfo = $parser->fromText($testVcard);
        $address  = $cardinfo['VCARD'][0]['ADR'][0]['value'];

        $this->assertEquals($address[File_IMC::VCARD_ADR_STREET][0], '22221 W, Unit 3');
    }

    /**
     * Covers a bug in File_IMC::build() (and related).
     *
     * Fixes an 'undefined index ORG', etc. in
     * {@link File_IMC::addOrganization()}.
     *
     * @return  void
     * @credits Stefan Huber
     * @link    http://pear.php.net/bugs/bug.php?id=18802
     */
    public function test18802()
    {
        $assertion = "BEGIN:VCARD
VERSION:3.0
FN:Stephan Groen
N:Groen;Stephan;;;
PROFILE:VCARD
EMAIL;TYPE=WORK,PREF:stephan@example.org
ORG:The Company!
END:VCARD";

        $vcard = File_IMC::build('vCard');
        $vcard->setFormattedName('Stephan Groen');
        $vcard->setName('Groen', 'Stephan');
        $vcard->addEmail('stephan@example.org');
        $vcard->addParam('TYPE', 'WORK');
        $vcard->addParam('TYPE', 'PREF');
        $vcard->addOrganization('The Company!');
        $text = $vcard->fetch();
        $this->assertEquals($assertion, $text);
    }
}
