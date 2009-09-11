<?php
/* add to mercurial asap. output buffering due to smush.it. */
ob_start();

require_once 'FrontController.php';
require_once 'AbsolutelyCool.php';

class Bootstrap
{
    public static function startup()
    {
        $b = new Bootstrap;
        $b->run();
    }

    public function run()
    {
        $this->initializeSpriteGenerator();
        $this->initializeFrontController();
        $this->processRequestAndGenerateSprite();
        $this->constructResponseAndEmit();
    }

    public function initializeSpriteGenerator()
    {
        $ac = new AbsolutelyCool;
        $savePath = $this->createRandomDirectory();
        $ac->setSavePath($savePath . '/');
        $this->absolutelyCool = $ac;
    }

    public function createRandomDirectory()
    {
        $random = rand();
        $hashed = md5($random);
        $shortened = substr($hashed, 1, 10);
        $savePath = dirname(dirname(__FILE__)) . '/public_images/' . $shortened;
        mkdir($savePath);
        return $savePath;
    }    

    public function initializeFrontController()
    {
        $fc = new FrontController;
        $fc->setAbsolutelyCool($this->absolutelyCool);
        $fc->setWebRoot('/var/www/html/');
        $fc->setRootUrl('http://localhost/');
        $this->frontController = $fc;
    }

    private $frontController;
    private $absolutelyCool;

    public function processRequestAndGenerateSprite()
    {
        $fc = $this->frontController;
        $requestAsArray = $fc->decodeRequest($_GET['absolute']);
        $localSpritePath = $fc->dispatch($requestAsArray);
        $this->localSpritePath = $localSpritePath;
        $this->optimizeSprite();
    }

    private $localSpritePath;

    public function optimizeSprite()
    {
        $localSpritePath = $this->localSpritePath;

        require_once 'PngCrush.php';
        $crusher = new PngCrush;

        try {
            $crusher->crush($localSpritePath, $localSpritePath . ".crushed");
            
            // if crushing succeeds, overwrite original file
            // if not, an exception will be thrown, and these
            //   overwriting commands won't be executed
            
            copy($localSpritePath. ".crushed", $localSpritePath);
            unlink($localSpritePath. ".crushed");
        } catch (Exception $e) {}
    }

    public function constructResponseAndEmit()
    {
        $fc = $this->frontController;
        $ac = $this->absolutelyCool;
        $localSpritePath = $this->localSpritePath;

        // replace local with web path, stuff into array, 
        $webPathAsArray = $fc->constructResponse($localSpritePath);

        // add the total size of component files, in bytes,
        // to the array (spriteme bug #15)
        $webPathAsArray['inputSize'] = $ac->getInputSize();

        // add the size of the output sprite, in bytes,
        // as well (spriteme bug #15 continued)
        $webPathAsArray['outputSize'] = $ac->getSpriteSize();

        // here FC should decide what to do
        // based on the format of the response.
        // This should be pushed into FC.

        if ($_GET['format'] == 'json') {
            $this->emitJsonResponse($webPathAsArray);
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
        public function emitJsonResponse($webPathAsArray)
        {
            $fc = $this->frontController;
            // convert into json, and emit!
            $webPathAsJson = $fc->responseAsJson($webPathAsArray);
            // trash the buffer
            ob_end_clean();
            $fc->sendResponse($webPathAsJson);
        }
}

Bootstrap::startup();
