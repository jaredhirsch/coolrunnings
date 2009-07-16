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
     * @access public
     */
    public $isSmushed;

    public function smush($fileToSmush,
                                $serviceUrl = 'http://smush.it/ws.php?img=')
    {
        try {
            $fullPath = $serviceUrl . $fileToSmush;
            $file = new SplFileObject($fullPath);
            $this->rawResponse = $file->__toString();
            $this->examineResponse();
        } catch (RuntimeException $e) {
            $this->isSmushed = false;
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
            return;
        }

        if ($responseAsArray['error'] == 'No savings') {
            $this->isSmushed = true;
            $this->smushedUrl = 'http://smush.it/' . $responseAsArray['src'];
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
