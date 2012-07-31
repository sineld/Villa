<?php

require_once('../../globals.php');

$conexion = mysql_connect($GLOBALS["dbServer"],$GLOBALS["dbUser"],"") or die('No se a podido conectar a mysql: ' . mysql_error());
mysql_select_db($GLOBALS["dbName"], $conexion) or die('No se ha encontrado la base de datos ');
$results = mysql_query("show tables;");

mysql_close($conexion);

?>
<div style="width:600px;">
    <p>Selecciona las tablas sobre las que quieres generar formularios de insercion:</p>
    <ul>
    <form action="crud_action.php" method="post">
    <?php while($tabla = mysql_fetch_row($results)) { ?>
        <li><input name="tablas[]" type="checkbox" value="<?php echo $tabla[0] ?>" /><?php echo $tabla[0] ?></li>
        <?php } ?>
    </ul>
    <p style="text-align:right; background-color:#D7DFFF;"><input name="" type="submit" value="Enviar" /></p>
    </form>
</div>