<?php

namespace App\Framework\Http;

use App\Framework\Core\Factory\DispatcherFactory;
use App\Framework\Facades\Route;
use FastRoute\Dispatcher;

class Kernel
{
    public function __construct()
    {
    }

    public function handle(Request $request): Response
    {
        // get request route and method
        $method = $request->getMethod();
        $uri = $request->getPathInfo();

        // get route info, containing if the route was found, the callback for that route and the parameters passed into it
        $routeInfo = Route::dispatch(method: $method, uri: $uri);

        // create response using handler
        if ($routeInfo->status === Dispatcher::FOUND && is_callable($routeInfo->handler)) {
            // TODO: implement Invoker class
            $content = $this->callHandler($routeInfo->handler, $routeInfo->params, $request);

            return new Response(
                content: $content
            );
        }

        // handle route errors
        if ($routeInfo->status === Dispatcher::METHOD_NOT_ALLOWED) {
            $code = 405;
            $errorMessage = "Este recurso suporta apenas os métodos '" . implode(', ', $routeInfo->allowedMethods) . "'";
        } else {
            $code = 404;
            $errorMessage = "Recurso '$uri' não foi encontrado";
        }

        $content = view('error', ['code' => $code, 'message' => $errorMessage]);

        return new Response(
            content: $content,
            status: $code
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
