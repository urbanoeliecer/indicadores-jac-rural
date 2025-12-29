<?php
//session_start();
?>

<nav>
  <ul>
    <li><a href="index.php">Inicio</a></li>
    <li><a href="conect.php">Conexión</a></li>
    <li><a href="funciones.php">Funciones</a></li>

    <?php // if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
      <li>
        Admin
        <ul>
          <li><a href="admin/infCons.php">Inf. Consolidada</a></li>
        </ul>
      </li>
    <?php //endif; ?>

    <?php //if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'coordinador'): ?>
      <li>
        Secretario
        <ul>
          <li><a href="coordinador/indDep.php">Información Departamental</a></li>
          <li><a href="coordinador/indMun.php">Información Municipal</a></li>
          <li><a href="coordinador/indNac.php">Información Nacional</a></li>
        </ul>
      </li>
    <?php //endif; ?>
  </ul>
</nav>

