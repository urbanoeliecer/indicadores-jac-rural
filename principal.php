<?php
//session_start();
?>

<nav>
  <ul>
    <li><a href="index.php">Inicio</a></li>

    <?php // if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
      <li>
        Admin
        <ul>
          <li><a href="admin/infConsultas.php">Inf. Consultas</a></li>
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

