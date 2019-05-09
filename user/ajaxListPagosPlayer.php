<?php 
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
require '../conexion.php';
$idPlayer = isset($_GET[ 'idPlayer' ])?$_GET[ 'idPlayer' ]:null;
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
$resultPagos = $connect->query( "select * from pago_jugador where id_jugador=".$idPlayer." and id_competicion=".$idComp." order by fecha desc" );
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-paysPlayer" >
<thead>
	<tr>
		<th>Valor</th>
		<th>Fecha</th>
		<th></th>
	</tr>
</thead>
<tbody>
<?php
while($row = mysqli_fetch_array($resultPagos)){
	$date_f = new DateTime($row['fecha']);
	$date_f = $date_f->format('d-m-Y');	
?>	
<tr>	
	<td><?php echo $row['valor']; ?></td>
	<td><?php echo $date_f; ?></td>
	<td>
		<a title="Borrar" href="javaScript:delPayPlayer('<?php echo $row['id']; ?>');">
		<i class="icon-cancel icon-larger red-color"></i> </a>
	</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script>
$(document).ready(function () {
$('.dataTables-paysPlayer').DataTable({
	"searching": false,
	"bSort" : false,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 10,	
});
});
</script>
<?php $connect->close(); ?>