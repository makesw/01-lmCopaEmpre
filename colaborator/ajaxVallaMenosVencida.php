<?php 
require '../conexion.php';
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
//Consutar equipos de la competiciòn:
$resultVmV = $connect->query( "select e.nombre, eg.GEC, eg.JUG, (eg.GEC/eg.JUG) prom from equipo_grupo eg   join equipo e on eg.id_equipo = e.id and eg.JUG <>0 join inscripcion i on e.id = i.id_equipo join competicion c on i.id_competicion = c.id and c.activa = 1 and c.id = ".$idComp." order by prom asc, eg.JUG desc");
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-JL" >
<thead>
	<tr>
		<th>Equipo</th>
		<th>GEC</th>
		<th>PJ</th>
		<th>Promedio</th>
	</tr>
</thead>
<tbody>
<?php
$i = 0;	
while($row = mysqli_fetch_array($resultVmV)){
?>	
<tr style="<?php if($i == 0 ){echo "background-color: #bbebba;"; } ?>">	
	<td> <?php echo $row['nombre']; ?>	</td>
	<td> <?php echo $row['GEC']; ?>	</td>
	<td> <?php echo $row['JUG']; ?>	</td>
	<td> <strong><?php echo round ($row['prom'],2); ?></strong>	</td>
</tr>
<?php $i++; } ?>
</tbody>
</table>
</div>
<script>
$(document).ready(function () {
$('.dataTables-JL').DataTable({
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