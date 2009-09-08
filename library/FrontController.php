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

    public function newConstructResponse($responsePath)
    {
        $filteredResponse = str_replace($this->webRootDirectory,
                                    $this->rootUrl,
                                    $responsePath);
        return array('url' => $filteredResponse);

    // snipped from Bootstrap

        // here FC should decide what to do
        // based on the format of the response.
        // This should be pushed into FC.

        if ($_GET['format'] == 'json') {
            // convert into json, and emit!
            $webPathAsJson = $fc->responseAsJson($webPathAsArray);
            // trash the buffer
            ob_end_clean();
            $fc->sendResponse($webPathAsJson);
        } elseif ($_GET['format'] == 'image') {
            // trash the buffer
            ob_end_clean();
                // make an image out of our URL
            if (!isset($webPathAsArray['url'])) {
                die('error, no url generated. please play again.');
            }
            try {
                    // get local copy of image and save
                    // over the earlier one.
                $localLocation = $ac->getLocalCopyOfImage($webPathAsArray['url'],
                                                          $localSpritePath);
                    // imagick needs local files. hence this whole song and dance.
                $imz = new Imagick($localSpritePath);
            } catch (Exception $e) {
                die('error, image url inaccessible. err msg was ' . 
                    $e->getMessage() .
                    '. thanks, please play again.');
            }

            // at this point, we have a real live image.
            // so display it
            header("Content-Type: image/png");
            echo $imz;
        }
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
