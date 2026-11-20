<?php

// Front Controller: resuelve /controlador/metodo/parametro.
$controllerClass = $controller . "Controller";
$controllerFile = "Controllers/" . $controllerClass . ".php";

try {
    if (!is_file($controllerFile)) {
        throw new RuntimeException("No existe el controlador: " . $controllerClass);
    }
    require_once $controllerFile;
    $instance = new $controllerClass();

    if (!method_exists($instance, $method)) {
        throw new RuntimeException("No existe el método: " . $controllerClass . "::" . $method);
    }
    $param !== null ? $instance->$method($param) : $instance->$method();
} catch (PDOException $e) {
    http_response_code(500);
    echo "<h1>Error de conexión a la base de datos</h1><p>" . htmlspecialchars($e->getMessage()) . "</p>";
} catch (RuntimeException $e) {
    http_response_code(404);
    echo "<h1>404</h1><p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
