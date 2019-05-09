<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
    header( 'Location: ../index.html' );
}else{
    if($_SESSION[ 'dataSession' ]['perfil'] != 'admin'){
        header( 'Location: ../salir.php' );
    }
}
$arrayFases = array();
require '../conexion.php';

if(isset($_GET[ 'opt' ]) && $_GET[ 'opt' ]==1 ){
	
$resultInscripciones = $connect->query( "select i.*,e.nombre nombreEquipo, e.color, c.nombre nombreCompeticion from inscripcion i JOIN competicion c ON i.id_competicion = c.id and i.id_competicion=".$_GET["idComp"]." JOIN equipo e ON i.id_equipo =e.id" );	
?>
<div class="table-responsive">
	<table class="table table-bordered table-hover dataTables-inscritos" >
		<thead>
			<tr>
				<th>#</th>
				<th>Nombre</th>
				<th>Color</th>
				<th>Competición</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php $iter = 1;
			while($row = mysqli_fetch_array($resultInscripciones)){
			?>
			<tr>
				<td>
					<?php echo $iter; ?>
				</td>
				<td>
					<?php echo $row['nombreEquipo']; ?>
				</td>
				<td>
					<div style="text-align: center;width: 50%;background-color:<?php echo $row['color']; ?>; height: 15px;"></div>
				</td>
				<td>
					<?php echo $row['nombreCompeticion']; ?>
				</td>																	
				<td>												
					<a title="Borrar" href="javaScript:delInscrip('<?php echo $row['id']; ?>');">
						<i class="icon-cancel icon-larger red-color"></i>
					</a>												
				</td>											
			</tr>
			<?php $iter++; } ?>
		</tbody>
	</table>
</div>
<?php } ?>
<script>
$(document).ready(function () {
$('.dataTables-inscritos').DataTable({
	"searching": true,
	"bSort" : false,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 20,
	"oLanguage": {
	   "sSearch": "Buscar: "
	 },
	dom: '<"html5buttons" B>lTfgitp',
		buttons: [				
			{
				extend: 'excelHtml5',
				title: 'ListaInscritos',				
				exportOptions: {
					columns: ':visible'
				}
			},
			{
				extend: 'pdfHtml5',
				title: 'ListaInscritos',
				exportOptions: {
					columns: ':visible'
				}
			}
		]
});
});
</script>