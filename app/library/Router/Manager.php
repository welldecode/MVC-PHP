<?php

namespace  app\library\Router;

class Manager 
{

    /** @var Route[] */
    protected array $routes = [];

    /** @var array<string, Route> */
    protected array $routesByName = [];

    public function new(string $uri, Method $method): Route
    {
        return $this->routes[] = new Route($uri, $method, $this);
    }

    /**
     * @param string $url 
     * @return Route[]
     */
    public function findByUrl(string $url): array
    {
        $routes = [];
        foreach($this->routes as $route) {
            if($route->matchWithUrl($url)) {
                $routes[] = $route;
            }
        }
        
        return $routes;
    }    

    public function findByName(string $name): ?Route
    {
        return $this->routesByName[$name] ?? null;
    }

    public function namedWith(string $name, Route $route): void
    {
        $this->routesByName[$name] = $route;    
    }

}