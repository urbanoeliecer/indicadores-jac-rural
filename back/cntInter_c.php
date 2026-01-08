<?php
// Mostrar todos los errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1); // Mostrar todos los errores

require_once __DIR__ . "/conexion.php"; // Incluir la conexión a la base de datos

// Recibir los parámetros de fecha y validarlos
$fechaInicio = $_POST['fecha_inicio'] ?? null;
$fechaFin = $_POST['fecha_fin'] ?? null;

if (!$fechaInicio || !$fechaFin) {
    $fechaInicio = '0000-00-00';  // Valor predeterminado
    $fechaFin = '9999-12-31';     // Valor predeterminado
}

$idDepartamento = 1; //$_POST['iddepartamento'] ?? null;
$idMunicipio = $_POST['idmunicipio'] ?? null;
$idJunta = $_POST['idjunta'] ?? null;

// Consulta SQL para obtener los resúmenes de proyectos
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

// Limpiar cualquier salida no deseada antes de enviar la respuesta JSON
ob_clean(); // Limpiar cualquier salida previa

// Establecer el tipo de respuesta a JSON
header('Content-Type: application/json');

// Ejecutar la consulta SQL
$stmt = $pdo->prepare($sqlResumen);
$stmt->execute([$fechaInicio, $fechaFin, $idDepartamento]);
$resumen = $stmt->fetch(PDO::FETCH_ASSOC);

// ================= CONSULTA DETALLE =================
$sqlDetalle = "
    SELECT
        p.nombre AS proyecto,
        p.monto,
        p.beneficiarios,
        p.fechainicio,
        p.fechafinal,
        j.nombre AS junta,
        m.nombre AS municipio,
        d.nombre AS departamento,
        u.nombre AS representante
    FROM proyectos p
    JOIN juntas j ON p.idjunta = j.idjunta
    JOIN municipios m ON j.idmunicipio = m.idmunicipio
    JOIN departamentos d ON m.iddepartamento = d.iddepartamento
    LEFT JOIN representantes r ON r.idjunta = j.idjunta
    LEFT JOIN usuarios u ON u.idusuario = r.idusuario
    WHERE p.fechainicio >= ? AND p.fechafinal <= ?
";

// Agregar los filtros dependiendo del rol
if ($idDepartamento) {
    $sqlDetalle .= " AND d.iddepartamento = ?";
}
if ($idMunicipio) {
    $sqlDetalle .= " AND m.idmunicipio = ?";
}
if ($idJunta) {
    $sqlDetalle .= " AND j.idjunta = ?";
}

$stmtDetalle = $pdo->prepare($sqlDetalle);

// Ejecutar la consulta de detalles con los parámetros adecuados
$paramsDetalle = [$fechaInicio, $fechaFin];
if ($idDepartamento) $paramsDetalle[] = $idDepartamento;
if ($idMunicipio) $paramsDetalle[] = $idMunicipio;
if ($idJunta) $paramsDetalle[] = $idJunta;

$stmtDetalle->execute($paramsDetalle);
$detalle = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

// Verificar si se obtuvo información
if (!$resumen) {
    echo json_encode(["error" => "No se encontraron proyectos para el período seleccionado"]);
    exit;
}

// Devolver los resultados en formato JSON
echo json_encode([
    "resumen" => $resumen,
    "detalle" => $detalle
]);

// Finalizar el buffer de salida y enviar todo al cliente
ob_end_flush();
?>
