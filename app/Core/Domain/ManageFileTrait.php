<?php

namespace App\Core\Domain;

use App\Core\Helpers\FileHelper;
use League\Flysystem\Filesystem;

/**
 * Save file trait
 */
trait ManageFileTrait
{
    /**
     * Save the file into the server
     *
     * @param string $path
     * @param null|string $defaultUrl
     * @param null|UploadedFile $file
     * @return string|null
     */
    private function saveFile(string $path, $file = null, string $defaultUrl = null): ?array
    {
        
        $serverPath = null;
        $extension = null;
        // Check if the file exists
        if ($file != null) {
            // Get the file content
            $content = file_get_contents($file);

            // Get extension
            $extension = FileHelper::getExtension($file);

            // Save the image into the server
            $response = app(Filesystem::class)->put("{$path}.{$extension}", $content);
            
            // Check if the image was saved
            if ($response) {
                $serverPath = $response ;
            }
        }

        // If the path is null assign the default url
        if ($serverPath == null) {
            $serverPath = $defaultUrl;
        }
        $result = [
            'serverPath'=>$serverPath,
            'extension'=>$extension
        ];
        // return the file url
        return $result;
    }

    private function deleteFile(string $path)
    {
        $exist = false;
        $exist = app(Filesystem::class)->has($path);
        if(!$exist){
           
        }
        $response = app(Filesystem::class)->delete("{$path}");
        return $response;
    }
}
