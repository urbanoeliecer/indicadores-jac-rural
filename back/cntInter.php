<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/conexion.php";

$accion = $_GET['accion'] ?? '';

header('Content-Type: application/json');

/* =========================
   COMBOS
========================= */

if ($accion === 'departamentos') {
    $sql = "SELECT iddepartamento, nombre FROM departamentos ORDER BY nombre";
    $stmt = $pdo->query($sql);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($accion === 'municipios') {
    $idDepartamento = $_GET['iddepartamento'] ?? null;

    $sql = "
        SELECT idmunicipio, nombre
        FROM municipios
        WHERE iddepartamento = ?
        ORDER BY nombre
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idDepartamento]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($accion === 'juntas') {
    $idMunicipio = $_GET['idmunicipio'] ?? null;

    $sql = "
        SELECT idjunta, nombre
        FROM juntas
        WHERE idmunicipio = ?
        ORDER BY nombre
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idMunicipio]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

/* =========================
   CONSULTAR (RESUMEN + DETALLE)
========================= */

if ($accion === 'consultar') {

    $fechaInicio = $_POST['fecha_inicio'] ?? '0000-00-00';
    $fechaFin    = $_POST['fecha_fin'] ?? '9999-12-31';

    $idDepartamento = $_POST['iddepartamento'] ?? null;
    $idMunicipio    = $_POST['idmunicipio'] ?? null;
    $idJunta        = $_POST['idjunta'] ?? null;

    /* ---------- RESUMEN ---------- */

    $sqlResumen = "
        SELECT
            COUNT(DISTINCT p.idproyecto) AS total_proyectos,
            SUM(p.monto) AS total_monto,
            SUM(p.beneficiarios) AS total_beneficiarios
        FROM proyectos p
        JOIN juntas j ON p.idjunta = j.idjunta
        JOIN municipios m ON j.idmunicipio = m.idmunicipio
        JOIN departamentos d ON m.iddepartamento = d.iddepartamento
        WHERE p.fechainicio >= ?
          AND (p.fechafinal <= ? OR p.fechafinal = '0000-00-00')
    ";

    $paramsResumen = [$fechaInicio, $fechaFin];

    if ($idDepartamento) {
        $sqlResumen .= " AND d.iddepartamento = ?";
        $paramsResumen[] = $idDepartamento;
    }
    if ($idMunicipio) {
        $sqlResumen .= " AND m.idmunicipio = ?";
        $paramsResumen[] = $idMunicipio;
    }
    if ($idJunta) {
        $sqlResumen .= " AND j.idjunta = ?";
        $paramsResumen[] = $idJunta;
    }

    $stmt = $pdo->prepare($sqlResumen);
    $stmt->execute($paramsResumen);
    $resumen = $stmt->fetch(PDO::FETCH_ASSOC);

    /* ---------- DETALLE ---------- */

    $sqlDetalle = "
        SELECT
            p.nombre AS proyecto,
            p.monto,
            p.beneficiarios,
            j.nombre AS junta,
            m.nombre AS municipio,
            d.nombre AS departamento,
            p.fechainicio,
            p.fechafinal
        FROM proyectos p
        JOIN juntas j ON p.idjunta = j.idjunta
        JOIN municipios m ON j.idmunicipio = m.idmunicipio
        JOIN departamentos d ON m.iddepartamento = d.iddepartamento
        WHERE p.fechainicio >= ?
          AND (p.fechafinal <= ? OR p.fechafinal = '0000-00-00')
    ";

    $paramsDetalle = [$fechaInicio, $fechaFin];

    if ($idDepartamento) {
        $sqlDetalle .= " AND d.iddepartamento = ?";
        $paramsDetalle[] = $idDepartamento;
    }
    if ($idMunicipio) {
        $sqlDetalle .= " AND m.idmunicipio = ?";
        $paramsDetalle[] = $idMunicipio;
    }
    if ($idJunta) {
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

/* =========================
   ACCIÓN NO VÁLIDA
========================= */

echo json_encode([
    "error" => "Acción no válida"
]);
