<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>coolRunnings Sprite Generator: About</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/base/base-min.css">  
<style>

</style>
</head>
<body>
<div id="doc4" class="yui-t7">
   <div id="hd" role="banner"><h1>coolRunnings is a CSS Sprite generator.</h1>
<p><em>you may have heard about it from the <a href="http://spriteme.org">SpriteMe</a>-related blog entries (<a href="http://www.stevesouders.com/blog/2009/09/14/spriteme/">Steve Souders's Blog</a>, <a href="http://http://ajaxian.com/archives/sprite-me">Ajaxian</a>, <a href="http://google-opensource.blogspot.com/2009/09/spritify-with-spriteme.html">Google Open Source Blog</a>) and/or Steve's talks at <a href="http://stevesouders.com/docs/jquery-20090913.ppt">jQuery conf '09</a> and <a href="http://ajaxexperience.techtarget.com/conference/html/performance.html#SSoudersFast">Ajax Experience</a>.</em></p></div>
   <div id="bd" role="main">
    <div class="yui-gc">
    <div class="yui-u first">
<h2>How it works</h2>
<p>coolRunnings is an absolute-coords generator: you tell it the dimensions of your sprite, the URL of each component image, and the top-left coordinate of each image within the sprite, and it generates the image accordingly. It also optimizes the sprite with pngcrush before returning it.</p>
<p>You can request the sprite image, using 'format=image', or the link to the image and some related facts, using 'format=json'.</p>
    </div>
    <div class="yui-u"><h2>quick facts</h2>
<ul>
<li>written in test-driven PHP</li>
<li>open source (MIT license)</li>
<li>it's alpha now (works but needs work, too). Aiming for beta in late october 09</li>
<li>currently implemented as a web service, accepting input via GET string</li>
<li>the code is at <a href="http://bitbucket.org/jared/coolrunnings/">http://bitbucket.org/jared/coolrunnings/</a></li>
<li>there is a working copy alive at <a href="http://csscoolrunnings.com/service.php">http://csscoolrunnings.com/service.php</a> and another at <a href="http://jaredhirsch.com/coolrunnings/index.php">http://jaredhirsch.com/coolrunnings/index.php</a>; see below for documentation on its use.</li>
<li>coolRunnings does sprite assembly work for <a href="http://spriteme.org/">SpriteMe</a>, which you should check out immediately.
</ul>
</div>
</div>
<div class="yui-g">
<h1>Examples:</h1>
</div>
<div class="yui-gc">
    <div class="yui-u first">
<h3>Image Request
<a href="http://csscoolrunnings.com/service.php?format=image&absolute={%22canvas%22:{%22name%22:%22ghosts-a-plenty%22,%22height%22:75,%22width%22:%20222,%22background-color%22:%22none%22,%22comments%22:%22comments%20inserted%20in%20the%20final%20PNG%22},%22images%22:[{%22url%22:%22http://www.namcogames.com/iphone_games/images/blinky.png%22,%22top%22:10,%22left%22:10},{%22url%22:%22http://www.namcogames.com/iphone_games/images/pinky.png%22,%22top%22:10,%22left%22:63},{%22url%22:%22http://www.namcogames.com/iphone_games/images/inky.png%22,%22top%22:10,%22left%22:116},{%22url%22:%22http://www.namcogames.com/iphone_games/images/sue.png%22,%22top%22:10,%22left%22:169}]}">(Try it)</a></h3>
<pre>http://csscoolrunnings.com/service.php?format=image&absolute=
{"canvas":
    {"name":"ghosts-a-plenty",
     "height":75,
     "width": 222, 
     "background-color":"none",
     "comments":"comments inserted in the final PNG"},
     "images":[
        {"url":"http://www.namcogames.com/iphone_games/images/blinky.png",
        "top":10,
        "left":10},
        {"url":"http://www.namcogames.com/iphone_games/images/pinky.png",
        "top":10,
        "left":63},
        {"url":"http://www.namcogames.com/iphone_games/images/inky.png",
        "top":10,
        "left":116},
        {"url":"http://www.namcogames.com/iphone_games/images/sue.png",
        "top":10,
        "left":169}]}</pre>
        </div>
    <div class="yui-u">
<h3>Image Response</h3>
<img src="ghosts-a-plenty.png" />
        </div>
</div>
<div class="yui-gc">   
    <div class="yui-u first">   
<h3>Json Request: <a href="http://csscoolrunnings.com/service.php?format=json&absolute={%22canvas%22:{%22name%22:%22ghosts-a-plenty%22,%22height%22:75,%22width%22:%20222,%22background-color%22:%22none%22,%22comments%22:%22comments%20inserted%20in%20the%20final%20PNG%22},%22images%22:[{%22url%22:%22http://www.namcogames.com/iphone_games/images/blinky.png%22,%22top%22:10,%22left%22:10},{%22url%22:%22http://www.namcogames.com/iphone_games/images/pinky.png%22,%22top%22:10,%22left%22:63},{%22url%22:%22http://www.namcogames.com/iphone_games/images/inky.png%22,%22top%22:10,%22left%22:116},{%22url%22:%22http://www.namcogames.com/iphone_games/images/sue.png%22,%22top%22:10,%22left%22:169}]}">(Try it)</a></h3>
<pre>http://csscoolrunnings.com/service.php?format=json&absolute=
{"canvas":
    {"name":"ghosts-a-plenty",
     "height":75,
     "width": 222, 
     "background-color":"none",
     "comments":"comments inserted in the final PNG"},
     "images":[
        {"url":"http://www.namcogames.com/iphone_games/images/blinky.png",
        "top":10,
        "left":10},
        {"url":"http://www.namcogames.com/iphone_games/images/pinky.png",
        "top":10,
        "left":63},
        {"url":"http://www.namcogames.com/iphone_games/images/inky.png",
        "top":10,
        "left":116},
        {"url":"http://www.namcogames.com/iphone_games/images/sue.png",
        "top":10,
        "left":169}]}</pre>
        </div>  
    <div class="yui-u">   
<h3>Json Response:</h3>
<pre>var coolRunnings = {
 "url":"http:\/\/www.csscoolrunnings.com
 \/public_images\/c76e73bdbc
 \/ghosts-a-plenty.png",
 "inputSize":1209,
 "outputSize":948,
 "spriteHeight":75,
 "spriteWidth":222}</pre>
        </div>  
</div>  

<div class="yui-g">
<h1>I can has coolRunnings?</h1>
<p>Yeah, it's open source:</p>
<h2>Configuration and dependencies:</h2> 
<p><em>Note: the beta version will not require Imagick or ImageMagick.</em></p>
<ul>
<li>PHP <a href="http://us.php.net/manual/en/ref.json.php">JSON functions</a> (PHP > 5.2.0 or PECL json > 1.2.0)</li>
<li>ImageMagick >= 6.2.4 and PECL Imagick >= 2.0.0 </li>
</ul>

<p><em>To install it on your machine,</em> just <a href="http:bitbucket.org/jared/coolrunnings/">download it</a> from bitbucket and insert your config info into lines 18-19 of the FrontController (coolrunnings/library/FrontController.php). 

<h1>project status</h1>
<p><em>See the <a href="http://bitbucket.org/jared/coolrunnings/issues">issue tracker</a> for planned enhancements.</em></p>

<p>9/14/09: added MIT license text. Little bit more refactoring.</p>

<p>9/13/09: refactored some cruft away. Also added some new information to the json response.

<p>As of 9/5/09, the call to the smush.it image optimizer has been removed. Instead, PNG optimization is carried out on the server. (Thanks, Stoyan) This should improve response time.</p>

<h3>Older updates are <a href="older-updates.html">here</a></h3>

    </div>

    </div>
   <div id="ft" role="contentinfo"><p><a href="http://jaredhirsch.com"><em>brought to you by jared hirsch</em></a></p>
</div>
</div>
</body>
</html>
