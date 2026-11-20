<?php require "Views/Template/header.php"; ?>

<h1>Productos activos</h1>

<p><a href="<?= BASE_URL ?>/productos/crear">+ Nuevo producto</a></p>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Código</th><th>Producto</th><th>Categoría</th><th>Marca</th>
        <th>Precio</th><th>Stock</th><th>Opciones</th>
    </tr>
    <?php foreach ($data["productos"] as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p["codigo"]) ?></td>
            <td><?= htmlspecialchars($p["nombre"]) ?></td>
            <td><?= htmlspecialchars($p["categoria"]) ?></td>
            <td><?= htmlspecialchars($p["marca"]) ?></td>
            <td><?= number_format($p["precio"], 2) ?></td>
            <td><?= (int) $p["stock"] ?></td>
            <td>
                <a href="<?= BASE_URL ?>/productos/editar/<?= (int) $p["id"] ?>">Editar</a>
                <form method="POST" action="<?= BASE_URL ?>/productos/eliminar/<?= (int) $p["id"] ?>"
                      style="display:inline" onsubmit="return confirm('¿Eliminar este producto?')">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require "Views/Template/footer.php"; ?>
