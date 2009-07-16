<?php

require_once 'simpletest/autorun.php';
require_once 'Smusher.php';

class SmusherTest extends UnitTestCase
{
    public function setUp()
    {
        error_reporting(0);
        $this->swallowErrors();
        $this->smusher = new Smusher;
    }

    public function testShouldCatchExceptionIfUrlIsUnavailable()
    {
        $badUrl = 'http://example.com/asdf/';
        $badFile = 'thisFakeFileDoesNotExist.png';
        $this->smusher->smush($imageToSmush = $badFile, 
                                $serviceUrl = $badUrl);
        $this->assertFalse($this->smusher->isSmushed);
        
    }

    public function testShouldMarkImageAsNotSmushedIfSmushFails()
    {
        $error = '{"src":"foo","error":"Could not get the image","id":""}';
        $this->smusher->examineResponse($error);

        $this->assertFalse($this->smusher->isSmushed);
    }
}
