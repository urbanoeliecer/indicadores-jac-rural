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
    // Verificamos las fechas
    $fechaInicio = $_POST['fecha_inicio'] ?? null;
    $fechaFin = $_POST['fecha_fin'] ?? null;
    if (!$fechaInicio || !$fechaFin) {
        $fechaInicio = '0000-00-00';  // Valor predeterminado
        $fechaFin = '9999-12-31';     // Valor predeterminado
    }

    // Construir la consulta SQL
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
        WHERE p.fechainicio >= ? AND p.fechafinal <= ?
        AND d.iddepartamento = ?
    ";

    // Establecer el encabezado a JSON
    header('Content-Type: application/json');

    // Aquí, enviamos la consulta SQL y otros datos al frontend
    echo json_encode([
        "consulta" => $sqlResumen,  // Esto envía la consulta al frontend
    ]);

    // Ejecutar la consulta SQL
    $stmt = $pdo->prepare($sqlResumen);
    $stmt->execute([$fechaInicio, $fechaFin, $idDepartamento]);
    $resumen = $stmt->fetch(PDO::FETCH_ASSOC);
    $detalle = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolvemos los datos al frontend en formato JSON
    echo json_encode([
        "resumen" => $resumen,
        "detalle" => $detalle
    ]);

}