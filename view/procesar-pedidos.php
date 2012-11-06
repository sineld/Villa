<?php
if(isset($_SESSION['cliente']->articulos) and count($_SESSION['cliente']->articulos)>=1){
	require_once('phputils/class.phpmailer.php');
	require_once('phputils/class.smtp.php');
	
	if (isset($_SESSION['cliente']->pedido)){
		echo '<pre>Error, ya existe un pedido procesado. Por favor, verifique su orden</pre>';
	}else{
		$pedido = new Pedidos;
		$pedido->procPedSesion();
	
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
	$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	$mail->Port       = 465;                   // set the SMTP port
	$mail->Username   = "no-reply@villadelasmascotas.com";  // GMAIL username
	$mail->Password   = "m643140991";            // GMAIL password
	$mail->SMTPDebug = 1;
	$mail->From       = "no-reply@villadelasmascotas.com";
	$mail->FromName   = "Respuesta automatica";
	$mail->Subject    = "Orden N#: ".$_SESSION['cliente']->pedido." - Importadora La Villa de las Mascotas, C.A.";
	$mail->AltBody    = "This is the body when user views in plain text format"; //Text Body
	$mail->WordWrap   = 50; // set word wrap
	$mensaje='';
	$headers = "From: no-reply@villadelasmascotas.com\r\n";
	$headers .= "Reply-To: no-reply@villadelasmascotas.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$mensaje .= '<html><body>';
	$mensaje .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
	$mensaje .=	'<thead>';
	$mensaje .= '<tr style="background-color: #363636; color: #FFFFFF">';
	$mensaje .=	'<th><h5>Codigo</h5></th>';
	$mensaje .= '<th><h5>Descripcion</h5></th>';
	$mensaje .=	'<th><h5>Imagen</h5></th>';
	$mensaje .=	'<th><h5>Precio unitario</h5></th>';
	$mensaje .=	'<th><h5>Cantidad</h5></th>';
	$mensaje .=	'<th><h5>Subtotal</h5></th>';
	$mensaje .=	'</tr>';
	$mensaje .= '</thead>';
	$mensaje .= '<tbody>';
 	$articulos = $_SESSION['cliente']->articulos;
	$cliente = $_SESSION['cliente'];
	$total = 0;
	foreach($articulos as $key => $value){
			$qArticulo = Doctrine::getTable('articulos')->findOneById($key);
			$qFotosArt = Doctrine_Query::create()
				-> select('*')
				-> from('fotosArt')
				-> where('id_art = ?',$key)
				-> andWhere('inactivo = 0');
			$fotosArt = $qFotosArt -> execute() -> toArray();
			$qFotos = Doctrine_Query::create()
				-> select('*')
				-> from('fotos')
				-> where('id = ?',$fotosArt[0]['id_foto'])
				-> andWhere('inactivo = 0');
			$fotos = $qFotos -> execute() -> toArray();
			$qPrecioArt = Doctrine_Query::create()
				-> select('*')
				-> from('precioArt')
				-> where('id_art = ?',$key)
				-> andWhere('id_tipo_cliente = ?',$cliente->id_tipo)
				-> andWhere('inactivo = 0');
			$precioArt = $qPrecioArt -> execute() -> toArray();
			//Obtengo informacion de la tabla precio
			$qPrecio = Doctrine_Query::create()
				-> select('*')
				-> from('precios')
				-> where('id = ?',$precioArt[0]['id_precio'])
				-> andWhere('inactivo = 0');
			$precio = $qPrecio -> execute() -> toArray();
			$mensaje .= '<tr>';
			$mensaje .= '<td>'.$qArticulo->codigo.'</td>';
			$mensaje .= '<td>'.$qArticulo->nombre.'</td>';
			$mensaje .= '<td><img class="thumbnail" src="'.$GLOBALS['baseURL'].''.$fotos[0]['direccion'].'thumbs/'.$fotos[0]['descripcion'].'.jpg" /></td>';
			$mensaje .= '<td>'.number_format($precio[0]['precio'],2,".",",").' Bs.</td>';
			$mensaje .= '<td>'.$value.'</td>';
			$subtotal = $precio[0]['precio']*$value;
			$mensaje .= '<td>'.number_format($subtotal,2,".",",").' Bs.</td>';
			$mensaje .= '</tr>';
			$total += $subtotal;
	}
	$mensaje .=	'</tbody>';
	$mensaje .= '<tfoot>';
	$mensaje .= '<tr>';
	$mensaje .= '<td colspan="5" style="text-align: right;">Subtotal:</td>';
	$mensaje .= '<td colspan="2">'.number_format($total,2, ".",",").' Bs.</td>';
	$mensaje .= '</tr>';
	$mensaje .= '<tr>';
	$mensaje .= '<td colspan="5" style="text-align: right;">I.V.A:</td>';
	$mensaje .= '<td colspan="2">'.number_format($total*0.12,2,".",",").' Bs.</td>';
	$mensaje .= '</tr>';
	$mensaje .= '<tr>';
	$mensaje .= '<td colspan="5" style="text-align: right;">Total:</td>';
	$mensaje .= '<td colspan="2">'.number_format($total*1.12, 2, ".", ",").' Bs.</td>';
	$mensaje .= '</tr>';
	$mensaje .= '</tfoot>';
	$mensaje .=	'</table>';
	$mensaje .= '</body></html>';
	$mail->MsgHTML($mensaje);
	$mail->AddAddress($_SESSION['user']->name,$_SESSION['cliente']->nombre);
	$mail->AddAddress('ventas@villadelasmascotas.com');
	$mail->IsHTML(true); // send as HTML	
	if(!$mail->Send()) {
  			//echo "Error: " . $mail->ErrorInfo;
		} else {
			echo '<div class="span12 row" style="padding-top: 20px;">';
			echo '<pre>Estimado cliente, su pedido está siendo procesado en estos momentos, un ejecutivo de ventas se comunicará con usted en las próximas 48 horas, confirmando el total de su orden y la disponibilidad de la misma.</pre>';	
	}
	}
	

	
	

?>
</div>
<?php
}else{
?>
<div class="span12">
	<pre><p>No hay pedido definido</p></pre>
</div>
<?php

}
?>
<!--
<script type="text/javascript">


var tipo, cat, id_tipo_cliente;



</script> -->