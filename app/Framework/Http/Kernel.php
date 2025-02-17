<?php

namespace App\Framework\Http;

use App\Framework\Core\Factory\DispatcherFactory;
use App\Framework\Http\Router;
use FastRoute\Dispatcher;

class Kernel
{
    public function __construct()
    {
    }

    public function handle(Request $request): Response
    {
        // make route dispatcher (basically a callback resolver)
        $routeList = Router::getRoutes();
        $dispatcher = DispatcherFactory::make($routeList);

        // get request route and method
        $method = $request->getMethod();
        $path = $request->getPathInfo();

        // get route info, containing if the route was found, the callback for that route and the parameters passed into it
        $routeInfo = $dispatcher->dispatch(httpMethod: $method, uri: $path);

        // parse route info into separate variables
        $status = $routeInfo[0] ?? Dispatcher::NOT_FOUND;
        $handler = $routeInfo[1] ?? null;
        $routeParams = $routeInfo[2] ?? [];

        // create response using handler
        if ($status === Dispatcher::FOUND && isset($handler)) {
            $content = $this->callHandler($handler, $routeParams, $request);

            return new Response(
                content: $content
            );
        }

        // handle route errors
        if ($status === Dispatcher::METHOD_NOT_ALLOWED) {
            $errorMessage = "Este recurso não suporta o método '$method'";
            $responseStatus = 405;
        } else {
            $errorMessage = "404: Recurso '$path' não foi encontrado";
            $responseStatus = 404;
        }

        $content = view('error', ['error' => $errorMessage]);

        return new Response(
            content: $content,
            status: $responseStatus
        );
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
        return (string) call_user_func_array($handler, $parameters);
    }
}
