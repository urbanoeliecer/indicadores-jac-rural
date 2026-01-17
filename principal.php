<?php session_start(); ?>
<html><head><meta><title>InvBasico</title></head><body>
<?php
if (isset($_SESSION["usuario"])) {
    echo 'Estas como: '.$_SESSION["usuario"];
    ?>
    &nbsp;<a href="logOut.php">Cerrar sesión</a>

<nav>
  <ul>
    <li><a href="index.php">Inicio</a></li>

    <?php // if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
      <li>
        Admin
        <ul>
          <li><a href="admin/infAgrGrafActivRes.php">Inf. Mensual Resum. de Actividades (G)</a></li>
          <li><a href="admin/infAgrTabActivConsMVC.php">Inf. Mensual Det. de Actividades (T)</a></li>
          <li><a href="admin/infGrafUbic.php">Inf. Graf. Ubicaciones</a></li>
          <li><a href="admin/infGrafUso.php">Inf. Graf. de Uso</a></li>
          <li><a href="admin/infTabProy.php">Inf. Tab. Proyectos</a></li>
        </ul>
      </li>
    <?php //endif; ?>

    <?php //if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'coordinador'): ?>
      <li>
        Coordinador
        <ul>
          <li><a href="coordinador/indInterConsMVC.php">Ind. de Intervención Consolidado</a></li>
          <li><a href="coordinador/indInterDetGraf.php">Ind. de Intervención Detalle</a></li>
        </ul>
      </li>
    <?php //endif; ?>
  </ul>
</nav>    
    

    <div class="container">
        <div class="col-md-3"><?php
        if ($_SESSION["rol"] == 1) {
            echo '<a href="usuarios.php">Gestión de Usuarios</a><br>';
            echo '<a href="productos.php">Gestión de Productos</a><br>';
        }
        ?>
            <a href="compras.php">Compras</a><br>
        </div>
    </div><?php
}
else {
    echo 'La sesión está cerrada, debe volver a iniciarla<br>';
    echo '<a href="index.php">Iniciar sesión</a>';
}
?>
</body></html>