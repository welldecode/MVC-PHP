<?php 

namespace  app\library\Router;

class Router 
{

    protected Manager $manager;

    /** @var array<string, Route> */
    protected array $cachedByName = [];

    public function __construct()
    {
        $this->manager = new Manager;
    }

    public function add(string $uri, Method $method): Route
    {
        return $this->manager->new($uri, $method);
    }

    public function get(string $uri): Route
    {
        return $this->add($uri, Method::GET);
    }

    public function post(string $uri): Route
    {
        return $this->add($uri, Method::POST);
    }

    public function put(string $uri): Route
    {
        return $this->add($uri, Method::PUT);
    }

    public function update(string $uri): Route
    {
        return $this->add($uri, Method::UPDATE);
    }

    public function delete(string $uri): Route
    {
        return $this->add($uri, Method::DELETE);
    }

    /**
     * @param string $url 
     * @return Route[]
     */
    public function find(string $url): array 
    {
        return $this->manager->findByUrl($url);
    }

    public function findByName(string $name): ?Route
    {
        $route = $this->cachedByName[$name] ?? $this->manager->findByName($name);
        return $this->cachedByName[$name] = $route;
    }

}