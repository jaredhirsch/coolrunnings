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

        $this->frontController = $this->initializeFrontController();
        $fc = $this->frontController;
        $this->localSpritePath = $fc->processRequestAndGenerateSprite($_GET['absolute']);
        $fc->constructResponseAndEmit($this->localSpritePath);
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
        $fc->setAbsolutelyCool(new AbsolutelyCool);
        $fc->setWebRoot('/var/www/html/');
        $fc->setRootUrl('http://localhost/');
        return $fc;
    }

    private $frontController;

    private $localSpritePath;

}

Bootstrap::startup();
