<?php

class ProductosController extends Controller
{
    // GET /productos  -> listado de productos activos
    public function index(): void
    {
        $data["page_title"] = "Productos";
        $data["productos"]  = $this->model->listarConDetalle();
        $this->view("Productos/index", $data);
    }

    // GET /productos/crear  -> formulario vacío
    public function crear(): void
    {
        $data["page_title"] = "Nuevo producto";
        $data["producto"]   = null;
        $data["categorias"] = (new CategoriaModel())->listarActivas();
        $data["marcas"]     = (new MarcaModel())->listarActivas();
        $data["action"]     = BASE_URL . "/productos/guardar";
        $this->view("Productos/formulario", $data);
    }

    // POST /productos/guardar  -> INSERT con el ORM
    public function guardar(): void
    {
        $this->requirePost();

        $data = $this->datosFormulario();
        $data["estado"] = 1; // todo producto nuevo nace activo

        try {
            $this->model->insert($data);
            $this->flash("ok", "Producto registrado.");
            $this->redirect("productos");
        } catch (PDOException $e) {
            $this->flash("error", "No se pudo registrar: el código ya existe o hay datos inválidos.");
            $this->redirect("productos/crear");
        }
    }

    // GET /productos/editar/{id}  -> formulario con los datos actuales
    public function editar($id): void
    {
        $producto = $this->model->find((int) $id);
        if (!$producto || (int) $producto["estado"] !== 1) {
            $this->flash("error", "El producto no existe.");
            $this->redirect("productos");
        }

        $data["page_title"] = "Editar producto";
        $data["producto"]   = $producto;
        $data["categorias"] = (new CategoriaModel())->listarActivas();
        $data["marcas"]     = (new MarcaModel())->listarActivas();
        $data["action"]     = BASE_URL . "/productos/actualizar/" . (int) $id;
        $this->view("Productos/formulario", $data);
    }

    // POST /productos/actualizar/{id}  -> UPDATE con el ORM
    public function actualizar($id): void
    {
        $this->requirePost();

        $data = $this->datosFormulario(); // sin id ni estado: el id va como parámetro

        try {
            $this->model->update((int) $id, $data);
            $this->flash("ok", "Producto actualizado.");
            $this->redirect("productos");
        } catch (PDOException $e) {
            $this->flash("error", "No se pudo actualizar: el código ya existe o hay datos inválidos.");
            $this->redirect("productos/editar/" . (int) $id);
        }
    }

    // POST /productos/eliminar/{id}  -> eliminación lógica (estado = 0)
    public function eliminar($id): void
    {
        $this->requirePost();

        $this->model->update((int) $id, ["estado" => 0]);
        $this->flash("ok", "Producto eliminado.");
        $this->redirect("productos");
    }

    // Arma el arreglo columna => valor con lo que llegó del formulario.
    private function datosFormulario(): array
    {
        return [
            "id_categoria"   => (int) $_POST["id_categoria"],
            "id_marca"       => (int) $_POST["id_marca"],
            "codigo"         => trim($_POST["codigo"]),
            "nombre"         => trim($_POST["nombre"]),
            "descripcion"    => trim($_POST["descripcion"] ?? ""),
            "precio"         => $_POST["precio"],
            "stock"          => (int) $_POST["stock"],
            "unidad_medida"  => trim($_POST["unidad_medida"]),
            "fecha_registro" => $_POST["fecha_registro"],
        ];
    }
}
