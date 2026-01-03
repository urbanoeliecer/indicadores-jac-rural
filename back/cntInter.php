<?php
require_once "modInter.php";

/* ===== MUNICIPIOS ===== */
if (isset($_GET['ajax']) && $_GET['ajax'] === 'municipios') {

    $idDep = intval($_GET['id'] ?? 0);
    $data = ModInter::municipiosPorDepartamento($idDep);

    echo '<option value="">Seleccione municipio</option>';
    foreach ($data as $m) {
        echo "<option value='{$m['id']}'>{$m['nombre']}</option>";
    }
    exit;
}

/* ===== JUNTAS ===== */
if (isset($_GET['ajax']) && $_GET['ajax'] === 'juntas') {

    $idMun = intval($_GET['id'] ?? 0);
    $data = ModInter::juntasPorMunicipio($idMun);

    echo '<option value="">Seleccione junta</option>';
    foreach ($data as $j) {
        echo "<option value='{$j['id']}'>{$j['nombre']}</option>";
    }
    exit;
}
