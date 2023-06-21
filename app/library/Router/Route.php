<?php 

namespace  app\library\Router;

use BadMethodCallException;
use Closure;
use InvalidArgumentException;

class Route 
{

    protected ?string $uriPattern = null;
    protected array|Closure|null $action = null;
    protected ?string $name = null;

    public function __construct(
        protected string $uri, 
        protected Method $method,
        protected Manager $manager
    ) {
        $baseUriPattern = preg_replace(
            '/{(.*)}/U',
            "(?'$1'[^\/]+)",
            $this->sanitizeUriForSafeRegex($uri)
        );
        $this->uriPattern = sprintf('/^%s$/', $baseUriPattern);
    }

    public function getMethod(): Method
    {
        return $this->method;
    }

    public function nameEqualsWith(string $name): bool
    {
        $name = str_replace('*', '\S*', $name);
        return 
            $this->name ? 
            (bool)preg_match(sprintf('/^%s$/', $this->sanitizeForSafeRegex($name)), $this->name) :
            false;
    }

    protected function removeFirstAndLastSlash(string $text): string 
    {
        return preg_replace('/^\/|\/$/', '', $text);
    }

    protected function sanitizeForSafeRegex(string $text): string 
    {
        return str_replace(['/', '.'], ['\/', '\.'], $text);
    }

    protected function preventHomeSlashes(string $url): string 
    {
        $url = $this->removeFirstAndLastSlash($url);
        if(empty($url)) {
            return '/';
        }

        return $url;
    }

    protected function sanitizeUriForSafeRegex(string $url): string 
    {
        return $this->sanitizeForSafeRegex($this->preventHomeSlashes($url));
    }

    public function matchWithUrl(string $url): bool
    {
        return preg_match($this->uriPattern, $this->preventHomeSlashes($url));
    }
    
    public function action(Closure|string|array $action): static
    {
        if(!$action instanceof Closure && !is_array($action)) {
            $result = preg_match('/\S+([#@])\S+/', $action, $matches);
            if(!$result) {
                throw new InvalidArgumentException(sprintf('Action "%s" is invalid!', $action));
            } 
            $action = explode($matches[1], $action, 2);
        }

        $this->action = $action;
        return $this;
    }

    protected function extractUrlParams(string $url): array 
    {
        $result = preg_match($this->uriPattern, $url, $matches);
        if(!$result) {
            throw new InvalidArgumentException(sprintf('The urls "%s" does not match the pattern "%s"', $url, $this->uriPattern));
        }

        return array_filter($matches, fn(int|string $key) => is_string($key), ARRAY_FILTER_USE_KEY);
    }

    public function doAction(?Closure $handler, string $url): void 
    {
        if(!$this->action) {
            throw new BadMethodCallException(sprintf('Route "%s" does not have an action', $this->uri));
        }

        $url = $this->preventHomeSlashes($url);

        $handler ??= function(array $params, array|Closure $action): void {
            if(is_array($action)) {
                $action[0] = new $action[0];
            }
            $result = $action instanceof Closure ? $action($params) : (Closure::fromCallable($action))($params);
            if(!empty($result)) {
                echo $result;
            }
        };

        $handler($this->extractUrlParams($url), $this->action);
    }

    public function name(string $name): static
    {
        $this->manager->namedWith($name, $this);
        
        $this->name = $name;
        
        return $this;
    }

}