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
          <li><a href="admin/infActividades.php">Inf. Consultas</a></li>
          <li><a href="admin/infListUbic.php">Inf. Ubicaciones</a></li>
          <li><a href="admin/infPartComp.php">Inf. Comparativo Part.</a></li>
          <li><a href="admin/infPartInd.php">Inf. Individual Part.</a></li>
          <li><a href="admin/infProy.php">Inf. de Proyectos</a></li>
          <li><a href="admin/infResReg.php">Inf. Resumen</a></li>
        </ul>
      </li>
    <?php //endif; ?>

    <?php //if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'coordinador'): ?>
      <li>
        Secretario
        <ul>
          <li><a href="coordinador/indInter.php">Informe de Intervención</a></li>
          <li><a href="coordinador/indCons.php">Informe de Consultas</a></li>
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