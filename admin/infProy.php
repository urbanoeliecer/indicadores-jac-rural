SELECT *
FROM viewproyectosxjunta
WHERE beneficiarios = (
    SELECT MAX(beneficiarios) FROM viewproyectosxjunta
);	

SELECT *
FROM proyectos
WHERE beneficiarios = (
    SELECT MAX(beneficiarios) FROM proyectos
);

SELECT *
FROM proyectos
ORDER BY beneficiarios DESC
LIMIT 1;

<?php

// ===========================
// 1. Conexión a MySQL
// ===========================
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdsara";     
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// ===========================
// 2. Captura de parámetros
// ===========================
$municipio = $_GET['municipio'] ?? '';
$junta = $_GET['junta'] ?? '';
$fecha_inicio = $_GET['fechainicio'] ?? '';
$fecha_fin = $_GET['fechafin'] ?? '';

$order = $_GET['order'] ?? 'junta';
$allowed = ['junta','monto','beneficiarios','fechainicio'];
if (!in_array($order,$allowed)) { $order = 'junta'; }
print $order;

// paginación
$limite = 10;
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($pagina - 1) * $limite;

// ===========================
// 3. Construir WHERE dinámico
// ===========================
$where = "WHERE 1=1";

if ($municipio !== '') {
    $where .= " AND municipio LIKE '%" . $conn->real_escape_string($municipio) . "%'";
}
if ($junta !== '') {
    $where .= " AND nombrejunta LIKE '%" . $conn->real_escape_string($junta) . "%'";
}
if ($fecha_inicio !== '') {
    $where .= " AND fechainicio >= '" . $conn->real_escape_string($fecha_inicio) . "'";
}
if ($fecha_fin !== '') {
    $where .= " AND fechafinal <= '" . $conn->real_escape_string($fecha_fin) . "'";
}

// ===========================
// 4. Contar total de registros
// ===========================
$sql_total = "SELECT COUNT(*) AS total FROM vproyectosxjunta $where";
$result_total = $conn->query($sql_total);
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $limite);

// ===========================
// 5. Consulta final con límite
// ===========================
$sql = "SELECT * 
        FROM vproyectosxjunta 
        $where ";
$sql .= " ORDER BY $order desc";
$sql .= " LIMIT $limite OFFSET $offset";

print '<br>'.$sql.'<br>';

$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Búsqueda de Proyectos</title>

    <style>
        body { font-family: Arial; padding: 20px; }
        form { margin-bottom: 20px; padding: 10px; background: #f7f7f7; border-radius: 6px; }
        label { display: inline-block; width: 150px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
        .paginacion { margin-top: 20px; }
        .paginacion a {
            padding: 6px 12px;
            margin: 0 4px;
            background: #ddd;
            text-decoration: none;
            border-radius: 3px;
        }
        .paginacion span {
            padding: 6px 12px;
            margin: 0 4px;
            background: #bbb;
            border-radius: 3px;
        }
    </style>
</head>
<body>

<h1>Proyectos por Junta de Acción Comunal</h1>

<!-- =========================== -->
<!-- FORMULARIO DE BÚSQUEDA -->
<!-- =========================== -->
<form method="GET">
    <div>
        <label>Municipio:</label>
        <input type="text" name="municipio" value="<?= htmlspecialchars($municipio) ?>">
    </div>
    <div>
        <label>Junta:</label>
        <input type="text" name="junta" value="<?= htmlspecialchars($junta) ?>">
    </div>
    <div>
        <label>Fecha inicio desde:</label>
        <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">
    </div>
    <div>
        <label>Fecha inicio hasta:</label>
        <input type="date" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">
    </div>

    <br>
    <button type="submit">Buscar</button>
<a href="proyectos.php?order=junta">Ordenar por Junta</a> |
<a href="proyectos.php?order=monto">Ordenar por Monto</a> |
<a href="proyectos.php?order=beneficiarios">Ordenar por Beneficiarios</a> |
<a href="proyectos.php?order=fechainicio">Ordenar por Fecha Inicio</a>
</form>

<!-- =========================== -->
<!-- TABLA DE RESULTADOS -->
<!-- =========================== -->
<table>
    <tr>
        <th>Departamento</th>
        <th>Municipio</th>
        <th>ID Junta</th>
        <th>Junta</th>
        <th>Monto</th>
        <th>Beneficiarios</th>
        <th>tipo</th>
        <th>ID Proyecto</th>
        <th>Proyecto</th>
        <th>Representante</th>
        <th>Fecha Inicio</th>
	<th>Fecha Final</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['departamento'] ?></td>
                <td><?= $row['municipio'] ?></td>
                <td><?= $row['idjunta'] ?></td>
                <td><?= $row['junta'] ?></td>
                <td><?= $row['monto'] ?></td>
                <td><?= $row['beneficiarios'] ?></td>
                <td><?= $row['tipo'] ?></td>
                <td><?= $row['idproyecto'] ?></td>
                <td><?= $row['nombreproyecto'] ?></td>
                <td><?= $row['nombrerepresentante'] ?? '—' ?></td>
                <td><?= $row['fechainicio'] ?></td>
		<td><?= $row['fechafinal'] ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="9">No se encontraron resultados.</td></tr>
    <?php endif; ?>
</table>

<!-- =========================== -->
<!-- PAGINACIÓN -->
<!-- =========================== -->
<div class="paginacion">
    <?php if ($pagina > 1): ?>
        <a href="?<?= http_build_query(array_merge($_GET, ["pagina" => $pagina-1])) ?>">Anterior</a>
    <?php endif; ?>

    <span>Página <?= $pagina ?> de <?= $total_paginas ?></span>

    <?php if ($pagina < $total_paginas): ?>
        <a href="?<?= http_build_query(array_merge($_GET, ["pagina" => $pagina+1])) ?>">Siguiente</a>
    <?php endif; ?>
</div>

</body>
</html>
