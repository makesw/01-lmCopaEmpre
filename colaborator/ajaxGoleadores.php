<?php 
require '../conexion.php';
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
$resultGolesCompeticion = $connect->query( "select  concat(j.nombres,' ',j.apellidos) nombres, e.nombre, j.url_foto, g.id_jugador, sum(g.valor) goles from gol g join jugador j on g.id_jugador = j.id join equipo e on j.id_equipo = e.id join inscripcion i on e.id = i.id_equipo and i.id_competicion = ".$idComp." group by id_jugador order by goles desc" );
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-goleadores" >
<thead>
	<tr>
		<th></th>
		<th>Nombre</th>
		<th>Equipo</th>
		<th>Goles</th>
	</tr>
</thead>
<tbody>
<?php
$i =0;
while($row = mysqli_fetch_array($resultGolesCompeticion)){	
?>	
<tr style="<?php if($i == 0 ){echo "background-color: #bbebba;"; } ?>">
	<td class="size-80"><img title="" alt="" src="<?php echo $row['url_foto'];?>" class="avatar img-circle"></td>
	<td> <?php echo $row['nombres']; ?>	</td>
	<td> <?php echo $row['nombre']; ?>	</td>
	<td> <strong><?php echo $row['goles']; ?></strong>	</td>	
</tr>
<?php $i++; } ?>
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