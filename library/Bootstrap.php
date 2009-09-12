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
        $fc = new FrontController;
        $fc->run();
    }

    public function initializeFrontController()
    {
        $fc = new FrontController;
        $fc->initialize();
        return $fc;
    }

    private $frontController;

    private $localSpritePath;

}

Bootstrap::startup();
