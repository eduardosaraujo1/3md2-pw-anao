<?php

namespace App\Framework\Support;

use App\Framework\Exception\ViewDirectoryNotFoundException;

class Invoker
{
    /**
     * Calls the method performing Dependency Injection. In other words, pass parameters without having to know the order the function demands, just variable name and/or type
     * @param callable $handler
     * @param array<string,mixed> $namedDeps
     * @param array<mixed> $typedDeps
     * @throws \App\Framework\Exception\ViewDirectoryNotFoundException
     */
    public static function call(callable $handler, array $namedDeps = [], array $typedDeps = []): mixed
    {
        // Reflection API: used to read the arguments a function has specified and using that to decide which params should be passed
        // pretty much replicates Laravel's parameter injection structure (laravel's is obviously more polished)

        // isMethod: checks if handler is in format [class_name, method_name] (most is done by the callable prop)
        $reflection = is_array($handler)
            ? new \ReflectionMethod($handler[0], $handler[1])
            : new \ReflectionFunction($handler);

        // Group typedDeps by their type
        $groupByType = self::groupByClass($typedDeps);

        // store parameters to send to handler
        $params = [];
        foreach ($reflection->getParameters() as $param) {
            $name = $param->getName();
            $type = ($param->getType() instanceof \ReflectionNamedType) ? $param->getType()->getName() : null;

            // check if param name matches injected named dep
            if ($name && isset($namedDeps[$name]) && $type === get_debug_type($namedDeps[$name])) {
                $params[] = $namedDeps[$name];
                continue;
            }

            // check if type was injected (and injection was not consumes previously)
            if ($type && isset($groupByType[$type]) && !empty($groupByType[$type])) {
                $params[] = array_shift($groupByType[$type]);
                continue;
            }

            // provide default value
            if ($param->isDefaultValueAvailable()) {
                $params[] = $param->getDefaultValue();
                continue;
            }

            // provide null
            if ($param->allowsNull()) {
                $params[] = null;
                continue;
            }

            // error that a dependency was missing
            throw new ViewDirectoryNotFoundException("Could not resolve '$type' dependency of argument '$name'", 1);
        }

        return call_user_func_array($handler, $params);
    }

    /**
     * Group all types in separate arrays inside an associative array.
     * In other words, put all variables in a "box" and label that "box" with the type of the variables inside it
     * Warning: php native types (string, int, double, array) are REMOVED from the array. Only objects are grouped
     * @param array<mixed> $typedDeps
     * @return array<string,array<mixed>>
     */
    private static function groupByClass(array $typedDeps): array
    {
        $groupedDeps = [];

        foreach ($typedDeps as $dep) {
            if (is_object($dep)) {
                $type = get_class($dep);
                $groupedDeps[$type][] = $dep;
            }
        }

        return $groupedDeps;
    }
}