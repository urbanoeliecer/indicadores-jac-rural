<?php require_once "../back/cntInter.php"; ?>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Intervenciones</title>
</head>

<body>
<script>
    const RUTA_CONTROLADOR = "../back/cntInter.php";
</script>
<script src="../back/cntInter.js"></script>
<form method="get">

<select id="dep" name="dep" onchange="cargarMunicipios(this.value)">
    <option value="">Departamento</option>
    <?php while ($d = $departamentos->fetch_assoc()): ?>
        <option value="<?= $d['iddepartamento'] ?>"
            <?= ($d['iddepartamento'] == $depSel) ? 'selected' : '' ?>>
            <?= $d['nombre'] ?>
        </option>
    <?php endwhile; ?>
</select>

<select id="mun" name="mun" onchange="cargarJuntas(this.value)">
    <option value="">Municipio</option>
    <?php if ($municipios): while ($m = $municipios->fetch_assoc()): ?>
        <option value="<?= $m['idmunicipio'] ?>"
            <?= ($m['idmunicipio'] == $munSel) ? 'selected' : '' ?>>
            <?= $m['nombre'] ?>
        </option>
    <?php endwhile; endif; ?>
</select>

<select id="jun" name="jun" onchange="validar()">
    <option value="">Junta</option>
    <?php if ($juntas): while ($j = $juntas->fetch_assoc()): ?>
        <option value="<?= $j['idjunta'] ?>"
            <?= ($j['idjunta'] == $junSel) ? 'selected' : '' ?>>
            <?= $j['nombre'] ?>
        </option>
    <?php endwhile; endif; ?>
</select>

<button id="btn" disabled>Enviar</button>

</form>
<script src="../back/cntInter.js"></script>
</body>
</html>