<?php

namespace Core\Http;

use Core\Routing\Router;
use FastRoute\Dispatcher;

class Kernel
{
    public function __construct()
    {
    }

    public function handle(Request $request): Response
    {
        $router = new Router();

        $uri = $request->getPathInfo();
        $method = $request->getMethod();

        $result = $router->dispatch(
            method: $method,
            uri: $uri,
        );

        if ($result[0] === Dispatcher::NOT_FOUND) {
            return $this->errorResponse(
                code: 404,
                response: "Route '$uri' was not declared in route/web.php",
            );
        }

        if ($result[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            return $this->errorResponse(
                code: 403,
                response: "Route method only allows'" . implode(', ', $result[1]) . "'. Received '$method'",
            );
        }

        try {
            $handler = $result[1];
            $params = $result[2];

            $response = call_user_func_array($handler, $params);

            if ($response instanceof Response) {
                return $response;
            }

            if (is_string($response)) {
                return new Response(
                    status: 200,
                    content: $response,
                );
            }

            return $this->errorResponse(
                response: "Invalid controller response type: " . is_object($response) ? get_class($response) : gettype($response)
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
