
<?php
$rol = $_GET['rol'] ?? 0;
?>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Intervenciones</title>
<link rel="stylesheet" href="../back/estilos.css">
<script src="../back/vstInter.js"></script>
</head>
<body>
<a href="../principal.php">Principal</a><br>
<h2>Intervenciones</h2>
<!-- Formulario de filtros -->
<form id="formFiltros">
    <input type="hidden" name="rol" value="<?= $rol ?>">

    <label for="fecha_inicio">Fecha inicio</label>
    <input type="date" id="fecha_inicio" name="fecha_inicio">

    <label for="fecha_fin">Fecha fin</label>
    <input type="date" id="fecha_fin" name="fecha_fin">
    <br><br>
    <!-- Combos para Departamento, Municipio y Junta -->
    <select name="iddepartamento" id="departamento" onchange="cargarMunicipios()"></select>
    <select name="idmunicipio" id="municipio" onchange="cargarJuntas()"></select>
    <select name="idjunta" id="junta"></select>
    <br><br>
    <button type="submit" id="consultar">Consultar</button>
</form>
<hr>
<!-- Resumen y Detalles de los Proyectos -->
<div id="resumen"></div>
<div id="detalle"></div>
<script>
    // Cargar departamentos al cargar la página

    //cargarDepartamentos();

    // Función para validar y habilitar el botón de envío
    function validar() {
        var dep = document.getElementById("departamento").value;
        var mun = document.getElementById("municipio").value;
        var jun = document.getElementById("junta").value;
        var btn = document.getElementById("btn");

        // Habilitar el botón si los tres campos están seleccionados
        if (dep && mun && jun) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    }
</script>

</body>
</html>


