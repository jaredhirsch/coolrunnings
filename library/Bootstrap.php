<?php
/* add to mercurial asap. output buffering due to smush.it. */
ob_start();

require_once 'FrontController.php';
require_once 'AbsolutelyCool.php';
require_once 'Smusher.php';
// I am reasonably sure this is how it works:

    // given a front controller
    
    $fc = new FrontController;
    
    // start by inserting an absolutelyCool
    // instance into the front controller
    
    $ac = new AbsolutelyCool;
    // I guess we have to set AbsolutelyCool path separately

// here we introduce the random directory.
$random = rand();
$hashed = md5($random);
$shortened = substr($hashed, 1, 10);
$savePath = dirname(dirname(__FILE__)) . '/public_images/' . $shortened;
mkdir($savePath);

    //$ac->setSavePath(dirname(dirname(__FILE__)) . '/public_images/' . $shortened . '/');
    $ac->setSavePath($savePath . '/');

    $fc->setAbsolutelyCool($ac);

    // set some other things we'll need
    $fc->setWebRoot('/var/www/html/jaredhirsch/');
    $fc->setRootUrl('http://jaredhirsch.com/');


    // now: get request and funnel to AC to generate sprite
    // ac returns path where sprite was saved
    $requestAsArray = $fc->decodeRequest($_GET['absolute']);
    $localSpritePath = $fc->dispatch($requestAsArray);

    // replace local with web path, stuff into array, 
    $webPathAsArray = $fc->constructResponse($localSpritePath);

	// next, send to smush.it
 	$smush = new Smusher;
 	$smush->smush($webPathAsArray['url']);
 	if ($smush->isSmushed()) {
 		$webPathAsArray['url'] = $smush->getSmushedUrl();
 	}

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
