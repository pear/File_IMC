<?php
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

$root = dirname(__DIR__);

$svnOrNot = '@package_version@';
if ($svnOrNot == '@package_version@') {
    // we run from svn and fiddle with the include_path
    set_include_path(
        get_include_path()
        . PATH_SEPARATOR
        . realpath($root)
    );
}

if (file_exists($root . '/vendor/autoload.php')) {
    require $root . '/vendor/autoload.php';
} else {
    /*
     * Files needed by PhpUnit
     */
    require_once 'PHPUnit/Autoload.php'; /** @phpstan-ignore requireOnce.fileNotFound */
    require_once 'PHPUnit/TextUI/TestRunner.php'; /** @phpstan-ignore requireOnce.fileNotFound */
    require_once 'PHPUnit/Extensions/PhptTestSuite.php'; /** @phpstan-ignore requireOnce.fileNotFound */
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
     */
    public static function main()
    {
    }


    /**
     * Adds all class test suites into the master suite
     *
     * @return PHPUnit\Framework\TestSuite a master test suite
     *                                     containing all class test suites
     * @uses PHPUnit\Framework\TestSuite
     */
    public static function suite()
    {
        $suite = new \PHPUnit\Framework\TestSuite('File_IMC Full Suite of Unit Tests');

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
        $phpt = new \PHPUnit\Framework\TestSuite(File_IMC_DIR_PHPT);
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

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
