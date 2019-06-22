<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
    header( 'Location: ../index.html' );
}else{
    if($_SESSION[ 'dataSession' ]['perfil'] != 'colaborador'){
        header( 'Location: ../salir.php' );
    }
}
require '../conexion.php';
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');

$resultJuegos = $connect->query( "select * from juego where tipo='AMISTOSO'" );

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

?>
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
			<td>
				<div class="btn-group">
					  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Acciones <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu">
						<li><a href="javascript:fnProgramar(<?php echo $rowJuego['id']; ?>,'<?php echo $rowJuego['nombre1']; ?>','<?php echo $rowJuego['nombre2']; ?>');">Programar</a></li>
					  	<!--li><a href="javascript:fnAplazar(<?php echo $rowJuego['id']; ?>);">Aplazar</a></li --> 
						<li><a href="javascript:fnClearProg(<?php echo $rowJuego['id']; ?>);">Limpiar Progrmación</a></li>
						<!-- li><a href="javaScript:delGame('<?php echo $rowJuego['id']; ?>');">Eliminar Juego</a></li -->						
					  </ul>
				</div>				
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
	"pageLength": 20,
	"oLanguage": {
	   "sSearch": "Buscar: "
	 }
});
});
</script>
<?php $connect->close(); ?>