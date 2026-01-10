<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Informe de Participación</title>
<link rel="stylesheet" href="../back/estilos.css">
</head>
<body>
<br>
<a href="../principal.php">Principal</a></li>
<br>
<?php
require '../back/funciones.php';

// Valores por defecto
$idMatriz   = $_POST['matriz'] ?? 1;
$fechaFinal = $_POST['fecha']  ?? date('Y-m-d');
?>
<form method="post">
    <label>Matriz:</label>
    <select name="matriz">
        <option value="1" <?= $idMatriz == 1 ? 'selected' : '' ?>>Matriz 1</option>
        <option value="2" <?= $idMatriz == 2 ? 'selected' : '' ?>>Matriz 2</option>
    </select>

    &nbsp;&nbsp;

    <label>Fecha final:</label>
    <input type="date" name= name="fecha" value="<?= $fechaFinal ?>">

    &nbsp;&nbsp;

    <button type="submit">Enviar</button>
</form>
<?php
// Mostrar calendario
mostrarCalendario($idMatriz, $fechaFinal);
?>

</body>
</html>


