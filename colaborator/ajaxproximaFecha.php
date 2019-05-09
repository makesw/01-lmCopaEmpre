<?php 
require '../conexion.php';
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
$resultJuegos = null;
if( $idComp != null && $idComp> 0){
	//Juegos Oficiales
	$resultJuegos = $connect->query( "select j.*, e.nombre nombreEscena, g.nombre nombreGrupo, j.tipo from juego j join escenario e ON j.id_escenario = e.id and j.fecha >= NOW() join fase f on j.id_fase = f.id and f.id_competicion =".$idComp." join grupo g on j.id_grupo = g.id
	order by j.fecha asc" );
}else{
	//Juegos Amistosos:
	$resultJuegos = $connect->query( "select j.*, e.nombre nombreEscena, '' nombreGrupo, j.tipo from juego j join escenario e ON j.id_escenario = e.id and j.fecha >= NOW() and j.tipo = 'AMISTOSO' 
	order by j.fecha asc" );
}


setlocale (LC_ALL,"spanish");
setlocale(LC_ALL,'es_ES');
header('Content-Type: text/html; charset=utf-8');
?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-juegos" >
<thead>
	<tr>
		<th>GRUPO</th>		
		<th>LOCAL</th>
		<th>COLOR</th>
		<th></th>
		<th>VISITANTE</th>
		<th>COLOR</th>
		<th>HORA INICIO</th>
		<th>HORA FIN</th>
		<th>ESCENARIO</th>
		<th>FECHA</th>
		<th>TIPO</th>
	</tr>
</thead>
<tbody>
<?php 
$iter = 1;
while($row = mysqli_fetch_array($resultJuegos)){
	$teamA = mysqli_fetch_array($connect->query( "select * from equipo where id=".$row['id_equipo_1']));
	$teamB = mysqli_fetch_array($connect->query( "select * from equipo where id=".$row['id_equipo_2']));
	$date_a = "";
	if(!empty($row['fecha'])){
		$date_a = strftime("%A, %d de %b %Y",strtotime($row['fecha']));
	}
	?>
		<tr>	
			<td>
				<?php echo $row['nombreGrupo']; ?>
			</td>			
			<td>
				<?php echo $row['nombre1']; ?>
			</td>
			<td>
				<div style="text-align: center;width: 50%;background-color:<?php echo $teamA['color']; ?>; height: 15px;"></div>
			</td>
			<td>
				Vs
			</td>
			<td>
				<?php echo $row['nombre2']; ?>
			</td>
			<td>
				<div style="text-align: center;width: 50%;background-color:<?php echo $teamB['color']; ?>; height: 15px;"></div>
			</td>
			<td>
				<?php echo $row['hora_inicio']; ?>				  
			</td>
			<td>
				<?php echo $row['hora_fin']; ?>
			</td>
			<td>
				<?php echo $row['nombreEscena']; ?>
			</td>
			<td>
				<?php echo utf8_encode($date_a); ?>
			</td>
			<td>
				<?php echo $row['tipo']; ?>
			</td>		
		</tr>
<?php } ?>
</tbody>
</table>
</div>
<script>
$(document).ready(function () {
$('.dataTables-juegos').DataTable({
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