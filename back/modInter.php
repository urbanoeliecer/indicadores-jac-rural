<?php
class ModInter {

    public static function conectar() {
        return new mysqli("localhost", "root", "", "bdsara");
    }

    /* ===== DEPARTAMENTOS ===== */
    public static function departamentos() {
        $db = self::conectar();
        $sql = "SELECT iddepartamento AS id, nombre
                FROM departamentos
                ORDER BY nombre";
        return $db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    /* ===== MUNICIPIOS ===== */
    public static function municipiosPorDepartamento($idDep) {
        $db = self::conectar();
        $sql = "SELECT idmunicipio AS id, nombre
                FROM municipios
                WHERE iddepartamento = $idDep
                ORDER BY nombre";
        return $db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    /* ===== JUNTAS ===== */
    public static function juntasPorMunicipio($idMun) {
        $db = self::conectar();
        $sql = "SELECT idjunta AS id, nombre
                FROM juntas
                WHERE idmunicipio = $idMun
                ORDER BY nombre";
        return $db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    /* ===== CONTADOR SEGÚN ROL ===== */
    public static function contarProyectos($rol, $idDep, $idMun, $idJun) {
        $db = self::conectar();
        if ($rol == 1) {
            $sql = "SELECT COUNT(*) total
                    FROM vproyectosxjunta
                    WHERE iddepartamento = $idDep";
        } elseif ($rol == 2) {
            $sql = "SELECT COUNT(*) total
                    FROM vproyectosxjunta
                    WHERE idmunicipio = $idMun";
        } else {
            $sql = "SELECT COUNT(*) total
                    FROM vproyectosxjunta
                    WHERE idjunta = $idJun";
        }
        //print $sql;
        print '<br> Rol: '.$rol.', Dep: '.$idDep.', Mun: '.$idMun.', Junta: '.$idJun;
        return $db->query($sql)->fetch_assoc()['total'];
    }
}


