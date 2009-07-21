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
        $redCanvas = $ac->generateCanvas($redBox);

        $blueImageParameters = array('url' => 'bluebox.png',
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
        
        $blueBox = array('url' => 'bluebox.png',
                         'top' => 0,
                         'left' => 0);
        $otherBlueBox = array('url' => 'bluebox.png',
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
        $commented = $ac->setComments(new Imagick('bluebox.png'), 
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
                            array('url'  => 'bluebox.png',
                              'top'  => 0,
                              'left' => 0),
                            array('url'  => 'redbox.png',
                              'top'  => 0,
                              'left' => 0)));

                  // todo: refactor API when I'm fresh...
                  // at least the pun is amusing for the moment
        $spriteSavePath = dirname(__FILE__) . '/public_images/';
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
        $red = new Imagick('redbox.png');
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
        $testFilePath = dirname(__FILE__) . '/public_images/';
        $testFile = dirname(__FILE__) . '/public_images/testfile.png';

        if (file_exists($testFile)) {
            unlink($testFile);
        }
        clearstatcache();

        $ac = new AbsolutelyCool;
        $ac->setSavePath($testFilePath);
        $this->assertTrue($ac->saveSpriteAs('testfile', 
                                new Imagick('bluebox.png')));

        $this->assertTrue(file_exists($testFile));

        // but is the saved sprite the same?
        $saved = new Imagick($testFile);
        $original = new Imagick('bluebox.png');
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
        $testFilePath = dirname(__FILE__) . '/public_images/';
        $testFile = dirname(__FILE__) . '/public_images/testfile.png';

        if (file_exists($testFile)) {
            unlink($testFile);
        }
        clearstatcache();

        $ac = new AbsolutelyCool;
        $commented = $ac->setComments(new Imagick('bluebox.png'), 
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

    public function testShouldDownloadRemoteImagesCorrectly()
    {
        // this one is tough to test. We need a permanent image.
        // the archive is as close as the web gets to permanent.

        // begin by ensuring the file doesn't exist from earlier attempts.
        if (file_exists(dirname(__FILE__) . '/public_images/' 
                            . 'local_copy.png')) {
            unlink(dirname(__FILE__) . '/public_images/' . 'local_copy.png');
        }

        $ac = new AbsolutelyCool;
        $ac->setSavePath(dirname(__FILE__) . '/public_images/');
        $localCopy = $ac->getLocalCopyOfImage('http://web.archive.org/web/20070610093230/www.libpng.org/pub/png/img_png/pngbook-cover-micro.png', 'local_copy.png');
        $this->assertTrue(file_exists($localCopy));

        // at this point, we assume we did save a file.
        // but let's use Imagick's compareImages to ensure
        // the downloaded image matches a copy we added 
        // earlier manually.
        $localCopyImagick = new Imagick($localCopy);
        $expectedImage = new Imagick(dirname(__FILE__) . '/pngbook-cover-micro.png');
        $comparisonArray =  $expectedImage->compareImages($localCopyImagick,
                                            imagick::METRIC_MEANSQUAREERROR);
        $difference = $comparisonArray[1];
        $this->assertEqual(0, $difference);
    }

    public function testShouldHandleImagesWithSpacesInUrls()
    {
        // although this feels like an input filtering issue,
        // really, it requires such detailed knowledge of the
        // input array's structure that it's too much coupling
        // to put it in the front controller.

        // this all goes down to fopen() choking on URLs with
        // non-encoded spaces in them.

        // once we refactor input object to ArrayObject or
        // similar, this will go in there. enough comments already...

        
        // I am lacking brilliance tonight. so we'll make a public method...

        $ac = new AbsolutelyCool;
        $this->assertEqual('http://example.com/single%20space.png',
                    $ac->encodeSpaces('http://example.com/single space.png'));
    }
}
