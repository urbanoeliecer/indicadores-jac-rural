
<?php
require_once "modInter.php";
$modelo = new InterModelo();

/* ----- ROL ----- */
$rol = isset($_GET['rol']) ? intval($_GET['rol']) : 1;

/* ----- VALORES ----- */
$depSel = $_GET['dep'] ?? '';
$munSel = $_GET['mun'] ?? '';
$junSel = $_GET['jun'] ?? '';

/* ----- AJAX ----- */
if (isset($_GET['ajax'])) {

    if ($_GET['ajax'] == 'municipios') {
        $r = $modelo->getMunicipios($_GET['id']);
        echo '<option value="">Seleccione municipio</option>';
        while ($f = $r->fetch_assoc()) {
            echo "<option value='{$f['idmunicipio']}'>{$f['nombre']}</option>";
        }
    }

    if ($_GET['ajax'] == 'juntas') {
        $r = $modelo->getJuntas($_GET['id']);
        echo '<option value="">Seleccione junta</option>';
        while ($f = $r->fetch_assoc()) {
            echo "<option value='{$f['idjunta']}'>{$f['nombre']}</option>";
        }
    }
    exit;
}

/* ----- DATOS PARA LA VISTA ----- */
$departamentos = $modelo->getDepartamentos();
$municipios = $depSel ? $modelo->getMunicipios($depSel) : null;
$juntas = $munSel ? $modelo->getJuntas($munSel) : null;
