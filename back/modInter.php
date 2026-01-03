<?php
class InterModelo {

    private $cn;

    public function __construct() {
        $this->cn = new mysqli("localhost","root","","bdsara");
        if ($this->cn->connect_error) die("Error BD");
        $this->cn->set_charset("utf8");
    }

    public function getDepartamentos() {
        return $this->cn->query(
            "SELECT iddepartamento, nombre FROM departamentos ORDER BY nombre"
        );
    }

    public function getMunicipios($idDep) {
        $idDep = intval($idDep);
        return $this->cn->query(
            "SELECT idmunicipio, nombre 
             FROM municipios 
             WHERE iddepartamento=$idDep
             ORDER BY nombre"
        );
    }

    public function getJuntas($idMun) {
        $idMun = intval($idMun);
        return $this->cn->query(
            "SELECT idjunta, nombre 
             FROM juntas
             WHERE idmunicipio=$idMun
             ORDER BY nombre"
        );
    }
}

