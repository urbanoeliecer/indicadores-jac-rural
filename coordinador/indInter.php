<?php
require_once "../back/modInter.php";

$rol = isset($_GET['rol']) ? intval($_GET['rol']) : 1;

$deps = ModInter::departamentos();
$idSantander = "";

foreach ($deps as $d) {
    if (strcasecmp($d['nombre'], 'Santander') === 0) {
        $idSantander = $d['id'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Intervención Territorial</title>
</head>
<body>

<h3>Filtro territorial</h3>

<form method="get">
<input type="hidden" name="rol" value="<?= $rol ?>">
<input type="hidden" id="rol" value="<?= $rol ?>">

<!-- DEPARTAMENTO -->
<label>Departamento</label><br>
<select id="dep" name="dep" <?= ($rol == 3) ? 'disabled' : '' ?>>
    <option value="">Seleccione departamento</option>
    <?php foreach ($deps as $d): ?>
        <option value="<?= $d['id'] ?>"
            <?= ($rol == 3 && $d['id'] == $idSantander) ? 'selected' : '' ?>>
            <?= $d['nombre'] ?>
        </option>
    <?php endforeach; ?>
</select>

<?php if ($rol == 3): ?>
    <input type="hidden" name="dep" value="<?= $idSantander ?>">
<?php endif; ?>

<br><br>

<!-- MUNICIPIO -->
<label>Municipio</label><br>
<select id="mun" name="mun">
    <option value="">Seleccione municipio</option>
</select>

<br><br>

<!-- JUNTA -->
<label>Junta</label><br>
<select id="jun" name="jun">
    <option value="">Seleccione junta</option>
</select>

<br><br>

<button type="submit" id="btnEnviar" disabled>Enviar</button>
</form>

<?php
if (isset($_GET['dep'])) {
    $total = ModInter::contarProyectos(
        $rol,
        $_GET['dep'] ?? 0,
        $_GET['mun'] ?? 0,
        $_GET['jun'] ?? 0
    );
    echo "<h4>Total de proyectos: $total</h4>";
}
?>

<script>
const RUTA_CONTROLADOR = "../back/cntInter.php";
</script>
<script src="../back/cntInter.js"></script>

</body>
</html>


