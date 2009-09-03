<?php

class PngCrush
{
    public function crush($inputFileName, $outputFileName) 
    {
        $this->checkFileExists($inputFileName);

        // don't want the output, thx
        ob_start();
        passthru("pngcrush -force $inputFileName $outputFileName");
        $pngcrushResults = ob_get_clean();
    }

    private function checkFileExists($file)
    {
        $fileInfo = new SplFileInfo($file);
        if (!$fileInfo->isFile()) {
            throw new RuntimeException("file $file does not exist");
        }
    }
}
