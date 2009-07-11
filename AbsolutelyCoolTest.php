<?php

require_once 'simpletest/autorun.php';

class AbsolutelyCoolTest extends UnitTestCase
{
    public function testShouldCreateBlankImageGivenOutputParameterArray()
    {
        $output = array('output' => 
                    array('name' => 'myBlankTestImage.png',
                        'height' => '50',
                        'width' => '50',
                        'backgroundColor' => 'blue',
                        'comments' => 'these comments are quite lame'));

        $ac = new AbsolutelyCool;
        $outputImage = $ac->generateCanvas($output);

        $blueBox = new Imagick('
                                    
    }
}
