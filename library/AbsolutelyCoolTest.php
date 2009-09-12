<?php

require_once 'simpletest/autorun.php';
require_once 'AbsolutelyCool.php';

class AbsolutelyCoolTest extends UnitTestCase
{
    public function setUp()
    {
        $this->bluebox = new Imagick('fixtures/bluebox.png');
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
        $redCanvas = $ac->generateCanvas($redBox);

        $blueImageParameters = array('url' => 'fixtures/bluebox.png',
                                     'top' => 0,
                                     'left' => 0);

        $sprite = $ac->generateSprite($redCanvas, array($blueImageParameters));
        
        $imageComparison = $sprite->compareImages($this->bluebox,
                                            imagick::METRIC_MEANSQUAREERROR);
        $imageDiffMetric = $imageComparison[1];
        $this->assertTrue($imageDiffMetric == 0);
    }

    public function testShouldPlaceTwoSpritesSideBySide()
    {
        // make a red rectangular canvas, 100 wide and 50 high.
        // overlay two blue squares, 50 x 50, over the rectangle
        // with one having a 50 pixel x-offset. 
        // if the sprite generator is working, the resulting
        // sprite should be identical to a blue rectangle
        // that's 100 wide and 50 high.

        // this test will get us multiple sprites, so probably
        // some kind of loop.

        $redRectangle = array('height' => 50,
                              'width'  => 100,
                              'background-color' => 'red');
        $ac = new AbsolutelyCool;
        $redRectangleCanvas = $ac->generateCanvas($redRectangle);
        
        $blueBox = array('url' => 'fixtures/bluebox.png',
                         'top' => 0,
                         'left' => 0);
        $otherBlueBox = array('url' => 'fixtures/bluebox.png',
                              'top' => 0,
                              'left' => 50);

        $sprite = $ac->generateSprite($redRectangleCanvas,
                                        array($blueBox, $otherBlueBox));

        $blueRectangle = new Imagick();
        $blueRectangle->newImage($width = 100, $height = 50, 
                                $backgroundColor = 'blue', $format = 'png');

        $compared = $blueRectangle->compareImages($sprite, 
                                        imagick::METRIC_MEANSQUAREERROR);
        $imageDiffMetric = $compared[1];

        $this->assertTrue($imageDiffMetric == 0);

    }

    public function testShouldBeAbleToSetAndGetSpriteComments()
    {
        $ac = new AbsolutelyCool;
        $canvas = $ac->generateCanvas(array('height' => 50, 
                                            'width' => 50,
                                            'background-color' => 'black'));
        
        $inputComment = 'IT IS YOUR BIRTHDAY.';
        
        $canvas = $ac->setComments($canvas, $inputComment);
        $returnedComment = $ac->getComments($canvas);
        $this->assertEqual($inputComment, $returnedComment);
    }

    public function testSetCommentsShouldReplaceNotAppend()
    {
        $ac = new AbsolutelyCool;
        $canvas = $ac->generateCanvas(array('height' => 50, 
                                            'width' => 50,
                                            'background-color' => 'black'));
        
        $firstComment = 'IT IS YOUR BIRTHDAY.';
        $canvas = $ac->setComments($canvas, $firstComment);

        $additionalComment = 'IT IS NOT YOUR BIRTHDAY.';
        $canvas = $ac->setComments($canvas, $additionalComment);

        $returnedComment = $ac->getComments($canvas);
        $this->assertEqual($additionalComment, $returnedComment);
    }

    public function testShouldBeAbleToGetAndSetCommentsOnAnExistingFile()
    {
        $ac = new AbsolutelyCool;
        $commented = $ac->setComments(new Imagick('fixtures/bluebox.png'), 
                                'comments on a blue box');
        $retrievedComment = $ac->getComments($commented);

        $this->assertEqual('comments on a blue box', $retrievedComment);
    }

    public function testShouldBeAbleToPassEverythingAtOnce()
    {
        $absolutelyCool = new AbsolutelyCool;
        $completeArray = array(

            'canvas' => array('name' => 'spritemazing_123',
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

                  // todo: refactor API when I'm fresh...
                  // at least the pun is amusing for the moment
        $spriteSavePath = dirname(__FILE__) . '/test_images/';
        $absolutelyCool->setSavePath($spriteSavePath);
        
        // maybe ac should work by side-effects here and return itself...
        $absolutelyCool->runnings($completeArray);

        // so we are now asserting against the sprite
        // which was saved to disk as a side effect

        $sprite = new Imagick($spriteSavePath . 'spritemazing_123.png');
        $this->assertEqual('IT IS YOUR BIRTHDAY, IMAGE.',
                           $absolutelyCool->getComments($sprite));
        
        // same old image comparison deal. 
        // expect the sprite to be a red box.
        $red = new Imagick('fixtures/redbox.png');
        $comparison = $red->compareImages($sprite, 
                                        imagick::METRIC_MEANSQUAREERROR);
        $imageDiffValue = $comparison[1];

        $this->assertTrue($imageDiffValue == 0);


        if (file_exists($spriteSavePath . 'spritemazing_123.png')) {
            unlink($spriteSavePath . 'spritemazing_123.png');
        }
                                            
    }

    public function testShouldWriteGeneratedSpriteToFile()
    {
        $testFilePath = dirname(__FILE__) . '/test_images/';
        $testFile = dirname(__FILE__) . '/test_images/testfile.png';

        if (file_exists($testFile)) {
            unlink($testFile);
        }
        clearstatcache();

        $ac = new AbsolutelyCool;
        $ac->setSavePath($testFilePath);
        try {
            $ac->saveSpriteAs('testfile', new Imagick('fixtures/bluebox.png'));
        } catch (RuntimeException $e) {
            $this->fail();
        }

        $this->assertTrue(file_exists($testFile));

        // but is the saved sprite the same?
        $saved = new Imagick($testFile);
        $original = new Imagick('fixtures/bluebox.png');
        $comparisonArray = $original->compareImages($saved,
                                        imagick::METRIC_MEANSQUAREERROR);
        $imageDifferenceMetric = $comparisonArray[1];
        $this->assertTrue($imageDifferenceMetric == 0);
        if (file_exists($testFile)) {
            unlink($testFile);
        }
    }

    public function testShouldSaveCommentsWhenSpriteIsSaved()
    {
        $testFilePath = dirname(__FILE__) . '/test_images/';
        $testFile = dirname(__FILE__) . '/test_images/testfile.png';

        if (file_exists($testFile)) {
            unlink($testFile);
        }
        clearstatcache();

        $ac = new AbsolutelyCool;
        $commented = $ac->setComments(new Imagick('fixtures/bluebox.png'), 
                                        'some silly comment');
        $ac->setSavePath($testFilePath);
        $ac->saveSpriteAs('testfile', $commented);
        $saved = new Imagick($testFile);
        $this->assertEqual('some silly comment',
                            $ac->getComments($saved));

        if (file_exists($testFile)) {
            unlink($testFile);
        }
    }

    public function testShouldGetFilesize()
    {
        $ac = new AbsolutelyCool;
        $localTestFile = 'fixtures/bluebox.png';
        $localFileSize = 211;
        $this->assertEqual($localFileSize,
                           $ac->getFilesizeInBytes($localTestFile));
        
    }
}
