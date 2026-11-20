<?php

class MarcaModel extends Model
{
    protected $table = "marca";

    // Solo marcas activas, para llenar el <select>.
    public function listarActivas(): array
    {
        return $this->select("id, nombre")
            ->where(["estado = 1"])
            ->orderBy("nombre")
            ->get();
    }
}
