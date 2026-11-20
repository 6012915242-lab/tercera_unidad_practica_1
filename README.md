# Práctica CRUD de Productos — PHP MVC + ORM (base)

Desarrollo de Aplicaciones Web II – UNTRM. Proyecto base: Front Controller + MVC + ORM con Query Builder.

## Instalación
1. Ejecutar en phpMyAdmin el script de la práctica (crea `practica_daw2_producto`).
2. `Config/Config.php`: BASE_URL `http://localhost:8080/practica_I_U_III`, MySQL en el puerto 4404.
3. Abrir `http://localhost:8080/practica_I_U_III/` → debe decir "Conectado a practica_daw2_producto".

## Flujo de una petición
`/productos/editar/5` → `.htaccess` envía todo a `index.php?url=productos/editar/5`
→ `index.php` separa controlador `Productos`, método `editar`, parámetro `5`
→ `Libraries/Core/Load.php` carga `Controllers/ProductosController.php` y ejecuta `editar(5)`
→ el controlador usa `$this->model` (ORM) → `Libraries/Conexion.php` (PDO) → MySQL
→ el controlador llama `$this->view("Productos/formulario", $data)`.

## Estructura
| Capa | Archivo |
| --- | --- |
| Front Controller | `.htaccess`, `index.php`, `Libraries/Core/Load.php` |
| Autoload | `Libraries/Core/Autoload.php` |
| Conexión PDO (una sola) | `Libraries/Conexion.php` |
| ORM base | `Libraries/Core/Model.php` |
| Controlador base | `Libraries/Core/Controller.php` |
| Modelos / Controladores / Vistas | `Models/`, `Controllers/`, `Views/` |

## ORM (`Model`)
Cada modelo declara su tabla: `class ProductoModel extends Model { protected $table = "producto"; }`

| Método | SQL que genera |
| --- | --- |
| `all()` | `SELECT * FROM tabla` |
| `find($id)` | `SELECT * FROM tabla WHERE id = ?` |
| `insert($data)` | `INSERT INTO tabla (cols) VALUES (?, ...)` → devuelve el id |
| `update($id, $data)` | `UPDATE tabla SET col = ? ... WHERE id = ?` → filas afectadas |
| `alias("p")` | `FROM tabla p` |
| `select("p.id, c.nombre AS categoria")` | columnas |
| `join("categoria c", "c.id = p.id_categoria")` | `INNER JOIN` (3er parámetro `"LEFT"` opcional) |
| `where(["p.estado = ?"], [1])` | `WHERE (...) AND (...)` con parámetros |
| `orderBy("p.nombre")`, `limit(10)` | `ORDER BY`, `LIMIT` |
| `get()` / `first()` | ejecuta y devuelve todas las filas / la primera |
| `toSql()` | muestra el SQL construido (depuración) |

Todo usa sentencias preparadas de PDO; los nombres de tabla y columna se validan.

## Controlador base (`Controller`)
- `$this->model`: se carga solo (`ProductosController` → `ProductoModel`).
- `$this->view("Carpeta/vista", $data)`, `$this->redirect("productos")`,
  `$this->flash("ok", "Mensaje")`, `$this->requirePost()`.
