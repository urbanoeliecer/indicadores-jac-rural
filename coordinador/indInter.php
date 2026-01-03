<?php require_once "../back/cntInter.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">

<script>
function cargarMunicipios(id){
    fetch("../back/cntInter.php?ajax=municipios&id="+id)
    .then(r=>r.text())
    .then(t=>{
        municipio.innerHTML=t;
        junta.innerHTML='<option value="">Seleccione junta</option>';
        validar();
    });
}

function cargarJuntas(id){
    fetch("../back/cntInter.php?ajax=juntas&id="+id)
    .then(r=>r.text())
    .then(t=>{
        junta.innerHTML=t;
        validar();
    });
}

function validar(){
    btn.disabled = !(dep.value && mun.value && jun.value);
}




</script>
</head>

<body>

<form>
<select id="dep" name="dep" onchange="cargarMunicipios(this.value)">
<option value="">Departamento</option>
<?php while($d=$departamentos->fetch_assoc()): ?>
<option value="<?= $d['iddepartamento'] ?>"><?= $d['nombre'] ?></option>
<?php endwhile; ?>
</select>

<select id="mun" name="mun" onchange="cargarJuntas(this.value)">
<option value="">Municipio</option>
<?php if($municipios): while($m=$municipios->fetch_assoc()): ?>
<option value="<?= $m['idmunicipio'] ?>"><?= $m['nombre'] ?></option>
<?php endwhile; endif; ?>
</select>

<select id="jun" name="jun" onchange="validar()">
<option value="">Junta</option>
<?php if($juntas): while($j=$juntas->fetch_assoc()): ?>
<option value="<?= $j['idjunta'] ?>"><?= $j['nombre'] ?></option>
<?php endwhile; endif; ?>
</select>

<button id="btn" disabled>Enviar</button>
</form>
<script>
function cargarMunicipios(id){
    console.log("Cargando municipios de:", id);

    fetch("../back/cntInter.php?ajax=municipios&id="+id)
    .then(r => r.text())
    .then(t => {
        document.getElementById("mun").innerHTML = t;
        document.getElementById("jun").innerHTML =
            '<option value="">Seleccione junta</option>';
    });
}

function cargarJuntas(id){
    console.log("Cargando juntas de:", id);

    fetch("../back/cntInter.php?ajax=juntas&id="+id)
    .then(r => r.text())
    .then(t => {
        document.getElementById("jun").innerHTML = t;
    });
}

document.addEventListener("DOMContentLoaded", function () {

    const dep = document.getElementById("dep");
    const mun = document.getElementById("mun");

    console.log("Departamento al cargar:", dep.value);
    console.log("Municipio al cargar:", mun.value);

    if (dep.value) {
        cargarMunicipios(dep.value);
    }

    if (mun.value) {
        cargarJuntas(mun.value);
    }
});
</script>
</body>

</html>
