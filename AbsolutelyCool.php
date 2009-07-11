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

    public function generateSprite(Imagick $canvas, $imageParameters)
    {
        $imageToAdd = new Imagick($imageParameters['url']);
        $canvas->compositeImage($imageToAdd, 
                                imagick::COMPOSITE_OVER,
                                $xOffset = $imageParameters['left'],
                                $yOffset = $imageParameters['top']);
        return $canvas;
    }
}
