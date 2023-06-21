<?php 

namespace app\library\Router;

enum Method 
{

    case GET;
    case POST;
    case PUT;
    case UPDATE;
    case DELETE;

    public function getType(): string
    {
        return match($this) {
            self::GET => 'get',
            self::POST => 'post',
            self::PUT => 'put',
            self::UPDATE => 'update',
            self::DELETE => 'delete'
        };
    }

    public function equals(self $method): bool
    {
        return $this->getType() === $method->getType();
    }

    public static function find(string $type): ?static
    {
        $type = strtolower($type);
        
        foreach(self::cases() as $method) {
            if($method->getType() === $type) {
                return $method;
            }
        }

        return null;
    }


}