<?php
ob_start(); // prevent headers being sent.
            // necessary to test this file.

require_once 'simpletest/autorun.php';

class BootstrapTest extends UnitTestCase
{
    public function testCanonicalJsonExampleShouldSucceed()
    {
        if (isset($_GET['format'])) {
            unset($_GET['format']);
        }
        if (isset($_GET['absolute'])) {
            unset($_GET['absolute']);
        }

        $_GET['format'] = 'json';
        $_GET['absolute'] = '
{"canvas":
    {"name":"redbox-bluebox",
    "height":200,
    "width": 200, 
    "background-color":"none",
    "comments":" "},
"images":[{"url":"fixtures/redbox.png",
        "top":10, 
        "left":10},
       {"url":"fixtures/bluebox.png",
        "top":10,
        "left":63}]}';

        ob_start();
        require_once 'Bootstrap.php';
        $coolRunningsOutput = ob_get_clean();

        // we need to parse the output. 
        // initially, it looks like "var coolRunnings = {'url':'http://foo'}".
        // first, remove the 'var coolrunnings ='.
        // then, convert json to associative array.
        // finally, take the URL from the array.
        $jsonOutput = substr($coolRunningsOutput, 19);
        $arrayOutput = json_decode($jsonOutput, true);
        $spriteUrl = $arrayOutput['url'];
        
        // now we compare the generated image with our test image.
        // md5 is simple and expedient. but check that the file 
        // exists and is accessible.
        $testImageUrl = dirname(__FILE__) . '/bootstrap-fixtures/ghosts-a-plenty.png';
        $this->assertEqual(md5_file($spriteUrl), md5_file($testImageUrl));
    }
}
