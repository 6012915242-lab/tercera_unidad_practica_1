<?php

// Carga automática de clases: busca en el núcleo, librerías, controladores y modelos.
spl_autoload_register(function (string $class): void {
    $folders = ["Libraries/Core/", "Libraries/", "Controllers/", "Models/"];
    foreach ($folders as $folder) {
        $file = $folder . $class . ".php";
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});
