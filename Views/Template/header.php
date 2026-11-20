<?php
$flash = $_SESSION["flash"] ?? null;
unset($_SESSION["flash"]);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($data["page_title"] ?? "Tienda") ?></title>
</head>
<body>
<p>
    <a href="<?= BASE_URL ?>/home">Inicio</a> |
    <a href="<?= BASE_URL ?>/productos">Productos</a>
</p>
<hr>
<?php if ($flash): ?>
    <p><b><?= htmlspecialchars($flash["texto"]) ?></b></p>
<?php endif; ?>
