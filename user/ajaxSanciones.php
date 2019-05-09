<?php 
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
require '../conexion.php';
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
$resultGolesCompeticion = $connect->query( "select  e.id_usuario, concat(ju.nombres,' ',ju.apellidos) nombres,ju.url_foto, e.nombre nombreEqui, ts.nombre nombreTs, ts.fechas_suspencion, s.fecha, ts.puntos pts FROM sancion s join juego j on s.id_juego = j.id join fase f on j.id_fase= f.id and f.id_competicion = ".$idComp." join jugador ju on s.id_jugador = ju.id and ju.activo is null join equipo e on ju.id_equipo = e.id and e.id_usuario = ".$_SESSION[ 'dataSession' ]['id']." join tipo_sancion ts on s.id_tipo_sancion = ts.id order by s.fecha desc" );

?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-goleadores" >
<thead>
	<tr>
		<th></th>
		<th>Nombres</th>
		<th>Equipo</th>
		<th>Sancion</th>
		<th>Fechas Sanción</th>
		<th>Puntos</th>
		<th>Fecha</th>
	</tr>
</thead>
<tbody>
<?php
while($row = mysqli_fetch_array($resultGolesCompeticion)){
    $date_f = new DateTime($row['fecha']);
?>	
<tr>
	<td class="size-80"><img title="" alt="" src="<?php echo $row['url_foto'];?>" class="avatar img-circle"></td>
	<td> <?php echo $row['nombres']; ?>	</td>
	<td> <?php echo $row['nombreEqui']; ?>	</td>
	<td> <strong><?php echo $row['nombreTs']; ?></strong>	</td>
	<td> <strong><?php echo $row['fechas_suspencion']; ?></strong>	</td>
	<td> <strong><?php echo $row['pts']; ?></strong>	</td>
	<td> <?php echo $date_f->format('d-m-Y'); ?>	</td>	
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script>
$(document).ready(function () {
$('.dataTables-goleadores').DataTable({
	"searching": true,
	"bSort" : true,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 20,
	dom: '<"html5buttons" B>lTfgitp',
		buttons: [				
			{
				extend: 'excelHtml5',
				exportOptions: {
					columns: ':visible'
				}
			},
			{
				extend: 'pdfHtml5',
				exportOptions: {
					columns: ':visible'
				}
			}
		]
});
});
</script>
<?php $connect->close(); ?>