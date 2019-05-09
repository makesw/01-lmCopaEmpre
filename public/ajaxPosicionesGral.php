<?php 
require '../conexion.php';
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
$resultTableGral = $connect->query( "select eg.id_equipo, e.nombre, sum(eg.JUG) JUG, sum(eg.GAN) GAN, sum(eg.EMP) EMP, sum(eg.PER) PER, sum(eg.GAF) GAF, sum(eg.GEC) GEC, 
sum(eg.DIF) DIF, sum(eg.PTS) PTS from equipo_grupo eg join grupo g on eg.id_grupo = g.id join fase f on g.id_fase = f.id join competicion c on f.id_competicion = c.id and( c.id = ".$idComp." or c.id_parent = ".$idComp.") 
join equipo e on eg.id_equipo = e.id group by id_equipo order by PTS desc, DIF desc" );
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
?>
Criterios: POS=Posición, JUG=Jugados, GAN=Ganados, EMP=Empatados, PER=Perdidos, GAF=Goles a Favor, GEC=Goles en Contra, DIF=Difrencia Goles, PTS=Puntos
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-tableGral" >
<thead>
	<tr>
		<th>POS</th>
		<th>Equipo</th>
		<th>JUG</th>
		<th>GAN</th>
		<th>EMP</th>
		<th>PER</th>
		<th>GAF</th>
		<th>GEC</th>
		<th>DIF</th>
		<th>PTS</th>		
	</tr>
</thead>
<tbody>
<?php 
	$iter = 1;
	while($row = mysqli_fetch_array($resultTableGral)){
?>	
	<tr>	
		<td><?php echo $iter; $iter++;?></td>
		<td><?php echo $row['nombre']; ?></td>
		<td><?php echo $row['JUG']; ?></td>
		<td><?php echo $row['GAN']; ?></td>
		<td><?php echo $row['EMP']; ?></td>
		<td><?php echo $row['PER']; ?></td>
		<td><?php echo $row['GAF']; ?></td>
		<td><?php echo $row['GEC']; ?></td>
		<td><?php echo $row['DIF']; ?></td>
		<td><strong><?php echo $row['PTS']; ?></strong></td>					
	</tr>												 
<?php } ?>
</tbody>
</table>
</div>
<script>
$(document).ready(function () {
$('.dataTables-tableGral').DataTable({
	"searching": false,
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