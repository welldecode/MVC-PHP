<?php 

namespace app\library\Cache\storage\contracts;

use DateTime;

interface Storage 
{

    public function has(string $key): bool;

    public function get(string $key): mixed;

    public function add(string $key, mixed $value, ?DateTime $expiresIn = null): void;

    public function remove(string $key): void;
    
}