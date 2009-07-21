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
            $localImage = $this->getLocalCopyOfImage($imageParameters['url'],
                                                $localTempFile = microtime() . '.png');
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

    public function runnings($bigInputArray)
    {
        $blankCanvas = $this->generateCanvas($bigInputArray['canvas']);
        $sprite = $this->generateSprite($blankCanvas, 
                                        $bigInputArray['images']);
        $commentedSprite = $this->setComments($sprite,
                                    $bigInputArray['canvas']['comments']);
        if($this->saveSpriteAs($bigInputArray['canvas']['name'], $commentedSprite)) {
            return $this->fileSavePath . 
                $bigInputArray['canvas']['name'] . '.png';
        }
    }

    protected $fileSavePath = '';

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

    public function encodeSpaces($url)
    {
        return str_replace(' ', '%20', $url);
    }
}
