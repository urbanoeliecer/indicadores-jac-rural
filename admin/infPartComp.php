<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe Part comunitaria</title>
<link rel="stylesheet" href="../back/estilos.css">
</head>
<body><a href="../principal.php">Principal</a>
<h2>Informe de Participación</h2>
<?php
// Grafica, hazla lleva los valores a un máximo de 1 proporcional al máximo que encuentres 
$data = [
    ['Betulia',  2511, 5],
    ['Betulia',  2512, 6],
    ['Zapatoca', 2511, 5],
    ['Zapatoca', 2512, 4],
    ['Lebrija',  2511, 3],
    ['Lebrija',  2512, 2],
];

// Máximo
$max = max(array_column($data, 2));

// Ancho máximo de la barra (en px)
$maxWidth = 300;

?>
<table border="0" cellpadding="5">
<?php
foreach ($data as $row): 
    $normalized = $row[2] / $max;           // valor entre 0 y 1
    $width = $normalized * $maxWidth;       // ancho proporcional
?>
<tr>
    <td><?= $row[0] ?></td>
    <td><?= $row[1] ?></td>
    <td>
        <img src="../img/barra.png" height="15" width="<?= $width ?>">
        <?= round($normalized, 2) ?>
    </td>
</tr>
<?php endforeach; ?>
</table>