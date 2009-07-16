<?php

require_once 'simpletest/autorun.php';
require_once 'Smusher.php';

class SmusherTest extends UnitTestCase
{
    public function setUp()
    {
        error_reporting(0);
        $this->swallowErrors();
    }

    public function testShouldCatchExceptionIfUrlIsUnavailable()
    {
        $badUrl = 'http://example.com/asdf/';
        $badFile = 'thisFakeFileDoesNotExist.png';
        $smush_it = new Smusher($imageToSmush = $badFile, 
                                $serviceUrl = $badUrl);
        $this->assertFalse($smush_it->isSmushed);
        
    }

    public function testShouldCatchExceptionIfSmushitReturnsJsonErrorMessage()
    {
        // enable error reporting while we're writing the test
        // so we know what's breaking
        error_reporting(-1);
        $fakeSmushit = dirname(__FILE__) . '/fixtures/';
        $fakeSmushitContinued = 'smushitFailureResponse.png';
        $smush_it = new Smusher($fakeSmushit, $fakeSmushitContinued);

        $this->assertFalse($smush_it->isSmushed);
        $expected = file_get_contents($fakeSmushit . $fakeSmushitContinued);
        $this->assertEqual($expected, $smush_it->getRawResponse());
    }
}
