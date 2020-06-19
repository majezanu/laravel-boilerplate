<?php

namespace App\Core\Helpers;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHelper
{
    /**
     * Gte the file extension
     *
     * @param UploadedFile $file
     * @return string
     */
    static function getExtension($file)
    {
        return explode('/', $file->getMimeType())[1];
    }

    static function getFilename($path)
    {
        return basename($path);
    }

    static function getMimeType($file)
    {
        return $file->getMimeType();
    }
}
