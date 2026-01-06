<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/conexion.php";  // Asegúrate de que la ruta sea correcta

$rol = $_POST['rol'] ?? $_GET['rol'] ?? 0;
$accion = $_GET['accion'] ?? '';

// ================= COMBOS =================
if ($accion === 'departamentos') {
    $stmt = $pdo->query("SELECT iddepartamento, nombre FROM departamentos ORDER BY nombre");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($accion === 'municipios') {
    $idDepartamento = $_GET['iddepartamento'];
    $stmt = $pdo->prepare("
        SELECT idmunicipio, nombre 
        FROM municipios 
        WHERE iddepartamento = ?
        ORDER BY nombre
    ");
    $stmt->execute([$idDepartamento]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($accion === 'juntas') {
    $idMunicipio = $_GET['idmunicipio'];
    $stmt = $pdo->prepare("
        SELECT idjunta, nombre 
        FROM juntas 
        WHERE idmunicipio = ?
        ORDER BY nombre
    ");
    $stmt->execute([$idMunicipio]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($accion === 'consultar') {

    $fechaInicio = $_POST['fecha_inicio'] ?? null;
    $fechaFin    = $_POST['fecha_fin'] ?? null;

    if (!$fechaInicio || !$fechaFin) {
        echo json_encode([
            "error" => "Debe seleccionar fecha inicial y fecha final"
        ]);
        exit;
    }

    $idDepartamento = $_POST['iddepartamento'] ?? null;
    $idMunicipio    = $_POST['idmunicipio'] ?? null;
    $idJunta        = $_POST['idjunta'] ?? null;

    // Si no se seleccionan fechas, asignamos valores por defecto
    $fechaInicio = $_POST['fecha_inicio'] ?? null;
    $fechaFin = $_POST['fecha_fin'] ?? null;

    $whereFecha = "";

    if ($fechaInicio && $fechaFin) {
        $whereFecha = "AND p.fechainicio >= ? AND p.fechafinal <= ?";
    } else {
        // Si no se seleccionan fechas, buscamos todos los proyectos, incluidos los de fecha final '0000-00-00'
        $whereFecha = "AND (p.fechafinal = '0000-00-00' OR p.fechainicio <= CURDATE())";
    }
    
    // ================= RESUMEN =================
    if ($rol == 1) {
        $sqlResumen = "
            SELECT 
                COUNT(DISTINCT p.idproyecto) AS total_proyectos,
                SUM(p.monto) AS total_monto,
                SUM(p.beneficiarios) AS total_beneficiarios,
                COUNT(DISTINCT m.idmunicipio) AS total_municipios,
                COUNT(DISTINCT j.idjunta) AS total_juntas,
                COUNT(DISTINCT r.idrepresentante) AS total_representantes
            FROM proyectos p
            JOIN juntas j ON p.idjunta = j.idjunta
            JOIN municipios m ON j.idmunicipio = m.idmunicipio
            JOIN departamentos d ON m.iddepartamento = d.iddepartamento
            LEFT JOIN representantes r ON r.idjunta = j.idjunta
            WHERE d.iddepartamento = ?
            $whereFecha
        ";
        $paramsResumen = [$fechaFin, $fechaInicio, $idDepartamento];
    }

    if ($rol == 2) {
        $sqlResumen = "
            SELECT
                m.nombre AS municipio,
                COUNT(DISTINCT p.idproyecto) total_proyectos,
                SUM(p.monto) total_monto,
                SUM(p.beneficiarios) total_beneficiarios,
                COUNT(DISTINCT j.idjunta) total_juntas,
                COUNT(DISTINCT r.idrepresentante) total_representantes
            FROM proyectos p
            JOIN juntas j ON p.idjunta = j.idjunta
            JOIN municipios m ON j.idmunicipio = m.idmunicipio
            LEFT JOIN representantes r ON r.idjunta = j.idjunta
            WHERE m.idmunicipio = ?
            $whereFecha
        ";
        $paramsResumen = [$fechaFin, $fechaInicio, $idMunicipio];
    }

    if ($rol == 3) {
        $sqlResumen = "
            SELECT
                j.nombre AS junta,
                COUNT(DISTINCT p.idproyecto) total_proyectos,
                SUM(p.monto) total_monto,
                SUM(p.beneficiarios) total_beneficiarios,
                COUNT(DISTINCT r.idrepresentante) total_representantes
            FROM proyectos p
            JOIN juntas j ON p.idjunta = j.idjunta
            LEFT JOIN representantes r ON r.idjunta = j.idjunta
            WHERE j.idjunta = ?
            $whereFecha
        ";
        $paramsResumen = [$fechaFin, $fechaInicio, $idJunta];
    }

    if (empty($sqlResumen)) {
        echo json_encode([
            "error" => "No se pudo construir la consulta SQL"
        ]);
        exit;
    }

    $stmt = $pdo->prepare($sqlResumen);
    $stmt->execute($paramsResumen);
    $resumen = $stmt->fetch(PDO::FETCH_ASSOC);

    /* ================= DETALLE ================= */

    $sqlDetalle = "
        SELECT
            p.nombre proyecto,
            p.monto,
            p.beneficiarios,
            p.fechainicio,
            p.fechafinal,
            j.nombre junta,
            m.nombre municipio,
            d.nombre departamento,
            u.nombre representante
        FROM proyectos p
        JOIN juntas j ON p.idjunta = j.idjunta
        JOIN municipios m ON j.idmunicipio = m.idmunicipio
        JOIN departamentos d ON m.iddepartamento = d.iddepartamento
        LEFT JOIN representantes r ON r.idjunta = j.idjunta
        LEFT JOIN usuarios u ON u.idusuario = r.idusuario
        WHERE p.fechainicio <= ?
          AND p.fechafinal >= ?
    ";

    $paramsDetalle = [$fechaFin, $fechaInicio];

    if ($rol == 1) {
        $sqlDetalle .= " AND d.iddepartamento = ?";
        $paramsDetalle[] = $idDepartamento;
    }
    if ($rol == 2) {
        $sqlDetalle .= " AND m.idmunicipio = ?";
        $paramsDetalle[] = $idMunicipio;
    }
    if ($rol == 3) {
        $sqlDetalle .= " AND j.idjunta = ?";
        $paramsDetalle[] = $idJunta;
    }

    $stmt = $pdo->prepare($sqlDetalle);
    $stmt->execute($paramsDetalle);
    $detalle = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "resumen" => $resumen,
        "detalle" => $detalle
    ]);
    exit;
}
