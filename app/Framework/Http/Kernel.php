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
        $routeResult = Route::dispatch(method: $method, uri: $uri);

        // handle the statuses
        try {
            switch ($routeResult->status) {
                case Dispatcher::FOUND:
                    if (!is_callable($routeResult->handler)) {
                        throw new \Exception("Ocorreu um erro na implementação da rota");
                    }

                    // Call the handler, which should return an HTTP Response
                    $response = Invoker::call($routeResult->handler, $routeResult->params, [$request]);

                    if (is_string($response)) {
                        return new Response(
                            status: 200,
                            content: $response,
                        );
                    } else if ($response instanceof Response) {
                        return $response;
                    } else {
                        $type = is_object($response) ? get_class($response) : gettype($response);
                        throw new \Exception("Controladores de classe devem retornar objectos Response ou string. Retornado $type");
                    }


                case Dispatcher::METHOD_NOT_ALLOWED:
                    $errorMessage = "Este recurso suporta apenas os métodos '"
                        . implode(', ', $routeResult->allowedMethods) . "'";
                    throw new RouteMethodNotAllowedException($errorMessage);

                case Dispatcher::NOT_FOUND:
                    $errorMessage = "Recurso '$uri' não foi encontrado";
                    throw new RouteMethodNotFoundException($errorMessage);

                default:
                    throw new \Exception("Unhandled route status: {$routeResult->status}");
            }
        } catch (RouteMethodNotAllowedException $ex) {
            return static::createErrorResponse($ex->getMessage(), 405);
        } catch (RouteMethodNotFoundException $ex) {
            return static::createErrorResponse($ex->getMessage(), 404);
        } catch (\Throwable $ex) {
            return static::createErrorResponse($ex->getMessage(), 500);
        }
    }

    private static function createErrorResponse(string $response = 'Internal Server Error', int $code = 500)
    {
        $view = view(
            name: 'error',
            params: ['code' => $code, 'message' => $response]
        );
        return new Response(
            status: $code,
            content: $view,
        );
    }
}
