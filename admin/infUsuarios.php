<?php
require_once "../back/conexion.php";

$sql = "
SELECT
    p.idproyecto,
    p.nombre AS nombreproyecto,
    p.beneficiarios,
    d.nombre AS departamento,
    m.nombre AS municipio,
    j.nombre AS junta,
    u.nombre1 AS usuario,
    DATE_FORMAT(a.fecha, '%Y-%m') AS mes,
    a.tipAct,
    p.monto AS presupuesto_proyecto,
    SUM(a.presupuesto) AS total_presupuesto_actividades,
    SUM(a.cntpersonas) AS total_personas,
    SUM(a.horas) AS total_horas,
    COUNT(a.idact) AS total_actividades
FROM tpryact a
JOIN proyectos p ON a.idpry = p.idproyecto
JOIN juntas j ON p.idjunta = j.idjunta
JOIN municipios m ON j.idmunicipio = m.idmunicipio
JOIN departamentos d ON m.iddepartamento = d.iddepartamento
JOIN tusuarios u ON a.idusr = u.idusuario
GROUP BY
    p.idproyecto,
    YEAR(a.fecha),
    MONTH(a.fecha),
    a.tipAct,
    u.nombre1,
    d.nombre,
    m.nombre,
    j.nombre,
    p.nombre,
    p.beneficiarios,
    p.monto
ORDER BY mes, p.idproyecto
";

$stmt = $pdo->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Informe consolidado mensual de actividades</h3>

<table border="1" cellpadding="5">
    <tr>
        <th>#</th>
        <th>ID Proyecto</th>
        <th>Proyecto</th>
        <th>Beneficiarios</th>
        <th>Departamento</th>
        <th>Municipio</th>
        <th>Junta</th>
        <th>Usuario</th>
        <th>Mes</th>
        <th>Tipo Act</th>
        <th>Presupuesto Proyecto</th>
        <th>Total Presupuesto Actividades</th>
        <th>Total Personas</th>
        <th>Total Horas</th>
        <th># Actividades</th>
    </tr>

<?php $i = 1; foreach ($data as $row): ?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= $row['idproyecto'] ?></td>
    <td><?= $row['nombreproyecto'] ?></td>
    <td><?= $row['beneficiarios'] ?></td>
    <td><?= $row['departamento'] ?></td>
    <td><?= $row['municipio'] ?></td>
    <td><?= $row['junta'] ?></td>
    <td><?= $row['usuario'] ?></td>
    <td><?= $row['mes'] ?></td>
    <td><?= $row['tipAct'] ?></td>
    <td><?= $row['presupuesto_proyecto'] ?></td>
    <td><?= $row['total_presupuesto_actividades'] ?></td>
    <td><?= $row['total_personas'] ?></td>
    <td><?= $row['total_horas'] ?></td>
    <td><?= $row['total_actividades'] ?></td>
</tr>
<?php endforeach; ?>
</table>

