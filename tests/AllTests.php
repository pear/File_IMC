<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Master Unit Test Suite file for File_IMC
 *
 * This top-level test suite file organizes
 * all class test suite files,
 * so that the full suite can be run
 * by PhpUnit or via "pear run-tests -u".
 *
 * PHP version 5
 *
 * @category   File
 * @package    File_IMC
 * @subpackage UnitTesting
 * @author     Chuck Burgess <ashnazg@php.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/File_IMC
 * @since      0.4.0
 */

$svnOrNot = '@package_version@';
if ($svnOrNot == '@package_version@') {
    // we run from svn and fiddle with the include_path
	set_include_path(
		 // bkdotcom switched from prepend to append... was getting "unable to find File/IMC.php" error
		get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__) . '/../')
	);
}

/**
 * Check PHP version... PhpUnit v3+ requires at least PHP v5.1.4
 */
if (version_compare(PHP_VERSION, "5.1.4") < 0) {
    // Cannnot run test suites
    echo 'Cannot run test suite via PhpUnit... requires at least PHP v5.1.4.' . PHP_EOL;
    echo 'Use "pear run-tests -p File_IMC" to run the PHPT tests directly.' . PHP_EOL
;
    exit(1);
}


/**
 * Derive the "main" method name
 * @internal PhpUnit would have to rename PHPUnit_MAIN_METHOD to PHPUNIT_MAIN_METHOD
 *           to make this usage meet the PEAR CS... we cannot rename it here.
 */
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'File_IMC_AllTests::main');
}


/*
 * Files needed by PhpUnit
 */
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Extensions/PhptTestSuite.php';

/*
 * You must add each additional class-level test suite file here
 */
// there are no PhpUnit test files... only PHPTs.. so nothing is listed here

/**
 * directory where PHPT tests are located
 */
define('File_IMC_DIR_PHPT', dirname(__FILE__));

/**
 * File_IMC_ParseTest
 */
require_once 'File/IMC/ParseTest.php';

/**
 * File_IMC_Parse_VcalendarTest
 */
require_once 'File/IMC/Parse/VcalendarTest.php';

/**
 * File_IMC_BuildTest
 */
require_once 'File/IMC/BuildTest.php';

/**
 * File_IMC_BugsTest
 */
require_once 'File/IMC/BugsTest.php';

/**
 * Master Unit Test Suite class for File_IMC
 *
 * This top-level test suite class organizes
 * all class test suite files,
 * so that the full suite can be run
 * by PhpUnit or via "pear run-tests -up File_IMC".
 *
 * @category   File
 * @package    File_IMC
 * @subpackage UnitTesting
 * @author     Chuck Burgess <ashnazg@php.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/File_IMC
 * @since      0.8.0
 */
class File_IMC_AllTests
{

    /**
     * Launches the TextUI test runner
     *
     * @return void
     * @uses PHPUnit_TextUI_TestRunner
     */
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }


    /**
     * Adds all class test suites into the master suite
     *
     * @return PHPUnit_Framework_TestSuite a master test suite
     *                                     containing all class test suites
     * @uses PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite(
            'File_IMC Full Suite of Unit Tests');

        /*
         * You must add each additional class-level test suite name here
         */
        $suite->addTestSuite('File_IMC_ParseTest');
        $suite->addTestSuite('File_IMC_Parse_VcalendarTest');
        $suite->addTestSuite('File_IMC_BuildTest');
        $suite->addTestSuite('File_IMC_BugsTest');

        /*
         * add PHPT tests
         */
        $phpt = new PHPUnit_Extensions_PhptTestSuite(File_IMC_DIR_PHPT);
        $suite->addTestSuite($phpt);

        return $suite;
    }
}

/**
 * Call the main method if this file is executed directly
 * @internal PhpUnit would have to rename PHPUnit_MAIN_METHOD to PHPUNIT_MAIN_METHOD
 *           to make this usage meet the PEAR CS... we cannot rename it here.
 */
if (PHPUnit_MAIN_METHOD == 'File_IMC_AllTests::main') {
    File_IMC_AllTests::main();
}

?>