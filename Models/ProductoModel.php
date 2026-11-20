<?php

class ProductoModel extends Model
{
    protected $table = "producto";

    public function listarConDetalle(): array
    {
        return $this
            ->alias("p")
            ->select("p.id, p.codigo, p.nombre, p.precio, p.stock,
                      c.nombre AS categoria, m.nombre AS marca")
            ->join("categoria c", "c.id = p.id_categoria")
            ->join("marca m", "m.id = p.id_marca")
            ->where(["p.estado = 1"])
            ->get();
    }
}