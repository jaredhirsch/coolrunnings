<?php

class PngCrush
{
    public function crush($inputFileName, $outputFileName) 
    {
        passthru("pngcrush -force $inputFileName $outputFileName");
    }
}
