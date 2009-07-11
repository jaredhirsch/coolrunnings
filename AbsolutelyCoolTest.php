<?php

require_once 'simpletest/autorun.php';
require_once 'AbsolutelyCool.php';

class AbsolutelyCoolTest extends UnitTestCase
{
    public function setUp()
    {
        $this->bluebox = new Imagick('bluebox.png');
    }

    public function tearDown()
    {
        $this->bluebox->clear();
        $this->bluebox->destroy();
    }

    public function testShouldCreateBlankImageGivenOutputParameterArray()
    {
        $output = array('name' => 'myBlankTestImage.png',
                        'height' => '50',
                        'width' => '50',
                        'background-color' => 'blue',
                        'comments' => 'these comments are quite lame');

        $ac = new AbsolutelyCool;
        $outputImage = $ac->generateCanvas($output);

        $imageComparison = $outputImage->compareImages($this->bluebox, 
                                            imagick::METRIC_MEANSQUAREERROR);
        $imageDiffMetric = $imageComparison[1];
        $this->assertTrue($imageDiffMetric == 0);
    }

    public function testShouldCreateCanvasWithCorrectDimensions()
    {
        $output = array('name' => 'myBlankTestImage.png',
                        'height' => '50',
                        'width' => '100',
                        'background-color' => 'blue',
                        'comments' => 'these comments are quite lame');

        $ac = new AbsolutelyCool;
        $outputImage = $ac->generateCanvas($output);

        $this->assertEqual('100', $outputImage->getImageWidth());
        $this->assertEqual('50', $outputImage->getImageHeight());
    }

    public function testShouldPutSpritesOnTopOfBackgroundCanvas()
    {
        // given a background of some size, and a "sprite" 
        // of exactly the same size, if we overlay the sprite
        // on the background, the background should be invisible.
        // so comparing with the original sprite should work.

        // we'll use the blue box as the overlaid thing.
        $redBox = array('height' => '50',
                        'width' => '50',
                        'background-color' => 'red');

        $ac = new AbsolutelyCool;
        $redBackgroundCanvas = $ac->generateCanvas($redBox);
        
    }
}
