<?php

namespace Core\Http;

use Core\Exceptions\Routing\RouteMethodNotAllowedException;
use Core\Exceptions\Routing\RouteNotFoundException;
use Core\Routing\Router;
use Core\Session;
use FastRoute\Dispatcher;

class Kernel
{
    public function __construct()
    {
    }

    public function handle(Request $request): Response
    {
        $router = Router::instance();

        $uri = $request->getPathInfo();
        $method = $request->getMethod();

        try {
            [$handler, $params] = $router->dispatch(
                method: $method,
                uri: $uri,
            );

            $response = call_user_func_array($handler, $params);

            if (!($response instanceof Response)) {
                if (is_string($response)) {
                    return new Response(status: 200, content: $response);
                }

                return $this->errorResponse(
                    response: "Invalid controller response type: " . (is_object($response) ? get_class($response) : gettype($response))
                );
            }

            if ($response->status() !== 301) {
                // only unflash if we are not redirecting
                Session::unflash();
            }

            return $response;
        } catch (RouteNotFoundException) {
            return $this->errorResponse(
                code: 404,
                response: "Route '$uri' was not declared in route/web.php",
            );
        } catch (RouteMethodNotAllowedException $err) {
            return $this->errorResponse(
                code: 403,
                response: "Route method only allows '" . $err->getMessage() . "'. Received '$method'",
            );
        } catch (\Throwable $th) {
            return $this->errorResponse(code: 500, response: $th->getMessage() ?? 'Undefined error message.');
        }
    }

    private function errorResponse(string $response = 'Internal Server Error', int $code = 500): Response
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
