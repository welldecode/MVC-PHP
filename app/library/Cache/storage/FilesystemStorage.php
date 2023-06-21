<?php 

namespace  app\library\Cache\storage;

use app\library\Cache\storage\contracts\Storage;
use DateTime;

class FilesystemStorage implements Storage
{

    public function __construct(protected string $folderPath)
    {
        $this->folderPath = preg_replace('/\/$/', '', $folderPath);
    }

    protected function makeHash(string $baseKey): string 
    {
        return sha1($baseKey);
    }

    protected function getFilePath(string $hash): string 
    {
        return $this->folderPath . DIRECTORY_SEPARATOR . substr($hash, 0, 3) . DIRECTORY_SEPARATOR . $hash;
    }

    public function has(string $key): bool 
    {
        return file_exists($this->getFilePath($this->makeHash($key)));
    }

    public function get(string $key): mixed 
    {
        if(!$this->has($key)) {
            return null;
        }

        $content = file_get_contents($this->getFilePath($this->makeHash($key)));
        $content = unserialize($content);

        /** @var DateTime|null */
        $expiresIn = $content['expires_in'];
        if($expiresIn && (new DateTime())->diff($expiresIn)->invert) {
            return null;
        }

        return $content['data'];
    }

    public function add(string $key, mixed $value, ?DateTime $expiresIn = null): void 
    {
        $content = [
            'data' => $value,
            'expires_in' => $expiresIn
        ];

        $filePath = $this->getFilePath($this->makeHash($key));
        
        $fileDirectory = pathinfo($filePath, PATHINFO_DIRNAME);
        if(!file_exists($fileDirectory)) {
            mkdir($fileDirectory, recursive: true);
        }

        file_put_contents($filePath, serialize($content));
    }

    public function remove(string $key): void 
    {
        if(!$this->has($key)) {
            return;
        }

        unlink($this->getFilePath($this->makeHash($key)));
    }

}