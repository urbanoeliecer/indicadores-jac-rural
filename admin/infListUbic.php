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
