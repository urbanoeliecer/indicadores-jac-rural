<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Ubicaciones</title>
<link rel="stylesheet" href="../back/estilos.css">
</head>
<body>
<a href="../principal.php">Principal</a></li>
<br>
<?php

$data = [
    ['San Bernardo',  25, 5],
    ['San Bernardo',  24, 6],
    ['Zapatoca', 25, 5],
    ['Zapatoca', 24, 4],
    ['Lebrija',  25, 3],
    ['Lebrija',  24, 2],
];

// Máximo
$max = max(array_column($data, 2));

// Ancho máximo de la barra (en px)
$maxWidth = 300;

?>

<table border="0" cellpadding="5">
<?php foreach ($data as $row): 
    $normalized = $row[2] / $max;           // valor entre 0 y 1
    $width = $normalized * $maxWidth;       // ancho proporcional
?>
<tr>
    <td><?= $row[0] ?></td>
    <td><?= $row[1] ?></td>
    <td>
        <img src="../img/barra.png" height="15" width="<?= $width ?>">
        <?= $row[2] ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
<?php
/****************************
 * 1. CONEXIÓN A LA BD
 ****************************/
$conexion = mysqli_connect(
    "localhost",   // servidor
    "root",        // usuario
    "",            // contraseña
    "bdsara"    // nombre de la BD
);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

/****************************
 * 2. CONSULTA
 ****************************/
$sql = "SELECT 
    e.idorg,
    t.idtipoactivo,
    t.tipElmNombre,
    COUNT(e.idelemento) AS total
FROM telementos e
INNER JOIN telementoscls c ON e.idelementocls = c.idelementocls
INNER JOIN telementostip t ON c.idtipoactivo = t.idtipoactivo
WHERE e.Estado = 1
GROUP BY e.idorg, t.idtipoactivo
ORDER BY e.idorg, total DESC
";

$result = mysqli_query($conexion, $sql);

/****************************
 * 3. PROCESAR RESULTADOS
 ****************************/
$data = [];
$maxTotal = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;

    if ($row['total'] > $maxTotal) {
        $maxTotal = $row['total'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gráfica de Elementos por Junta</title>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 80%;
        }

        th, td {
            border: 1px solid #999;
            padding: 6px;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>

<h2>Elementos por Junta y Tipo de Activo</h2>

<table>
    <tr>
        <th>Junta</th>
        <th>Tipo de elemento</th>
        <th>Total</th>
        <th>Gráfica</th>
    </tr>

<?php
/****************************
 * 4. MOSTRAR TABLA + BARRAS
 ****************************/
$anchoMaximo = 300; // ancho máximo de la barra en px
$jntant = '';
foreach ($data as $row) {

    $porcentaje = 0;
    $anchoBarra = 0;

    if ($maxTotal > 0) {
        $porcentaje = ($row['total'] / $maxTotal) * 100;
        $anchoBarra = ($row['total'] / $maxTotal) * $anchoMaximo;
    }
    $jnt = $row['idorg'];
    if ($jnt <> $jntant && $jntant <> '') {
	echo '<tr><td>';
	
    }
    
    $jntant = $jnt; 
    ?>
    <tr>
        <td><?php echo $jnt; ?></td>
        <td><?php echo $row['tipElmNombre']; ?></td>
        <td align="center"><?php echo $row['total']; ?></td>
        <td>
            <img 
                src="../img/barra.png"
                width="<?php echo intval($anchoBarra); ?>"
                height="20"
                alt="<?php echo round($porcentaje,1); ?>%"
            >
            <?php echo round($porcentaje,1); ?>%
        </td>
    </tr>
<?php } ?>

</table>

</body>
</html>

<?php
/****************************
 * 5. CERRAR CONEXIÓN
 ****************************/
mysqli_close($conexion);
?>

