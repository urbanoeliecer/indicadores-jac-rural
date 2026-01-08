<?php session_start(); ?>
<html><head><meta><title>InvBasico</title></head><body>
<?php
if (isset($_SESSION["tipo"])) {
    include("conect.php"); 
    $link = Conectarse();    
    echo 'Estas como: '.$_SESSION["usuario"];
    echo '&nbsp;<a href="logOut.php">Cerrar sesión</a>';
// 0 Inactivar
    $idx = @$_GET['idx'];
    if ($idx != '') {
        $usql = "update tusuarios set est = 0 where idusr= '".$idx."'";
        mysqli_query($link,$usql);        
    }
// 0 Editar
    $id = @$_GET['id'];
    $txtId = @$_POST['txtId'];
    if ($txtId <> '') {
        $nombre = @$_POST['nmb'];
        $usr = @$_POST['usr'];
        $usql = "update tusuarios set nombre ='".$nombre."',user ='".$usr."' where idusr = '".$txtId."'";
        mysqli_query($link,$usql);
        //print $ssql;
    } 
// 1 Insertar 
    $nmb = @$_POST['txtnmbUsr'];
    if ($nmb <> '') {
        $isql = "insert into tusuarios (nombre) values ('".$nmb."') ";
        mysqli_query($link,$isql);
        //print $ssql;
    }
// 2 Listar    
    ?>
    <a href="principal.php">Principal</a><br><br>
    <table border= "1" align="center" width="50%">
        <tr><th bgcolor="#0022ff">Id<th>Usr<th>Pass<th>Nombre<th>Est<td>
        <?php
        $ssql = "select * from tusuarios where est = 1";
        $array = mysqli_query($link,$ssql);
        while ($f = mysqli_fetch_array($array)) {
            echo '<tr>';
            for ($i=0;$i<=4;$i++){
                echo '<td>'.$f[$i].'&nbsp;';
            }
            echo '<td><a href="usuarioseditar.php?id='.$f[0].'"><img src="img/editar.jpeg" width="18"></a>';
            echo '<a href="usuarios.php?idx='.$f[0].'"><img src="img/borrar.jpeg" width="18"></a>';
        } ?>  
    </table>
    <form action="usuariosagregar.php">
    <input type="submit" value="Agregar">    
    </form>
    <?php
}
else {
    echo 'La sesión está cerrada, debe volver a iniciarla<br>';
    echo '<a href="index.php">Iniciar sesión</a>';
}
?>
</body>
</html>
