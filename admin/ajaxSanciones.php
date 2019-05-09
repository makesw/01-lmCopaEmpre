<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
    header( 'Location: ../index.html' );
}else{
    if($_SESSION[ 'dataSession' ]['perfil'] != 'admin'){
        header( 'Location: ../salir.php' );
    }
}
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
require '../conexion.php';
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
$resultSanciones = $connect->query( "select  e.id_usuario, concat(ju.nombres,' ',ju.apellidos) nombres,ju.url_foto,ju.documento,e.nombre nombreEqui, ts.nombre nombreTs, ts.fechas_suspencion, s.*, ts.puntos pts FROM sancion s join juego j on s.id_juego = j.id join fase f on j.id_fase= f.id and f.id_competicion = ".$idComp." join jugador ju on s.id_jugador = ju.id and ju.activo is null join equipo e on ju.id_equipo = e.id join tipo_sancion ts on s.id_tipo_sancion = ts.id order by s.fecha desc" );
$competicion = mysqli_fetch_array($connect->query( "select * from competicion where id =".$idComp));
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-sanciones" >
<thead>
	<tr>
		<th></th>
		<th>Documento</th>
		<th>Nombres</th>
		<th>Equipo</th>
		<th>Sancion</th>
		<th>Fechas Sancion</th>
		<th>Puntos</th>
		<th>Fecha</th>
		<th></th>
	</tr>
</thead>
<tbody>
<?php
while($row = mysqli_fetch_array($resultSanciones)){
    $date_f = new DateTime($row['fecha']);
?>	
<tr>	
	<td class="size-80"><img title="" alt="" src="<?php echo $row['url_foto'];?>" class="avatar img-circle"></td>
	<td> <?php echo $row['documento']; ?>	</td>
	<td> <?php echo $row['nombres']; ?>	</td>
	<td> <?php echo $row['nombreEqui']; ?>	</td>
	<td> <strong><?php echo $row['nombreTs']; ?></strong>	</td>
	<td> <strong><?php echo $row['fechas_suspencion']; ?></strong>	</td>
	<td> <strong><?php echo $row['pts']; ?></strong>	</td>
	<td> <?php echo $date_f->format('d-m-Y'); ?>	</td>
	<td><a title="Borrar Sanción" href="javaScript:delSan(<?php echo($row['id']) ?>);">
		<i class="icon-cancel icon-larger red-color"></i></a>
	</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script>
$(document).ready(function () {
$('.dataTables-sanciones').DataTable({
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
				title: '<?php echo 'Sancionados-'.$competicion['nombre']; ?>',
				exportOptions: {
					columns: [ 1, 2, 3, 4, 5, 6 ]
				}
			},
			{
				extend: 'pdfHtml5',
				title: '<?php echo 'Sancionados-'.$competicion['nombre']; ?>',
				exportOptions: {
					columns: [ 1, 2, 3, 4, 5, 6 ]
				}
			}
		]
});
});
function delSan( id ) {
	if ( confirm( 'Confirma Eliminar?' ) ) {
		$.ajax( {
			url: 'server.php?action=delSan&id=' + id,
			type: 'POST',
			data: new FormData( this ),
			success: function ( data ) {
				//console.log( data );
				location.href = './sancionados.php?idCompReload=<?php echo($idComp) ?>';
			},
			error: function ( data ) {
				//console.log( data );
			},
			cache: false,
			contentType: false,
			processData: false
		} );
	}
}
</script>
<?php $connect->close(); ?>