<?php

namespace App\Framework\Support;

use App\Framework\Exception\MissingCallableDependencyException;

class Invoker
{
    public static function call(callable $handler, array $namedDeps = [], array $typedDeps = []): mixed
    {
        // Reflection API: used to read the arguments a function has specified and using that to decide which params should be passed
        // pretty much replicates Laravel's parameter injection structure (laravel's is obviously more polished)

        // isMethod: checks if handler is in format [class_name, method_name]
        $isMethod = is_array($handler) && count($handler) === 2;
        $reflection = $isMethod
            ? new \ReflectionMethod($handler[0], $handler[1])
            : new \ReflectionFunction($handler);

        // Group typedDeps by their type
        $groupByType = static::groupByClass($typedDeps);

        // store parameters to send to handler
        $params = [];
        foreach ($reflection->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType()?->getName();

            // check if param name matches injected named dep
            if ($name && isset($namedDeps[$name])) {
                $params[] = $namedDeps[$name];
                continue;
            }

            // check if type was injected (and injection was not consumes previously)
            if ($type && isset($groupByType[$type]) && is_array($groupByType[$type]) && !empty($groupByType[$type])) {
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
            throw new MissingCallableDependencyException("Could not resolve '$type' dependency of argument '$name'", 1);
        }

        return call_user_func_array($handler, $params);
    }

    /**
     * Group all types in separate arrays inside an associative array.
     * In other words, put all variables in a "box" and label that "box" with the type of the variables inside it
     * Warning: php native types (string, int, double, array) are REMOVED from the array. Only objects are grouped
     * @param array $typedDeps
     * @return void
     */
    private static function groupByClass(array $typedDeps): array
    {
        // [User, Request, string, type]
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