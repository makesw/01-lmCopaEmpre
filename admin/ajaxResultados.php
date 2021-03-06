<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
    header( 'Location: ../index.html' );
}else{
    if($_SESSION[ 'dataSession' ]['perfil'] != 'admin'){
        header( 'Location: ../salir.php' );
    }
}
require '../conexion.php';
$iidFase = $_GET[ 'idFase' ];
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
$resultResultados = $connect->query( "select distinct j.id, j.id_equipo_1,j.nombre1,j.id_equipo_2,nombre2, j.fecha, g.nombre nombreGrupo from juego j 
join competicion c ON j.id_competicion = c.id and c.id = ".$idComp." AND j.fecha is not null and j.informado =1 
join fase f on j.id_fase = f.id and f.id = ".$iidFase." left join grupo g on j.id_grupo = g.id ORDER by g.nombre asc, j.fecha desc" );

setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-resultados" >
<thead>
	<tr>
		<!--th>Grupo</th-->
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
	</td -->			
	<td>
		<?php echo $row['nombre1']; ?>
	</td>	
	<td>
		<?php echo !empty($goles1['totalGoles'])?$goles1['totalGoles']:0; ?>
	</td>
	<td>
		<?php echo $row['nombre2']; ?>
	</td>	
	<td>
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
	 },
	dom: '<"html5buttons" B>lTfgitp',
		buttons: [				
			{
				extend: 'excelHtml5',
				exportOptions: {
					columns: ':visible'
				}
			},
			{
				extend: 'pdfHtml5',
				exportOptions: {
					columns: ':visible'
				}
			}
		]
});
});
</script>
<?php $connect->close(); ?>