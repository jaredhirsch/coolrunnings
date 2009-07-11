<?php

class FrontController
{
    public function decodeRequest($inputAsJson)
    {
        return json_decode($inputAsJson, true);
    }
}
