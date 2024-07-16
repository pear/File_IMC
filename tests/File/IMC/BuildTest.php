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
 * Tests for File_IMC_Build.
 *
 * @category File_Formats
 * @package  File_IMC
 * @author   Till Klampaeckel <till@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/File_IMC
 */
class File_IMC_BuildTest extends \PHPUnit\Framework\TestCase
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
    public function setUp(): void
    {
        $this->vcard = File_IMC::build('vcard');
    }

    /**
     * Data provider for {@link self::testExampleParser()}.
     *
     * each array consists of
     *   PropertyName
     *   Value(s)
     *   Deprecated Method to test  (or null)
     *   Alternate Values (or null)... will test that stored value is same as 1st set of values
     *
     * @return array
     */
    public function exampleProvider()
    {
    	$data = array(
			array('VERSION',	'3.0',										'setVersion'),
			array('FN',			'Mr. John Q. Public, Esq.',					'setFormattedName'),
			array('N',
				array(
					'Stevenson',
					'John',
					array('Philip','Paul'),
					'Dr.',
					array('Jr.','M.D.','A.C.P.')
				),
				'setName',
				array(
					'honorific-prefix'	=> 'Dr.',
					'given-name'		=> 'John',
					'additional-name'	=> array('Philip','Paul'),
					'family-name'		=> 'Stevenson',
					'honorific-suffix'	=> array('Jr.','M.D.','A.C.P.'),
				)
			),
			array('PROFILE',	'vcard'),									// formerly no "setProfile" method
			array('NAME',		'RFC 2426 - vCard MIME Directory Profile',	'setSourceName'),
			array('SOURCE',		'http://tools.ietf.org/html/rfc2426',		'setSource'),
			array('NICKNAME',	'Robbie',									'addNickname'),
			array('PHOTO',		dirname(__FILE__).'/../../test_pattern.gif','setPhoto'),
			array('BDAY',		'1996-04-15',								'setBirthday'),
			array('ADR',
				array(
					'',
					'',
					'123 Main Street',
					'Any Town',
					'CA',
					'91921-1234',
				),
				'addAddress',
				array(
					'street-address'	=> '123 Main Street',
					'locality'			=> 'Any Town',
					'region'			=> 'CA',
					'postal-code'		=> '91921-1234',
				)
			),
			array('LABEL',		"Mr.John Q. Public, Esq.\nMail Drop: TNE QB\n123 Main Street\nAny Town, CA  91921-1234\nU.S.A.", 'addLabel'),
			array('TEL',		'+1-213-555-1234',							'addTelephone'),
			array('EMAIL',		'jqpublic@xyz.dom1.com',					'addEmail'),
			array('MAILER',		'PigeonMail 2.1',							'setMailer'),
			array('TZ',			'-05:00',									'setTZ'),
			array('GEO',
				array(
					'37.386013',
					'-122.082932',
				),
				'setGeo',
				array(
					'latitude'		=> '37.386013',
					'longitude'		=> '-122.082932',
				)
			),
			array('TITLE',		'Director, Research and Development',		'setTitle'),
			array('ROLE',		'Programmer',								'setRole'),
			array('LOGO',		'',											'setLogo'),
			array('AGENT',		'',											'setAgent'),
			array('ORG',
				array(
					'ABC, Inc.',
					'North American Division',
					'Marketing'
				),
				'addOrganization',
				array(
					'organization-name' => 'ABC, Inc.',
					'organization-unit'	=> array('North American Division','Marketing'),
				)
			),
			array('CATEGORIES',	array(
									'INTERNET',
									'IETF',
									'INDUSTRY',
									'INFORMATION TECHNOLOGY',
			),																'addCategories'),
			array('NOTE',		'This fax number is operational 0800 to 1715 EST, Mon-Fri.', 'setNote'),
			array('PRODID',		'-//ONLINE DIRECTORY//NONSGML Version 1//EN',	'setProductID'),
			array('REV',		'1995-10-31T22:27:10Z',						'setRevision'),
			array('SORT-STRING','Public John Q',							'setSortString'),
			array('SOUND',		'',											'setSound'),
			array('UID',		'19950401-080045-40000F192713-0052',		'setUniqueID'),
			array('URL',		'http://pear.php.net/package/File_IMC/',	'setURL'),
			array('CLASS',		'PUBLIC',									'setClass'),
			array('KEY',		'',											'setKey'),
		);
		return $data;
	}

	/**
	 * test that the values are properly stored
	 * this does not test that the values are properly fetched (escaped and encoded)
	 *
	 * @todo... add an expected get() result string to the dataProvider to test the get() method
	 *
	 * @param string $propName The parameter to set
	 * @param string $propValue The value
	 * @return       void
	 *
	 * @dataProvider exampleProvider
	 */
	public function testSettingValues($propName,$passedValue,$depMethod=null,$propValueAlt=null)
	{
		for ( $i=0; $i<2; $i++ )
		{
			$propValue = $passedValue;	// set/reset
			$iter = 0;
			// clear any previously stored value
			if ( isset($this->value[$propName][$iter]) ) {
				unset($this->value[$propName][$iter]);
			}
			if ( $i == 0 ) {
				// test the set($propname) method
				$this->vcard->set($propName,$propValue);
			}
			else {
				// test the deprecated method (if specified)
				if ( empty($depMethod) )
					continue;
				if ( in_array($propName,array('N','ADR','GEO')) ) {
					// multiple parameters
					call_user_func_array(array($this->vcard,$depMethod), $propValue);
				}
				else {
					// single parameter
					call_user_func(array($this->vcard,$depMethod), $propValue);
				}
			}
			// test that value correctly stored
			if ( in_array($propName,array('N','ADR','GEO','ORG')) ) {
				// multiple parts
				foreach ( $propValue as $part => $val ) {
					$valSet = $this->vcard->value[$propName][$iter][$part];
					settype($val, 'array');
					$this->assertSame($valSet, $val);
				}
			}
			else {
				// one part / multiple repetitions
				$valSet = $this->vcard->value[$propName][$iter][0];
				if ( in_array($propName,array('PHOTO','LOGO','SOUND','KEY')) )
				{
					if ( file_exists($propValue) ) {
						$propValue = base64_encode(file_get_contents($propValue));
						$this->assertSame('B', $this->vcard->param[$propName][$iter]['ENCODING'][0]);
					}
					elseif ( preg_match('#^(https?|ftp)://#',$propValue) ) {
						$this->assertSame('URI', $this->vcard->param[$propName][$iter]['VALUE'][0]);
					}
				}
				settype($propValue, 'array');
				$this->assertSame($valSet, $propValue);
			}
		}
		if ( !empty($propValueAlt) ) {
			// test the alternate method creates the same value
			$iter = 0;
			$this->vcard->set($propName,$propValue);
			$valSetA = $this->vcard->value[$propName][$iter];
			$this->vcard->set($propName,$propValueAlt);
			$valSetB = $this->vcard->value[$propName][$iter];
			$this->assertSame($valSetA, $valSetB);
		}
	}

    /**
     * Test Exception
     */
    public function testExceptionIfNoFormatIsProvided()
    {
        $this->expectException('File_IMC_Exception');
        $foo = File_IMC::build('');
    }

    /**
     * Test Exception
     */
    public function testExceptionIfInvalidFormatIsProvided()
    {
        $this->expectException('File_IMC_Exception');
        $foo = File_IMC::build('bar');
    }

    /**
     * Test the fluent interface.
     */
    public function testFluentInterface()
    {
        $this->assertInstanceOf('File_IMC_Build_Vcard', $this->vcard->set('N',array('Doe', 'John')));
        $this->assertInstanceOf('File_IMC_Build_Vcard', $this->vcard->set('SOURCE','Your mom.'));
    }

    /**
     * Test formatted name set and get.
     */
    public function testFormattedName()
    {
        $name = 'Jane Doe';
        $this->vcard->set('FN',$name);
        $this->assertSame("FN:{$name}", $this->vcard->get('FN'));
    }

    /**
     *Test Exception
     */
    public function testVersionException()
    {
        $this->expectException('File_IMC_Exception');
        $this->vcard->setVersion('4.0');
    }

    /**
     * Test version set and get.
     */
    public function testVersion()
    {
        $version = '2.1';
        $this->vcard->set('VERSION',$version);
        $this->assertSame("VERSION:{$version}", $this->vcard->get('VERSION'));
    }
}
