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
        $inputFile = $testImageDir . 'purple.png';
        $outputFile = $testImageDir . 'purple-test-output.png';

        // if the file exists, delete it:
        try {
            $fileCheck = new SplFileInfo($outputFile);
            $fileCheck->isFile();
            // if no RuntimeException was thrown, 
            // the file exists and must be deleted.
            unlink($outputFile);
        } catch (RuntimeException $e) {}

        $crusher->crush($inputFile, $outputFile);

        $inputInfo  = new SplFileInfo($inputFile);
        $outputInfo = new SplFileInfo($outputFile);
        $this->assertTrue($outputInfo->isFile());
        $this->assertTrue($inputInfo->getSize() >= $outputInfo->getSize()); 

        // if the whole test ran OK, delete the file:
        try {
            $fileCheck = new SplFileInfo($outputFile);
            $fileCheck->isFile();
            // if no RuntimeException was thrown, 
            // the file exists and must be deleted.
            unlink($outputFile);
        } catch (RuntimeException $e) {}

    }

    public function testMissingInputFileShouldThrowException()
    {
        $crusher = new PngCrush;
        $this->expectException();
        $crusher->crush('/dev/null/foo', '/tmp/foo.png');
    }

    public function testUnwritableOutputFileShouldThrowException()
    {
        $crusher = new PngCrush;
        $testImageDir = dirname(__FILE__) . '/pngcrush-fixtures/';
        $dummyInputFile = $testImageDir . 'purple.png';

        // I don't think anyone would really
        // run this as root, but let's test
        // to avoid trashing anyone's system
        $specialDir = new SplFileInfo('/var/log');
        if ($specialDir->isWritable()) {
            $this->fail('you should not run this test as root!');
        }

        $this->expectException();
        $crusher->crush($dummyInputFile, '/var/log/foo.png');
    }
}
