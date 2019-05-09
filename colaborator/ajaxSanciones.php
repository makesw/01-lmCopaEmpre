<?php 
require '../conexion.php';
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
$resultGolesCompeticion = $connect->query( "select concat(j.nombres,' ',j.apellidos) nombres ,j.url_foto,e.nombre, ts.nombre nombreTs, s.fecha, ts.puntos pts from sancion s join tipo_sancion ts on ts.id = s.id_tipo_sancion join jugador j on s.id_jugador = j.id join equipo e on j.id_equipo = e.id join inscripcion i on e.id = i.id_equipo and i.id_competicion = ".$idComp );
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-goleadores" >
<thead>
	<tr>
		<th></th>
		<th>Nombres</th>
		<th>Equipo</th>
		<th>Sancion</th>
		<th>Puntos</th>
		<th>Fecha</th>
	</tr>
</thead>
<tbody>
<?php
while($row = mysqli_fetch_array($resultGolesCompeticion)){	
?>	
<tr>
	<td class="size-80"><img title="" alt="" src="<?php echo $row['url_foto'];?>" class="avatar img-circle"></td>
	<td> <?php echo $row['nombres']; ?>	</td>
	<td> <?php echo $row['nombre']; ?>	</td>
	<td> <strong><?php echo $row['nombreTs']; ?></strong>	</td>
	<td> <strong><?php echo $row['pts']; ?></strong>	</td>
	<td> <?php echo $row['fecha']; ?>	</td>	
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script>
$(document).ready(function () {
$('.dataTables-goleadores').DataTable({
	"searching": true,
	"bSort" : false,
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