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

                    // Call the handler, which should return an HTTP Response
                    $response = Invoker::call($routeInfo->handler, $routeInfo->params, [$request]);

                    if (!$response instanceof Response) {
                        $type = is_object($response) ? get_class($response) : gettype($response);
                        throw new \Exception("Controladores de classe devem retornar objectos Response. Retornado $type");
                    }

                    return $response;

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
            $errorCode = 405;
            return view(
                name: 'error',
                params: ['code' => $errorCode, 'message' => $ex->getMessage()]
            );
        } catch (RouteMethodNotFoundException $ex) {
            $errorCode = 404;
            return view(
                name: 'error',
                params: ['code' => $errorCode, 'message' => $ex->getMessage() ?: 'Internal Server Error']
            )
                ->setStatus($errorCode);
        } catch (\Throwable $ex) {
            $errorCode = 500;
            return view(
                name: 'error',
                params: ['code' => $errorCode, 'message' => $ex->getMessage() ?: 'Internal Server Error']
            )
                ->setStatus($errorCode);
        }
    }
}
