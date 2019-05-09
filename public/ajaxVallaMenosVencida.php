<?php 
require '../conexion.php';
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
//Consutar equipos de la competiciòn:
$resultVmV = $connect->query("select e.nombre, sum(eg.GEC) GEC, sum(eg.JUG) JUG, (sum(eg.GEC)/sum(eg.JUG)) prom from equipo_grupo eg   join equipo e on eg.id_equipo = e.id and eg.JUG <>0 join grupo g on eg.id_grupo = g.id join fase f on g.id_fase = f.id join competicion c on f.id_competicion = c.id and c.id = ".$idComp." group by  e.nombre order by prom asc, sum(eg.JUG) desc");
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-JL" >
<thead>
	<tr>
		<th>Equipo</th>
		<th>PJ</th>
		<th>GEC</th>
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
	<td> <?php echo $row['JUG']; ?>	</td>
	<td> <?php echo $row['GEC']; ?>	</td>	
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
	"oLanguage": {
	   "sSearch": "Buscar: "
	 }
});
});
</script>
<?php $connect->close(); ?>