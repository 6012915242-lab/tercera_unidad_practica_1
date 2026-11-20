<?php

/**
 * ORM base con Query Builder.
 * Cada modelo hijo declara su tabla:   protected $table = "producto";
 * Todas las consultas usan sentencias preparadas de PDO.
 */
class Model
{
    protected PDO $db;
    protected $table = "";
    protected $primaryKey = "id";

    // Estado del Query Builder (se limpia después de cada get/first).
    private string $alias = "";
    private string $columns = "*";
    private array $joins = [];
    private array $wheres = [];
    private array $params = [];
    private string $order = "";
    private string $limit = "";

    public function __construct()
    {
        $this->db = (new Conexion())->connect();
    }

    /* ======================= QUERY BUILDER ======================= */

    // Alias de la tabla principal: ->alias("p")  =>  FROM producto p
    public function alias(string $alias): static
    {
        $this->alias = $this->identifier($alias);
        return $this;
    }

    // Columnas a traer: ->select("p.id, p.nombre, c.nombre AS categoria")
    public function select(string $columns = "*"): static
    {
        $this->columns = $columns;
        return $this;
    }

    // ->join("categoria c", "c.id = p.id_categoria")          INNER JOIN por defecto
    // ->join("marca m", "m.id = p.id_marca", "LEFT")
    public function join(string $table, string $on, string $type = "INNER"): static
    {
        $type = strtoupper($type);
        if (!in_array($type, ["INNER", "LEFT", "RIGHT"], true)) {
            throw new InvalidArgumentException("Tipo de JOIN no válido: " . $type);
        }
        $this->joins[] = "$type JOIN $table ON $on";
        return $this;
    }

    // ->where(["p.estado = 1"])
    // ->where(["p.id = ?", "p.estado = ?"], [$id, 1])   valores siempre como parámetros
    public function where(array|string $conditions, array $params = []): static
    {
        foreach ((array) $conditions as $condition) {
            $this->wheres[] = "(" . $condition . ")";
        }
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    // ->orderBy("p.nombre ASC")
    public function orderBy(string $order): static
    {
        $this->order = " ORDER BY " . $order;
        return $this;
    }

    // ->limit(10)
    public function limit(int $limit, int $offset = 0): static
    {
        $this->limit = " LIMIT " . max(0, $offset) . ", " . max(0, $limit);
        return $this;
    }

    // Ejecuta la consulta construida y devuelve todas las filas.
    public function get(): array
    {
        $stmt = $this->db->prepare($this->buildSelect());
        $stmt->execute($this->params);
        $this->reset();
        return $stmt->fetchAll();
    }

    // Igual que get() pero devuelve solo la primera fila (o null).
    public function first(): ?array
    {
        $this->limit(1);
        $rows = $this->get();
        return $rows[0] ?? null;
    }

    // Muestra el SQL que se generaría (útil para depurar o explicar en la oral).
    public function toSql(): string
    {
        return $this->buildSelect();
    }

    /* ======================= CRUD DEL ORM ======================= */

    // SELECT * FROM tabla
    public function all(): array
    {
        return $this->select()->get();
    }

    // SELECT * FROM tabla WHERE id = ?
    public function find(int|string $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM " . $this->tableName() . " WHERE " . $this->primaryKey . " = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    // INSERT INTO tabla (col1, col2) VALUES (?, ?)   => devuelve el id generado
    public function insert(array $data): int
    {
        if (empty($data)) {
            throw new InvalidArgumentException("insert() necesita al menos un campo.");
        }
        $columns = array_map([$this, "identifier"], array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $sql = "INSERT INTO " . $this->tableName()
             . " (" . implode(", ", $columns) . ") VALUES (" . $placeholders . ")";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        return (int) $this->db->lastInsertId();
    }

    // UPDATE tabla SET col1 = ?, col2 = ? WHERE id = ?   => devuelve filas afectadas
    public function update(int|string $id, array $data): int
    {
        if (empty($data)) {
            throw new InvalidArgumentException("update() necesita al menos un campo.");
        }
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = $this->identifier($column) . " = ?";
        }
        $sql = "UPDATE " . $this->tableName() . " SET " . implode(", ", $sets)
             . " WHERE " . $this->primaryKey . " = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([...array_values($data), $id]);
        return $stmt->rowCount();
    }

    /* ======================= SQL DIRECTO (opcional) ======================= */

    // Para consultas especiales que no encajan en el Query Builder.
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function queryOne(string $sql, array $params = []): ?array
    {
        $rows = $this->query($sql, $params);
        return $rows[0] ?? null;
    }

    /* ======================= INTERNOS ======================= */

    private function buildSelect(): string
    {
        $sql = "SELECT " . $this->columns . " FROM " . $this->tableName();
        if ($this->alias !== "") {
            $sql .= " " . $this->alias;
        }
        if ($this->joins) {
            $sql .= " " . implode(" ", $this->joins);
        }
        if ($this->wheres) {
            $sql .= " WHERE " . implode(" AND ", $this->wheres);
        }
        return $sql . $this->order . $this->limit;
    }

    private function reset(): void
    {
        $this->alias = "";
        $this->columns = "*";
        $this->joins = [];
        $this->wheres = [];
        $this->params = [];
        $this->order = "";
        $this->limit = "";
    }

    private function tableName(): string
    {
        if ($this->table === "") {
            throw new LogicException(static::class . ' no declara protected $table.');
        }
        return $this->identifier($this->table);
    }

    // Solo letras, números y guion bajo en nombres de tablas/columnas (evita inyección).
    private function identifier(string $name): string
    {
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name)) {
            throw new InvalidArgumentException("Identificador no válido: " . $name);
        }
        return $name;
    }
}
