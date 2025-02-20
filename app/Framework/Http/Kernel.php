<?php

namespace App\Framework\Http;

use App\Framework\Facades\Route;
use App\Framework\Support\Invoker;
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
            $content = (string) Invoker::call($routeInfo->handler, $routeInfo->params, [$request]);

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
}
