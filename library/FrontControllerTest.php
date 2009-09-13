<?php
// had to add this because simpletest sends headers by default...
ob_start();

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
        return '/this/is/a/test/path';
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

    public function testShouldProperlyDecodeRealJsonInput()
    {
        $expectedAsJson = '{"canvas":{"name":"my-awesome-numbered-img-123","height":50,"width":50,"background-color":"green","comments":"IT IS YOUR BIRTHDAY, IMAGE."},"images":[{"url":"fixtures/bluebox.png","top":0,"left":0},{"url":"fixtures/redbox.png","top":0,"left":0}]}';
        $expectedArray = array(
            'canvas' => array('name' => 'my-awesome-numbered-img-123',
                              'height' => 50,
                              'width'  => 50,
                              'background-color' => 'green',
                              'comments' => 'IT IS YOUR BIRTHDAY, IMAGE.'),  
            'images' => array(
                            array('url'  => 'fixtures/bluebox.png',
                              'top'  => 0,
                              'left' => 0),
                            array('url'  => 'fixtures/redbox.png',
                              'top'  => 0,
                              'left' => 0)));

        $frontController = new FrontController;
        $decodedRequest = $frontController->decodeRequest($expectedAsJson);
        $this->assertEqual($expectedArray, $decodedRequest);
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
                            array('url'  => 'fixtures/bluebox.png',
                              'top'  => 0,
                              'left' => 0),
                            array('url'  => 'fixtures/redbox.png',
                              'top'  => 0,
                              'left' => 0)));


        $fakeCr = new AbsolutelyCoolStub;
        
        $frontController = new FrontController();
        $frontController->setAbsolutelyCool($fakeCr);
        $frontController->dispatch($expectedArray);
        
        $this->assertEqual($expectedArray, $fakeCr->inputArray);
    }

    public function testDispatchShouldReturnPathToGeneratedSprite()
    {
        $fake = new AbsolutelyCoolStub;
        $dummyArray = array();

        $fc = new FrontController;
        $fc->setAbsolutelyCool($fake);
        $resultPath = $fc->dispatch($dummyArray);
        $this->assertEqual('/this/is/a/test/path', $resultPath);
    }

    public function testResponseShouldBeJsonPathToGeneratedSprite()
    {
        $fc = new FrontController;
        $output = $fc->responseAsJson(array('url' => 
                                        'http://example.com/foo.png'));
        $expectedOutput = '{"url":"http:\/\/example.com\/foo.png"}';
        $this->assertEqual($expectedOutput, $output);
    }

    public function testReallyCaptureAndExamineResponse()
    {
        $fc = new FrontController;
        $someJson = '{"foo":"bar"}';
        ob_start();
        $fc->sendResponse($someJson);
        $responseDocument = ob_get_clean();
        $expected = 'var coolRunnings = {"foo":"bar"}';
        $this->assertEqual($expected, $responseDocument);
    }

    public function testShouldConvertResponseFilesystemLinkToPublicUrl()
    {
        $fc = new FrontController;
        $fc->setWebRoot('/var/www/html/');
        $fc->setRootUrl('http://www.example.com/');

        $typicalResponse = '/var/www/html/public_images/test.png';
        $output = $fc->constructResponse($typicalResponse);
        $expectedOutput = array('url' => 
                            'http://www.example.com/public_images/test.png');
        $this->assertEqual($expectedOutput, $output);
    }

}
