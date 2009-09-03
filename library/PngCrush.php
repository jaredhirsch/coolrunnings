<?php

class PngCrush
{
    public function crush($inputFileName, $outputFileName) 
    {
        // don't want the output, thx
        ob_start();
        passthru("pngcrush -force $inputFileName $outputFileName");
        $pngcrushResults = ob_get_clean();
    }
}
