<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Informe mensual de actividades</title>
<link rel="stylesheet" href="../back/estilos.css">
</head>
<body>
    <a href="../principal.php">Principal</a></li>
<h2>Informe mensual de actividades</h2>
<form method="post">
    <label>Fecha inicio:</label>
    <input type="date" name="fecha_inicio">
    <label>Fecha fin:</label>
    <input type="date" name="fecha_fin">
    <label>Departamento:</label>
    <select name="iddepartamento">
        <option value="">Todos</option>
        <!-- se puede cargar dinámico después -->
    </select>
    <button type="submit">Consultar</button>
</form>
<br>
<?php
require_once "../back/conexion.php";
require_once "../back/ModActiv.php";

$model = new ActividadModel($pdo);

/* ===== RECIBIR FILTROS ===== */
$fechaInicio = $_POST['fecha_inicio'] ?? '0000-00-00';
$fechaFin    = $_POST['fecha_fin'] ?? '9999-12-31';
$idDepartamento = $_POST['iddepartamento'] ?? null;

/* ===== CONSULTA ===== */
$datos = $model->obtenerInforme($fechaInicio, $fechaFin, $idDepartamento);

/* ===== PASAR A LA VISTA ===== */
require_once "../back/vstActiv.php";
?>
</body>
</html>