<?php

class HomeModel extends Model
{
    // Consulta sencilla para comprobar que el ORM funciona (no usa tabla).
    public function estadoConexion(): ?array
    {
        return $this->queryOne("SELECT DATABASE() AS bd, VERSION() AS version, NOW() AS hora");
    }
}
