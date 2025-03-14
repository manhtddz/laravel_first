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
    public function uploadTempFile($newFile, $oldFileName)
    {
        if ($oldFileName !== null) {
            // dd(Storage::exists($oldFileName));
            Storage::disk('public')->delete('temp/' . $oldFileName);
            // unlink('D:/xampp/htdocs/example-app/public/storage/' . $oldFileName);
        }
        $filePath = $newFile->store('temp', 'public'); // Lưu vào storage/public/temp
        $fileName = str_replace('temp/', '', $filePath);
        return $fileName;
    }
    public function uploadFile($newFile)
    {
        $filePath = $newFile->store('app', 'public'); // Lưu vào storage/public/temp
        $fileName = str_replace('app/', '', $filePath);
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
        $tempPath = 'temp/' . $fileName;  // Đường dẫn file trong storage/app/temp/
        $newPath = str_replace('temp', 'app', $tempPath);  // Đường dẫn file trong storage/app/temp/

        if (Storage::disk('public')->exists($tempPath)) {
            Storage::disk('public')->move($tempPath, $newPath);
            return "File moved successfully to " . $newPath;
        } else {
            return "File not found!";
        }
    }
}