<?php

class FrontController
{
    public function decodeRequest($inputAsJson)
    {
        return json_decode($inputAsJson, true);
    }

    public function responseAsJson($responseArray)
    {
        return json_encode($responseArray);
    }

    protected $absolutelyCool;

    public function setAbsolutelyCool($aCoolObject)
    {
        $this->absolutelyCool = $aCoolObject;
        return $this;
    }

    public function dispatch($inputAsArray)
    {
        $pathToGeneratedSprite = $this->absolutelyCool->runnings($inputAsArray);
        return $pathToGeneratedSprite;
    }

    public function sendResponse($response)
    {
        header('Content-type: application/json');
        echo $response;
    }
}
