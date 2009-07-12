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
    $ac->setSavePath('/var/www/html/coolRunnings/examples/');

    $fc->setAbsolutelyCool($ac);

    // set some other things we'll need
    $fc->setWebRoot('/var/www/html/coolRunnings/examples');
    $fc->setRootUrl('http://localhost/');


    // now: get request and funnel to AC to generate sprite
    // ac returns path where sprite was saved
    $expectedAsJson = '{"canvas":{"name":"my-awesome-numbered-img-123","height":50,"width":50,"background-color":"green","comments":"IT IS YOUR BIRTHDAY, IMAGE."},"images":[{"url":"http://localhost/coolRunnings/examples/bluebox.png","top":0,"left":0},{"url":"http://localhost/coolRunnings/examples/redbox.png","top":0,"left":0}]}';
    $requestAsArray = $fc->decodeRequest($expectedAsJson);
    $localSpritePath = $fc->dispatch($requestAsArray);

    // replace local with web path, stuff into array, 
    // convert into json, and emit!
    $webPathAsArray = $fc->constructResponse($localSpritePath);
    $webPathAsJson = $fc->responseAsJson($webPathAsArray);
    //echo 'foo';
    $fc->sendResponse($webPathAsJson);
