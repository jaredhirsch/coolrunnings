<?php

class PngCrush
{
    public function crush($inputFileName, $outputFileName) 
    {
        $this->checkFileExists($inputFileName);
        $this->checkDirIsWritable($outputFileName);

        // don't want the output, thx
        ob_start();
        // thx to Stoyan
        // brute is too slow passthru("pngcrush -rem alla -brute -reduce $inputFileName $outputFileName");
        passthru("pngcrush -rem alla -reduce $inputFileName $outputFileName");
        $pngcrushResults = ob_get_clean();
    }

    private function checkFileExists($file)
    {
        $fileInfo = new SplFileInfo($file);
        if (!$fileInfo->isFile()) {
            throw new RuntimeException("file $file does not exist");
        }
    }

    private function checkDirIsWritable($file)
    {
        $fileInfo = new SplFileInfo($file);
        $path = $fileInfo->getPath();
        $pathInfo = new SplFileInfo($path);
        if (!$pathInfo->isWritable()) {
            throw new RuntimeException("$path is not writable");
        }
    }
}
