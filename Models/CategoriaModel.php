<?php

class CategoriaModel extends Model
{
    protected $table = "categoria";

    // Solo categorías activas, para llenar el <select>.
    public function listarActivas(): array
    {
        return $this->select("id, nombre")
            ->where(["estado = 1"])
            ->orderBy("nombre")
            ->get();
    }
}
