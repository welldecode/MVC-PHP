<?php 

namespace app\library\Cache ;

use app\library\Cache\exception\CacheException;
use app\library\Cache\storage\contracts\Storage;
use app\library\Cache\storage\StorageFactory;
use Closure;
use DateTime;

class Cache 
{

    protected static ?Storage $storage = null;

    public static function init(string $folderPath): void 
    {
        self::$storage = StorageFactory::make($folderPath);
    }

    protected static function storage(): Storage
    {
        return self::$storage ?? throw new CacheException('Cache is not initialized');
    }

    public static function has(string $key): bool 
    {
        return self::storage()->has($key);
    }

    public static function get(string $key, mixed $defaultValue = null, ?DateTime $expiresIn = null): mixed 
    {
        $data = self::storage()->get($key);
        if(!$data && $defaultValue) {
            $data = $defaultValue instanceof Closure ? $defaultValue() : $defaultValue;
            self::add($key, $data, $expiresIn);
        }

        return $data;
    }

    public static function add(string $key, mixed $value, ?DateTime $expiresIn): void 
    {
        self::storage()->add($key, $value, $expiresIn);
    }

    public static function remove(string $key): void 
    {
        self::storage()->remove($key);
    }
    
}