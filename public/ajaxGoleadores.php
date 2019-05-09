<?php 
require '../conexion.php';
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
$resultGolesCompeticion = $connect->query( "select concat(ju.nombres,' ',ju.apellidos) nombres, e.nombre, ju.url_foto, g.id_jugador, sum(g.valor) goles from gol g join juego j on g.id_juego = j.id join fase f on j.id_fase = f.id and f.id_competicion = ".$idComp." join jugador ju on g.id_jugador = ju.id join equipo e on ju.id_equipo = e.id and e.id_competicion=".$idComp." group by g.id_jugador order by goles desc" );
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
	"oLanguage": {
	   "sSearch": "Buscar: "
	 }
});
});
</script>
<?php $connect->close(); ?>