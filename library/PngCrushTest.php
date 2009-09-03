<?php

require_once 'simpletest/autorun.php';
require_once 'PngCrush.php';

class PngCrushTest extends UnitTestCase
{
    // start with obvious use-case.
    public function testSuccessfulPngCrush()
    {
        $crusher = new PngCrush;
        $testImageDir = dirname(__FILE__) . '/pngcrush-fixtures/';
        $crusher->crush($inputFile = $testImageDir . 'purple.png',
                        $outputFile = $testImageDir . 'purple-test-output.png');

    }
}