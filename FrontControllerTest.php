<?php

require_once 'simpletest/autorun.php';
require_once 'FrontController.php';

class FrontControllerTest extends UnitTestCase
{
    public function testShouldDecodeTrivialJsonInput()
    {
        $_GET['absolute'] = '{"foo":"bar"}';
        $frontController = new FrontController;
        $decodedRequest = $frontController->decodeRequest($_GET['absolute']);
        $this->assertEqual('bar', $decodedRequest['foo']);
    }

    public function testShouldPassDecodedInputToCoolRunnings()
    {
        
    }
}
