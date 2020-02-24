<?php
declare(strict_types=1);

namespace App\Service;

use ZipArchive;
use Exception;

class ZipExtractorService
{
    /**
     * @param string $zipArchiveName
     * @param string|null $fileToExtract
     * @return mixed|string
     * @throws Exception
     */
    public function unzip(string $zipArchiveName, ?string $fileToExtract = null)
    {
        $zipArchive = new ZipArchive();
        $result = $zipArchive->open($zipArchiveName);

        if ($result === false) {
            throw new Exception('Unable to unzip file "'.$zipArchiveName.'"');
        }

        $zipArchive->extractTo(dirname($zipArchiveName), $fileToExtract);
        $zipArchive->close();

        $result = dirname($zipArchiveName).'/'.$fileToExtract;
        unlink($zipArchiveName);

        return $result;
    }
}
