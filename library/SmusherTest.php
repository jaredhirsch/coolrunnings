<?php

require_once 'simpletest/autorun.php';
require_once 'Smusher.php';

class SmusherTest extends UnitTestCase
{
    public function testShouldCatchExceptionIfUrlIsUnavailable()
    {
        $badUrl = 'http://example.com/asdf/';
        $badFile = 'thisFakeFileDoesNotExist.png';
        $this->expectException();
        $smush_it = new Smusher($imageToSmush = $badFile, 
                                $serviceUrl = $badUrl);
        $this->assertFalse($smush_it->isSmushed);
        
    }
}
