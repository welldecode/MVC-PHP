<?php 

namespace app\library\Router;

use app\library\Router\Exception\MethodNotAllowedException;
use app\library\Router\Exception\NotFoundException;
use Closure;
use LogicException;

class Dispatcher
{

    protected bool $isDispatched = false;

    protected bool $hasMatchingRoutes = false;
    protected ?Route $currentRoute = null;

    public function __construct(
        protected Router $router, 
        protected ?string $url = null, 
        protected ?Method $method = null
    ) {
        $this->url ??= $_SERVER['REQUEST_URI'];
        $this->method ??= Method::find($_SERVER['REQUEST_METHOD']);

        $this->defineCurrentRoute();
    }

    protected function defineCurrentRoute(): void 
    {
        $matchingRoutes = $this->router->find($this->url);
        if(count($matchingRoutes) > 0) {
            $this->hasMatchingRoutes = true;
        }

        foreach($matchingRoutes as $route) {
            if($route->getMethod()->equals($this->method)) {
                $this->currentRoute = $route;
                break;
            }
        }
    }

    public function dispatch(?Closure $handler = null): void
    {
        if($this->isDispatched) {
            throw new LogicException('Already dispatched!');
        }

        $this->isDispatched = true;

        $url = $this->url;

        if(!$this->currentRoute) {
            if($this->hasMatchingRoutes) {
                throw new MethodNotAllowedException(sprintf('Method "%s" not allowed for route "%s"', $this->method->getType(), $url));
            }
            $this->errorCode(404);
        }

        $this->currentRoute->doAction($handler, $url);
    }
    
    public function currentRouteNamedHas(string $name): bool
    {
        return (bool)$this->currentRoute?->nameEqualsWith($name);
    }

    public function getCurrentRoute(): ?Route
    {
        return $this->currentRoute;
    }
    
    public function errorCode($code)
	{
		http_response_code($code);
		$path = dirname(__FILE__, 3);
		$filePath = $path . '/views/errors/' . $code . '.php'; 
		if (file_exists($filePath)) {
			require $filePath;
		}
		exit;
	}
}