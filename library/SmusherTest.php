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
        $this->assertFalse($this->smusher->isSmushed());
        
    }

    public function testShouldMarkImageAsNotSmushedIfSmushFails()
    {
        $error = '{"src":"foo","error":"Could not get the image","id":""}';
        $this->smusher->examineResponse($error);

        $this->assertFalse($this->smusher->isSmushed());
    }

    public function testShouldMarkImageAsSmushedAndGiveSmushitImageUrlIfImageCannotBeSmushedFurther()
    {
        // if smush.it cannot further compress an image,
        // it throws an error. but we are still happy with
        // this, because smush.it still hosts a copy of
        // the image in this case. 
        
        $aGoodError = '{"src":"results\/c825409c\/logo.png",' .
                      '"src_size":2722,"error":"No savings",' .
                      '"dest_size":-1,"id":""}';
        $this->smusher->examineResponse($aGoodError);

        $this->assertTrue($this->smusher->isSmushed());
        $this->assertEqual('http://smush.it/results/c825409c/logo.png',
                           $this->smusher->getSmushedUrl());
    }

    public function testIfImageSmushesSuccessfullyThenMarkAsSmushedAndSetSmushitUrl()
    {
        $success = '{"src":"http:\/\/smush.it\/css\/skin\/screenshot.png",' .
                   '"src_size":2334,' .
                   '"dest":"results\/1dab8f0e\/smush\/screenshot.png",' .
                   '"dest_size":2261,"percent":"3.13","id":""}';
        $this->smusher->examineResponse($success);
        
        $this->assertTrue($this->smusher->isSmushed());
        $expectedUrl = 'http://smush.it/results/1dab8f0e/smush/screenshot.png';
        $this->assertEqual($expectedUrl, 
                           $this->smusher->getSmushedUrl());
    }

}
