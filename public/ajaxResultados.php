<?php 
require '../conexion.php';
$idFase = isset($_GET[ 'idFase' ])?$_GET[ 'idFase' ]:0;
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:0;
$resultResultados = $connect->query( "select distinct j.id,id_equipo_1,nombre1,id_equipo_2,nombre2,fecha, g.nombre nombreGrupo from competicion c join fase f ON c.id = f.id_competicion AND c.id=".$idComp." AND f.id=".$idFase." JOIN grupo g ON f.id = g.id_fase  AND g.id_fase=".$idFase." JOIN equipo_grupo eg ON g.id = eg.id_grupo JOIN juego j ON (eg.id_equipo = j.id_equipo_1 OR eg.id_equipo = j.id_equipo_2) AND j.id_fase=".$idFase." AND j.fecha is not null and j.informado =1 ORDER by nombreGrupo asc, j.fecha desc" );
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-resultados" >
<thead>
	<tr>
		<!--th>Grupo</th -->
		<th>Local</th>
		<th>Goles</th>
		<th>Visitante</th>
		<th>Goles</th>
		<th>Fecha</th>
	</tr>
</thead>
<tbody>
<?php
$grupoNameAux = "";
while($row = mysqli_fetch_array($resultResultados)){
	$date_a = "";
	if(!empty($row['fecha'])){
		$date_a = new DateTime($row['fecha']);
		$date_a = $date_a->format('d/m/Y');
	}	
	$goles1 = mysqli_fetch_array( $connect->query("select sum(g.valor) totalGoles from gol g JOIN jugador ju ON g.id_jugador = ju.id AND g.id_juego=".$row['id']." JOIN equipo e ON ju.id_equipo = e.id AND e.id =".$row['id_equipo_1']));	
	$goles2 = mysqli_fetch_array( $connect->query("select sum(g.valor) totalGoles from gol g JOIN jugador ju ON g.id_jugador = ju.id AND g.id_juego=".$row['id']." JOIN equipo e ON ju.id_equipo = e.id AND e.id =".$row['id_equipo_2']));
?>	
<tr>	
	<!--td>
		<strong><?php //if($grupoNameAux != $row['nombreGrupo']){ echo $row['nombreGrupo']; $grupoNameAux = $row['nombreGrupo']; }?></strong>
	</td-->			
	<td>
		<?php echo $row['nombre1']; ?>
	</td>	
	<td style="font-weight: bold;">
		<?php echo !empty($goles1['totalGoles'])?$goles1['totalGoles']:0; ?>
	</td>
	<td>
		<?php echo $row['nombre2']; ?>
	</td>	
	<td style="font-weight: bold;">
		<?php echo !empty($goles2['totalGoles'])?$goles2['totalGoles']:0; ?>
	</td>		
	<td>
		<?php echo $date_a; ?>
	</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script>
$(document).ready(function () {
$('.dataTables-resultados').DataTable({
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