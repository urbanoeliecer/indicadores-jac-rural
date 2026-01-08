<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/conexion.php";

$accion = $_GET['accion'] ?? $_POST['accion'] ?? '';

switch ($accion) {
    case 'departamentos':
        // Consulta para obtener los departamentos
        $sqlDepartamentos = "SELECT iddepartamento, nombre FROM departamentos";
        $resultadoDepartamentos = mysqli_query($conexion, $sqlDepartamentos);

        if (!$resultadoDepartamentos) {
            die("Error al ejecutar el SQL de departamentos: " . mysqli_error($conexion));
        }

        // Crear un array de resultados
        $departamentos = mysqli_fetch_all($resultadoDepartamentos, MYSQLI_ASSOC);

        // Devolver los resultados en formato JSON
        echo json_encode($departamentos);
        break;

    case 'municipios':
        $idDepartamento = $_GET['iddepartamento'] ?? 0;

        // Consulta para obtener los municipios del departamento
        $sqlMunicipios = "SELECT idmunicipio, nombre FROM municipios WHERE iddepartamento = $idDepartamento";
        $resultadoMunicipios = mysqli_query($conexion, $sqlMunicipios);

        if (!$resultadoMunicipios) {
            die("Error al ejecutar el SQL de municipios: " . mysqli_error($conexion));
        }

        // Crear un array de resultados
        $municipios = mysqli_fetch_all($resultadoMunicipios, MYSQLI_ASSOC);

        // Devolver los resultados en formato JSON
        echo json_encode($municipios);
        break;

    case 'juntas':
        $idMunicipio = $_GET['idmunicipio'] ?? 0;

        // Consulta para obtener las juntas del municipio
        $sqlJuntas = "SELECT idjunta, nombre FROM juntas WHERE idmunicipio = $idMunicipio";
        $resultadoJuntas = mysqli_query($conexion, $sqlJuntas);

        if (!$resultadoJuntas) {
            die("Error al ejecutar el SQL de juntas: " . mysqli_error($conexion));
        }

        // Crear un array de resultados
        $juntas = mysqli_fetch_all($resultadoJuntas, MYSQLI_ASSOC);

        // Devolver los resultados en formato JSON
        echo json_encode($juntas);
        break;

    // Otras acciones si es necesario

    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}
?>