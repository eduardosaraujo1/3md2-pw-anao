<?php

namespace App\Framework\Http;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Kernel
{
    public function __construct()
    {
    }

    private function makeDispatcher()
    {
        return simpleDispatcher(function (RouteCollector $r) {
            // get route list
            $routes = Router::getRoutes();

            // add each route to the dispatcher
            foreach ($routes as $route) {
                $r->addRoute(
                    httpMethod: $route->httpMethod,
                    route: $route->path,
                    handler: $route->handler
                );
            }
        });
    }

    private function callHandler(mixed $handler, array $params, Request $request)
    {
        // Reflection API: used to read the arguments a function has specified and using that to decide which params should be passed
        // pretty much replicates Laravel's parameter injection structure (laravel's is obviously more polished)

        if (is_array($handler) && count($handler) === 2) {
            // The handler is probably a class method, since it's probably in the format [class_name, method_name]
            $reflection = new \ReflectionMethod($handler[0], $handler[1]);
        } else if (is_callable($handler)) {
            // The handler is callable and is not an array, so it is probably a closure or a function
            $reflection = new \ReflectionFunction($handler);
        } else {
            // The handler is neither, throw an exception
            throw new \Exception("The provided \$handler is neither callable or an array", 1);
        }

        // List of all the parameters the function accepts that we are also providing
        $parameters = [];

        // Look at each parameter (function argument) of the handler
        foreach ($reflection->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType();

            // if we accept a $request, add it to the parameters list
            if (isset($type) && $type instanceof \ReflectionNamedType && $type->getName() === Request::class) {
                $parameters[] = $request;
            }
            // now, check if the current parameter has a corresponding name in the params array
            else if (isset($params[$name ?? ''])) {
                $parameters[] = $params[$name];
            }
            // since we're relying on order for the parameters to be inserted, see if the parameter has a default value
            else if ($param->isDefaultValueAvailable()) {
                $parameters[] = $param->getDefaultValue();
            }
            // if the parameter is nullable, set it to null
            else if ($param->allowsNull()) {
                $parameters[] = null;
            }
            // if none of the above conditions were met, the route fucked up somehow. Throw an error in that case
            else {
                throw new \Exception("The following required \$handler parameter was not provided: $name", 1);
            }
        }

        // call the function with the decided parameters
        $result = (string) call_user_func_array($handler, $parameters);

        // create response object from the result
        return new Response($result);
    }

    private function getErrorPage(string $message, int $status)
    {
        // TODO: implement error page
        return new Response(content: $message, status: $status);
    }

    public function handle(Request $request)
    {
        // make route dispatcher (basically a callback resolver)
        $dispatcher = $this->makeDispatcher();

        // get request route and method
        $method = $request->getMethod();
        $path = $request->getPathInfo();

        // get route info
        $routeInfo = $dispatcher->dispatch($method, $path);

        // parse route info into separate variables
        $status = $routeInfo[0] ?? Dispatcher::NOT_FOUND;
        $handler = $routeInfo[1] ?? null;
        $routeParams = $routeInfo[2] ?? [];

        if ($status === Dispatcher::FOUND && isset($handler)) {
            return $this->callHandler($handler, $routeParams, $request);
        } else if ($status === Dispatcher::METHOD_NOT_ALLOWED) {
            return $this->getErrorPage(message: "Este recurso não suporta o método '$method'.", status: 405);
        } else {
            return $this->getErrorPage(message: "Recurso não encontrado.", status: 404);
        }
    }
}
