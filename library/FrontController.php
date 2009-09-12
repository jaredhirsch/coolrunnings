<?php

class FrontController
{
    public function decodeRequest($inputAsJson)
    {
        $inputAsJson = stripslashes($inputAsJson);
        return json_decode($inputAsJson, true);
    }

    protected $webRootDirectory;
    public function setWebRoot($rootDir)
    {
        $this->webRootDirectory = $rootDir;
    }

    protected $rootUrl;
    public function setRootUrl($rootUrl)
    {
        $this->rootUrl = $rootUrl;
    }

    public function responseAsJson($responseAsArray)
    {
        return json_encode($responseAsArray);
    }

    public function constructResponse($responsePath)
    {
        $filteredResponse = str_replace($this->webRootDirectory,
                                    $this->rootUrl,
                                    $responsePath);
        return array('url' => $filteredResponse);
    }

    public function emitJsonResponse($webPathAsArray, $localSpritePath)
    {
        $ac = $this->absolutelyCool;

        // spriteme bug #15: add input file total size, sprite 
        // size, sprite height, sprite width to json output.
        $webPathAsArray['inputSize'] = $ac->getInputSize();
        $webPathAsArray['outputSize'] = $ac->getFilesizeInBytes($localSpritePath);
        $webPathAsArray['spriteHeight'] = $ac->getSpriteHeight();
        $webPathAsArray['spriteWidth']  = $ac->getSpriteWidth();

        // convert into json, and emit!
        $webPathAsJson = $this->responseAsJson($webPathAsArray);
        // trash the buffer
        ob_end_clean();
        $this->sendResponse($webPathAsJson);
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
        echo 'var coolRunnings = ' . $response;
    }
}
