<?php

// this class is an absolute-coordinates sprite generator.

class AbsolutelyCool
{
    // not really a good idea to base the API on
    // a bad pun: coolRunnings, so why not AbsolutelyCool->runnings()?
    // but here we are. I'll refactor this later.
    public function runnings($bigInputArray)
    {
        $blankCanvas = $this->generateCanvas($bigInputArray['canvas']);
        $sprite = $this->generateSprite($blankCanvas, 
                                        $bigInputArray['images']);
        $commentedSprite = $this->setComments($sprite,
                                    $bigInputArray['canvas']['comments']);
        
// request for unique image ids from steve
// superceded by unique image dirs
//        $imageName = $bigInputArray['canvas']['name'] . uniqid();

//	if($this->saveSpriteAs($imageName, $commentedSprite))
	if($this->saveSpriteAs($bigInputArray['canvas']['name'], $commentedSprite)) {
            $spritePath = $this->fileSavePath . $bigInputArray['canvas']['name'] . '.png';
            $this->spriteSize = $this->getFilesizeInBytes($spritePath);
            $this->spriteHeight = $sprite->getImageHeight();
            $this->spriteWidth  = $sprite->getImageWidth();
            return $spritePath;
        }
    }

    public function generateCanvas($canvasParameters)
    {
        $canvas = new Imagick();
        $canvas->newImage($canvasParameters['width'],
                          $canvasParameters['height'],
                          $canvasParameters['background-color'],
                          $fileFormat = 'png');
        return $canvas;
    }

    protected $totalInputSize;

    public function getInputSize()
    {
        return $this->totalInputSize;
    }

    public function generateSprite(Imagick $canvas, $allImages)
    {
        foreach ($allImages as $imageParameters) {
            $localImage = $this->getLocalCopyOfImage($imageParameters['url'],
                                                $localTempFile = microtime() . '.png');
            $this->totalInputSize += $this->getFilesizeInBytes($localImage);
            $imageToAdd = new Imagick($localImage);
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

    // this doesn't take png optimization into account!
    // spriteSize calc moved temporarily to bootstrap.
    // once we get a plugin-based optimization thing going,
    // we'll be able to loop through plugins, then calculate
    // the spriteSize here in the generator, where it should
    // logically be calculated.
    protected $spriteSize;

    public function getSpriteSize()
    {
        return $this->spriteSize;
    }

    protected $spriteHeight;

    public function getSpriteHeight()
    {
        return $this->spriteHeight;
    }

    protected $spriteWidth;

    public function getSpriteWidth()
    {
        return $this->spriteWidth;
    }

    protected $fileSavePath;

    public function setSavePath($path)
    {
        $this->fileSavePath = $path;
    }
    public function saveSpriteAs($filename, Imagick $sprite)
    {
        $filename = $this->fileSavePath . $filename . '.png';
        return $sprite->writeImage($filename);
    }

    public function getLocalCopyOfImage($url, $localFilename)
    {
        $file = file_get_contents($url);
        $completeLocalPath = $this->fileSavePath . $localFilename;
        $handle = fopen($completeLocalPath, 'w');
        if (fputs($handle, $file)) {
            return $this->fileSavePath . $localFilename;
        }
    }

    public function getFilesizeInBytes($file)
    {
        $fileInfo = new SplFileInfo($file);
        return $fileInfo->getSize();
    }
}
