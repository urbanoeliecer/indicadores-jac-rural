<?php

//cambiar todo lo que diga cnt por vst

function mostrarTablaActividades($datos) {
?>
<table border="1" cellpadding="5">
<tr>
    <th>#</th>
    <th>Id</th>
    <th>Proyecto</th>
    <th>Benef.</th>
    <th>Departamento</th>
    <th>Municipio</th>
    <th>Junta</th>
    <th>Mes</th>
    <th>Pres.</th>
    <th>Pres. Ejec.</th>
    <th>Benef. Prm.</th>
    <th>Cant. Horas</th>
    <th>Cant. Activ.</th>
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
    <?php
    echo '<td>' . (
    $row['total_actividades'] > 0
        ? round($row['total_personas'] / $row['total_actividades'], 1)
        : 0
    ) . '</td>';
    ?>
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




