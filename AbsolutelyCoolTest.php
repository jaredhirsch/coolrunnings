<?php

require_once 'simpletest/autorun.php';
require_once 'AbsolutelyCool.php';

class AbsolutelyCoolTest extends UnitTestCase
{
    public function setUp()
    {
        $this->bluebox = new Imagick();
        $this->bluebox->newImage($width  = '50', 
                                 $height = '50',
                                 $backgroundColor = 'blue', 
                                 $format = 'png');
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
}
