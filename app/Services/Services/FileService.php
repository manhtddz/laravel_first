<?php
namespace App\Services\Services;

use Storage;
class FileService
{
    private static $instance = null;

    public function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new FileService();
        }
        return self::$instance;
    }
    public function uploadTempFileAndDeleteTempFile($newFile, $oldFileName)
    {
        if ($oldFileName !== null) {
            Storage::disk('public')->delete('temp/' . $oldFileName);
        }
        $filePath = $newFile->store('temp', 'public'); // store in storage/public/temp
        $fileName = str_replace('temp/', '', $filePath);
        return $fileName;
    }

    public function removeFile($fileName)
    {
        if (Storage::disk('public')->exists($fileName)) {
            Storage::disk('public')->delete($fileName);
        }
    }
    
    public function moveTempFileToApp($fileName)
    {
        $tempPath = 'temp/' . $fileName;  // Take path form temp folder
        $newPath = str_replace('temp', 'app', $tempPath);  // Make a new path to app folder

        if (Storage::disk('public')->exists($tempPath)) {
            Storage::disk('public')->move($tempPath, $newPath); // move file
        }
    }
}