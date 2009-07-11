<?php

class AbsolutelyCool
{
    public function generateCanvas($canvasParameters)
    {
        $canvas = new Imagick();
        $canvas->newImage($canvasParameters['width'],
                          $canvasParameters['height'],
                          $canvasParameters['background-color'],
                          $fileFormat = 'png');
        return $canvas;
    }

    public function generateSprite(Imagick $canvas, $allImages)
    {
        foreach ($allImages as $imageParameters) {
            $imageToAdd = new Imagick($imageParameters['url']);
            $canvas->compositeImage($imageToAdd, 
                                    imagick::COMPOSITE_OVER,
                                    $xOffset = $imageParameters['left'],
                                    $yOffset = $imageParameters['top']);
            $imageToAdd->clear();
            $imageToAdd->destroy();
        }

        return $canvas;
    }

    public function setComments(Imagick $canvas, $comments)
    {
        $canvas->commentImage($comments);
        return $canvas;
    }

    public function getComments(Imagick $canvas)
    {
        return $canvas->getImageProperty('comment');
    }

    public function runnings($bigInputArray)
    {
        $blankCanvas = $this->generateCanvas($bigInputArray['canvas']);
        $sprite = $this->generateSprite($blankCanvas, 
                                        $bigInputArray['images']);
        $commentedSprite = $this->setComments($sprite,
                                    $bigInputArray['canvas']['comments']);
        return $commentedSprite;
    }

    /**
     * outputPath: path to save generated sprites
     * 
     * @var mixed
     * @access protected
     */
    protected $outputPath;

    public function setOutputPath($path)
    {
        $this->outputPath = $path;
    }

    public function saveSpriteAs($filename, Imagick $sprite)
    {
        // I don't even know what to do with all these side-effects...
        return $sprite->writeImage($filename);
    }
}
