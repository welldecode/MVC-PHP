<?php 

namespace app\library\Cache\storage;

use app\library\Cache\storage\contracts\Storage;

final class StorageFactory 
{

    public static function make(string $folderPath): Storage
    {
        return new FilesystemStorage($folderPath);
    }

}