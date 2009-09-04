<?php
ob_start(); // prevent headers being sent.
            // necessary to test this file.

require_once 'simpletest/autorun.php';

class BootstrapTest extends UnitTestCase
{
    public function testCanonicalExampleShouldSucceed()
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
    {"name":"ghosts-a-plenty",
    "height":75,
    "width": 222, 
    "background-color":"gray",
    "comments":"normally you would save sprite coordinates in here"},
"images":[{"url":"http://www.namcogames.com/iphone_games/images/blinky.png",
        "top":10, 
        "left":10},
       {"url":"http://www.namcogames.com/iphone_games/images/pinky.png",
        "top":10,
        "left":63}]}';

        require_once 'Bootstrap.php';
    }
}
