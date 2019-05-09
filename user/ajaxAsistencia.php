<?php 
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
require '../conexion.php';
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
$resultJugadores = $connect->query( "select ju.id, e.id idEquipo,  e.nombre nombreEquipo, concat (ju.nombres,' ',ju.apellidos) nombreJugador from equipo e join jugador ju on e.id=ju.id_equipo and e.id_usuario = ".$_SESSION[ 'dataSession' ]['id']." join inscripcion i on e.id = i.id_equipo and i.id_competicion =".$idComp);
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-asis" >
<thead>
	<tr>
		<th>Equipo</th>
		<th>Jugador</th>
		<th>Cant. Asistencias</th>
		<th></th>
	</tr>
</thead>
<tbody>
<?php
while($row = mysqli_fetch_array($resultJugadores)){
//Consultar asistencias de cada jugador:
$asistencia = mysqli_fetch_array($connect->query( "select count(1) total from asistencia a join juego j on a.id_juego = j.id and a.id_jugador = ".$row['id']." join fase f on j.id_fase = f.id and f.id_competicion =".$idComp));
?>	
<tr>	
	<td> <?php echo $row['nombreEquipo']; ?> </td>
	<td> <?php echo $row['nombreJugador']; ?> </td>
	<td> <strong><?php echo $asistencia['total']; ?></strong> </td>
	<td> <a href="asistenciaDetalle.php?idJugador=<?php echo($row['id']); ?>&idComp=<?php echo $idComp; ?>&idEquipo=<?php echo $row['idEquipo']; ?>" class="btn btn-link btn-outline" type="button">Ver Detalle</a> </td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script>
$(document).ready(function () {
$('.dataTables-asis').DataTable({
	"searching": true,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 20,
	"order": [[ 2, "desc" ]],
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