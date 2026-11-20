<?php require "Views/Template/header.php"; ?>
<?php $p = $data["producto"]; // null al crear, arreglo con datos al editar ?>

<h1><?= htmlspecialchars($data["page_title"]) ?></h1>

<form method="POST" action="<?= $data["action"] ?>">

    <p>Categoría:<br>
    <select name="id_categoria" required>
        <option value="">-- Seleccione --</option>
        <?php foreach ($data["categorias"] as $c): ?>
            <option value="<?= $c["id"] ?>"
                <?= $p && $c["id"] == $p["id_categoria"] ? "selected" : "" ?>>
                <?= htmlspecialchars($c["nombre"]) ?>
            </option>
        <?php endforeach; ?>
    </select></p>

    <p>Marca:<br>
    <select name="id_marca" required>
        <option value="">-- Seleccione --</option>
        <?php foreach ($data["marcas"] as $m): ?>
            <option value="<?= $m["id"] ?>"
                <?= $p && $m["id"] == $p["id_marca"] ? "selected" : "" ?>>
                <?= htmlspecialchars($m["nombre"]) ?>
            </option>
        <?php endforeach; ?>
    </select></p>

    <p>Código:<br>
    <input type="text" name="codigo" maxlength="30" required
           value="<?= htmlspecialchars($p["codigo"] ?? "") ?>"></p>

    <p>Nombre:<br>
    <input type="text" name="nombre" maxlength="120" required
           value="<?= htmlspecialchars($p["nombre"] ?? "") ?>"></p>

    <p>Descripción:<br>
    <input type="text" name="descripcion" maxlength="250"
           value="<?= htmlspecialchars($p["descripcion"] ?? "") ?>"></p>

    <p>Precio:<br>
    <input type="number" name="precio" step="0.01" min="0" required
           value="<?= htmlspecialchars($p["precio"] ?? "") ?>"></p>

    <p>Stock:<br>
    <input type="number" name="stock" min="0" required
           value="<?= htmlspecialchars($p["stock"] ?? "0") ?>"></p>

    <p>Unidad de medida:<br>
    <input type="text" name="unidad_medida" maxlength="30" required
           value="<?= htmlspecialchars($p["unidad_medida"] ?? "UNIDAD") ?>"></p>

    <p>Fecha de registro:<br>
    <input type="date" name="fecha_registro" required
           value="<?= htmlspecialchars($p["fecha_registro"] ?? date("Y-m-d")) ?>"></p>

    <button type="submit">Guardar</button>
    <a href="<?= BASE_URL ?>/productos">Cancelar</a>
</form>

<?php require "Views/Template/footer.php"; ?>
