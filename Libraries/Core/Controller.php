<?php

/**
 * Controlador base.
 * Carga automáticamente su modelo en $this->model:
 *   ProductosController  ->  ProductosModel  o  ProductoModel
 */
class Controller
{
    protected ?Model $model = null;

    public function __construct()
    {
        $name = preg_replace('/Controller$/', '', static::class);
        foreach ([$name . "Model", rtrim($name, "s") . "Model"] as $modelClass) {
            if (class_exists($modelClass)) {
                $this->model = new $modelClass();
                break;
            }
        }
    }

    // Renderiza una vista: $this->view("Productos/index", $data)
    protected function view(string $view, array $data = []): void
    {
        $file = "Views/" . $view . ".php";
        if (!is_file($file)) {
            throw new RuntimeException("No existe la vista: " . $file);
        }
        require $file;
    }

    // Redirige dentro del proyecto: $this->redirect("productos")
    protected function redirect(string $path = ""): never
    {
        header("Location: " . BASE_URL . "/" . ltrim($path, "/"));
        exit;
    }

    // Mensaje que se muestra una vez en el header: $this->flash("ok", "Producto registrado")
    protected function flash(string $tipo, string $texto): void
    {
        $_SESSION["flash"] = ["tipo" => $tipo, "texto" => $texto];
    }

    // Exige que la acción llegue por POST (guardar, actualizar, eliminar).
    protected function requirePost(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            exit("Método no permitido");
        }
    }
}
