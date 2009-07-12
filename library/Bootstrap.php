<?php
require_once 'FrontController.php';
require_once 'AbsolutelyCool.php';
// I am reasonably sure this is how it works:

    // given a front controller
    
    $fc = new FrontController;
    
    // start by inserting an absolutelyCool
    // instance into the front controller
    
    $ac = new AbsolutelyCool;
    // I guess we have to set AbsolutelyCool path separately
    $ac->setSavePath('/var/www/html/coolRunnings/public_images/');

    $fc->setAbsolutelyCool($ac);

    // set some other things we'll need
    $fc->setWebRoot('/var/www/html/');
    $fc->setRootUrl('http://localhost/');


    // now: get request and funnel to AC to generate sprite
    // ac returns path where sprite was saved
    $requestAsArray = $fc->decodeRequest($_GET['absolute']);
    $localSpritePath = $fc->dispatch($requestAsArray);

    // replace local with web path, stuff into array, 
    // convert into json, and emit!
    $webPathAsArray = $fc->constructResponse($localSpritePath);
    $webPathAsJson = $fc->responseAsJson($webPathAsArray);
    //echo 'foo';
    $fc->sendResponse($webPathAsJson);
