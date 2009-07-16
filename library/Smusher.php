<?php

class Smusher
{
    public function __construct($fileToSmush,
                                $serviceUrl = 'http://smush.it/ws.php?img=')
    {
        try {
            $fullPath = $serviceUrl . $fileToSmush;
            $file = new SplFileObject($fullPath);
            $this->rawResponse = $file->__toString();
        } catch (RuntimeException $e) {
            $this->isSmushed = false;
        }
    }

    protected $rawResponse;

    public function getRawResponse()
    {
        return $this->rawResponse;
    }
}
