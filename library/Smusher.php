<?php

class Smusher
{
    public function __construct($fileToSmush,
                                $serviceUrl = 'http://smush.it/ws.php?img=')
    {
        try {
            $fullPath = $serviceUrl . $fileToSmush;
            $file = new SplFileObject($fullPath);
        } catch (RuntimeException $e) {
            $this->isSmushed = false;
        }
    }
}
