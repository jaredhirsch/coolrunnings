<?php

require_once 'simpletest/autorun.php';
require_once 'FrontController.php';
require_once 'AbsolutelyCool.php';

class AbsolutelyCoolStub extends AbsolutelyCool
{
    public $inputArray;

    
    /**
     * runnings - the fake version: instead of 
     * running a test, just store the input in
     * a public variable. This allows testing to
     * be done.
     * 
     * @param mixed $inputArray 
     * @access public
     * @return void
     */
    public function runnings($inputArray)
    {
        $this->inputArray = $inputArray;
    }
}

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
        $expectedArray = array(
            'canvas' => array('name' => 'my-awesome-numbered-img-123',
                              'height' => 50,
                              'width'  => 50,
                              'background-color' => 'green',
                              'comments' => 'IT IS YOUR BIRTHDAY, IMAGE.'),  
            'images' => array(
                            array('url'  => 'bluebox.png',
                              'top'  => 0,
                              'left' => 0),
                            array('url'  => 'redbox.png',
                              'top'  => 0,
                              'left' => 0)));


        $fakeCr = new AbsolutelyCoolStub;
        
        $frontController = new FrontController();
        $frontController->setAbsolutelyCool($fakeCr);
        $throwawayResponse = $frontController->dispatch($expectedArray);
        
        $this->assertEqual($expectedArray, $fakeCr->inputArray);
    }
}
