<?php

class Smusher
{
    
    /**
     * isSmushed - true if smushing succeeds OR if the image
     *             could not be further smushed--they both
     *             count as victory as far as I'm concerned.
     *             getRawResponse can be used to dig deeper
     *             if the gentle reader deems it necessary.
     * 
     * @var mixed
     * @access protected 
     */
    protected $isSmushed;

    public function isSmushed()
    {
        return $this->isSmushed;
    }

    public function smush($fileToSmush,
                          $serviceUrl = 'http://smushit.com/ws.php?img=')
    {
        // usually we get a response in less than
        // five seconds. maybe we can loop over
        // this and make several attempts if it times out.

        ini_set('default_socket_timeout', 2);
        try {
            $fullPath = $serviceUrl . $fileToSmush;
            $file = new SplFileObject($fullPath);
            $this->rawResponse = $file->__toString();
            $this->examineResponse();
        } catch (RuntimeException $e) {

            //adding nested try/catch as a way of trying smushit twice

	    try {
                $fullPath = $serviceUrl . $fileToSmush;
		$file = new SplFileObject($fullPath);
		$this->rawResponse = $file->__toString();
		$this->examineResponse();
            } catch (RuntimeException $e) {
                $this->isSmushed = false;
            }
        }

    }

    protected $rawResponse;

    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    public function examineResponse($response = null)
    {
        $json = ($response === null) ? $this->rawResponse : $response;
        $responseAsArray = json_decode($json, true);

        if ($responseAsArray === null) {
            $this->isSmushed = false;
        } elseif ($responseAsArray['error'] == 'No savings') {
            $this->isSmushed = true;
            $this->smushedUrl = 'http://smush.it/' . $responseAsArray['src'];
        } elseif (array_key_exists('dest', $responseAsArray)) {
            $this->isSmushed = true;
 // smushit.com returns the full path
 //           $this->smushedUrl = 'http://smush.it/' . $responseAsArray['dest'];
            $this->smushedUrl = $responseAsArray['dest'];
        } else {
            $this->isSmushed = false;
        }
    }

    /**
     * smushedUrl - in the event that smushing succeeds,
     *              a URL on the smush.it server should be
     *              returned with the response.
     * 
     * @var mixed
     * @access protected
     */
    protected $smushedUrl;

    public function getSmushedUrl()
    {
        return $this->smushedUrl;
    }

}
