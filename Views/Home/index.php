<?php require "Views/Template/header.php"; ?>

<h1>Tienda - Gestión de productos</h1>

<?php if (!empty($data["estado"])): ?>
    <p>Conectado a <b><?= htmlspecialchars($data["estado"]["bd"]) ?></b>
       (MySQL <?= htmlspecialchars($data["estado"]["version"]) ?>) - <?= htmlspecialchars($data["estado"]["hora"]) ?></p>
<?php endif; ?>

<p><a href="<?= BASE_URL ?>/productos">Ir a Productos</a></p>

<?php require "Views/Template/footer.php"; ?>
