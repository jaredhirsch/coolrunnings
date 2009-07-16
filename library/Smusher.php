<?php

class Smusher
{
    public function smush($fileToSmush,
                                $serviceUrl = 'http://smush.it/ws.php?img=')
    {
        try {
            $fullPath = $serviceUrl . $fileToSmush;
            $file = new SplFileObject($fullPath);
            $this->rawResponse = $file->__toString();
            $this->examineResponse();
        } catch (RuntimeException $e) {
            $this->isSmushed = false;
        }

    }

    protected $rawResponse;

    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    public function examineResponse($response = null)
    {
        $json = ($response === null) ? $this->rawResponse : $response;
        $responseAsArray = json_decode($json, true);
        if ($responseAsArray === null) {
            $this->isSmushed = false;
            return;
        }
    }
}
