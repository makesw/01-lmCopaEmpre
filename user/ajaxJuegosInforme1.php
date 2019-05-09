<?php 
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
require '../conexion.php';
$idFase = $_GET[ 'idFase' ];
$idUser = $_SESSION[ 'dataSession' ]['id'];
$idComp = $_GET[ 'idComp' ];
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
$equipo = mysqli_fetch_array($connect->query("select * from equipo where id_usuario = ".$idUser." limit 1"));
$resultJuegos = $connect->query("select j.*, g.nombre nombreGrupo from juego j join fase f on j.id_fase = f.id and f.id = ".$idFase." and j.informado =1 and (j.id_equipo_1 = ".$equipo['id']." OR j.id_equipo_2 = ".$equipo['id'].") join grupo g on j.id_grupo = g.id order by j.fecha desc");
?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-juegos" >
<thead>
	<tr>
		<th>#</th>
		<th>GRUPO</th>
		<th>LOCAL</th>
		<th></th>
		<th>VISITANTE</th>
		<th>FECHA</th>
		<th></th>
	</tr>
</thead>
<tbody>
<?php 
$iter = 1;
while($rowJuego = mysqli_fetch_array($resultJuegos)){
	$date_a = "";
	if(!empty($rowJuego['fecha'])){
		$date_a = new DateTime($rowJuego['fecha']);
		$date_a = $date_a->format('Y-m-d');
	}
	?>
		<tr>
			<td>
				<?php echo $iter; ?>
			</td>
			<td>
				<?php echo $rowJuego['nombreGrupo']; ?>
			</td>
			<td>
				<?php echo $rowJuego['nombre1']; ?>
			</td>			
			<td>
				Vs
			</td>
			<td>
				<?php echo $rowJuego['nombre2']; ?>
			</td>			
			<td>
				<?php echo $date_a; ?>
			</td>
			<td>
				<a href="informe_2.php?idJuego=<?php echo $rowJuego['id']; ?>&idFase=<?php echo $idFase; ?>&idComp=<?php echo $idComp; ?>">
					<button class="btn btn-success btn-outline" type="button">Ver</button>
				</a>
			</td>			
		</tr>
<?php $iter++; } ?>
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
	"pageLength": 20
});
});
</script>
<?php $connect->close(); ?>