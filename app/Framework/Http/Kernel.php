<?php

namespace App\Framework\Http;

use App\Framework\Exception\Routing\RouteMethodNotAllowedException;
use App\Framework\Exception\Routing\RouteMethodNotFoundException;
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

        // handle the statuses
        try {
            switch ($routeInfo->status) {
                case Dispatcher::FOUND:
                    if (!is_callable($routeInfo->handler)) {
                        throw new \Exception("Ocorreu um erro na implementação da rota");
                    }
                    // Call the handler and set a success status code
                    $content = (string) Invoker::call($routeInfo->handler, $routeInfo->params, [$request]);
                    $code = 200;
                    break;

                case Dispatcher::METHOD_NOT_ALLOWED:
                    $errorMessage = "Este recurso suporta apenas os métodos '"
                        . implode(', ', $routeInfo->allowedMethods) . "'";
                    throw new RouteMethodNotAllowedException($errorMessage);

                case Dispatcher::NOT_FOUND:
                    $errorMessage = "Recurso '$uri' não foi encontrado";
                    throw new RouteMethodNotFoundException($errorMessage);

                default:
                    throw new \Exception("Unhandled route status: {$routeInfo->status}");
            }
        } catch (RouteMethodNotAllowedException $ex) {
            $code = 405;
            $content = view('error', ['code' => $code, 'message' => $ex->getMessage()]);
        } catch (RouteMethodNotFoundException $ex) {
            $code = 404;
            $content = view('error', ['code' => $code, 'message' => $ex->getMessage()]);
        } catch (\Throwable $ex) {
            $code = 500;
            $content = view('error', ['code' => $code, 'message' => $ex->getMessage() ?: 'Internal Server Error']);
        }

        return new Response(status: $code, content: $content);
    }
}
