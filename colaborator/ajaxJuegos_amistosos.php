<?php 
require '../conexion.php';
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');

$resultJuegos = $connect->query( "select * from juego where tipo='AMISTOSO'" );
?>
<button class="btn btn-success" type="button" data-toggle="modal" data-target="#modal-agregar" disabled>Agregar Juego</button>
<button class="btn btn-danger" type="button" onClick="javascript:delJuegos();" disabled>Borrar Juegos</button>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-juegos" >
<thead>
	<tr>
		<th>#</th>
		<th>LOCAL</th>
		<th></th>
		<th>VISITANTE</th>	
		<th>HORA INICIO</th>
		<th>HORA FIN</th>
		<th>ESCENARIO</th>
		<th>FECHA</th>
		<!--th></th -->
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
	//find escenario:
	$escena = "";
	if(!empty($rowJuego['id_escenario'])){
		$resultEscena = $connect->query( "select * from escenario where id =".$rowJuego['id_escenario'] );
		$escenaObject = mysqli_fetch_array($resultEscena);
		$escena = $escenaObject['nombre'];		
	}
	?>
		<tr>
			<td>
				<?php echo $iter; ?>
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
				<?php echo $rowJuego['hora_inicio']; ?>				  
			</td>
			<td>
				<?php echo $rowJuego['hora_fin']; ?>
			</td>
			<td>
				<?php echo $escena; ?>
			</td>
			<td>
				<?php echo $date_a; ?>
			</td>
			<!--td>
				<button class="btn btn-success btn-outline" type="button" onClick="javascript:fnProgramar(<?php echo $rowJuego['id']; ?>,'<?php echo $rowJuego['nombre1']; ?>','<?php echo $rowJuego['nombre2']; ?>');">Programar</button>
				<button class="btn btn-warning btn-outline" type="button" onClick="javascript:fnClearProg(<?php echo $rowJuego['id']; ?>);">Limpiar</button>
				<a title="Borrar" href="javaScript:delGame('<?php echo $rowJuego['id']; ?>');">
					<i class="icon-cancel icon-larger red-color"></i>
				</a>
			</td -->			
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