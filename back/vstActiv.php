<?php

//cambiar todo lo que diga cnt por vst

function mostrarTablaActividades($datos) {
?>
<table border="1" cellpadding="5">
<tr>
    <th>#</th>
    <th>ID Proyecto</th>
    <th>Proyecto</th>
    <th>Beneficiarios</th>
    <th>Departamento</th>
    <th>Municipio</th>
    <th>Junta</th>
    <th>Mes</th>
    <th>Presupuesto Proyecto</th>
    <th>Total Presupuesto Actividades</th>
    <th>Total Personas</th>
    <th>Total Horas</th>
    <th># Actividades</th>
</tr>

<?php
$i = 1;
if (!empty($datos)):
    foreach ($datos as $row):
?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= $row['idproyecto'] ?></td>
    <td><?= $row['nombreproyecto'] ?></td>
    <td><?= $row['beneficiarios'] ?></td>
    <td><?= $row['departamento'] ?></td>
    <td><?= $row['municipio'] ?></td>
    <td><?= $row['junta'] ?></td>
    <td><?= $row['mes'] ?></td>
    <td><?= $row['presupuesto_proyecto'] ?></td>
    <td><?= $row['total_presupuesto_actividades'] ?></td>
    <td><?= $row['total_personas'] ?></td>
    <td><?= $row['total_horas'] ?></td>
    <td><?= $row['total_actividades'] ?></td>
</tr>
<?php
    endforeach;
else:
?>
<tr>
    <td colspan="13">No hay información</td>
</tr>
<?php endif; ?>
</table>
<?php
}
mostrarTablaActividades($datos); 




