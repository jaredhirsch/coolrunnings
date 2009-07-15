<?php

require_once 'simpletest/autorun.php';

class SmusherTest extends UnitTestCase
{
    public function testShouldCatchExceptionIfUrlIsUnavailable()
    {
        $badUrl = 'http://example.com/asdf/';
        $badFile = 'thisFakeFileDoesNotExist.png';
        $smush_it = new Smusher($serviceUrl = $badUrl, 
                                $imageToSmush = $badFile);
        
    }
}
