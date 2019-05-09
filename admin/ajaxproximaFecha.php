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
$idComp=0;
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
$arrayProxFecha = array();

//Juegos Oficiales
$resultJuegos = $connect->query( "select j.*, e.nombre nombreEscena, g.nombre nombreGrupo, j.tipo from juego j join escenario e ON j.id_escenario = e.id and j.fecha >= NOW() join fase f on j.id_fase = f.id and f.id_competicion =".$idComp." join grupo g on j.id_grupo = g.id order by j.fecha asc, hora_inicio asc" );
//Adicionar al arreglo los juegos oficiales programados:
while($row = mysqli_fetch_array($resultJuegos)){
	$arrayProxFecha [] = $row;
}
//Adicionar al arreglo los juegos oficiales Aplazados:
$resultJuegos = $connect->query( "select distinct j.*, '' nombreEscena, g.nombre nombreGrupo, j.tipo from juego j join grupo g on j.id_grupo = g.id and j.aplazado = 1 and j.id_competicion = ".$idComp." order by j.fecha asc, hora_inicio asc" );
//Adicionar al arreglo los juegos aplazados:
while($row = mysqli_fetch_array($resultJuegos)){
    $arrayProxFecha [] = $row;
}
//Adicionar al arreglo los juegos Juegos Amistosos:
$resultJuegos = $connect->query( "select j.*, e.nombre nombreEscena, '' nombreGrupo, j.tipo from juego j join escenario e ON j.id_escenario = e.id and j.fecha >= NOW() and j.tipo = 'AMISTOSO' and j.id_competicion =".$idComp." order by j.fecha asc, hora_inicio asc" );
//Adicionar al arreglo los juegos amistosos:
while($row = mysqli_fetch_array($resultJuegos)){
	$arrayProxFecha [] = $row;
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
for($i=0; $i<count($arrayProxFecha); $i++){
	$teamA;
	$teamB;
	if($arrayProxFecha[$i]['id_equipo_1']!=null){
		$teamA = mysqli_fetch_array($connect->query( "select * from equipo where id=".$arrayProxFecha[$i]['id_equipo_1']));
	}
	if($arrayProxFecha[$i]['id_equipo_2']!=null){
		$teamB = mysqli_fetch_array($connect->query( "select * from equipo where id=".$arrayProxFecha[$i]['id_equipo_2']));
	}
	$date_a = "";
	if(!empty($arrayProxFecha[$i]['fecha'])){
		$date_a = strftime("%A, %d de %b %Y",strtotime($arrayProxFecha[$i]['fecha']));
	}
	?>
		<tr>	
			<td>
				<?php echo $arrayProxFecha[$i]['nombreGrupo']; ?>
			</td>			
			<td>
				<?php echo $arrayProxFecha[$i]['nombre1']; ?>
			</td>
			<td>
				<div style="text-align: center;width: 50%;background-color:<?php echo $teamA['color']!=null?$teamA['color']:''; ?>; height: 15px;"></div>
			</td>
			<td>
				Vs
			</td>
			<td>
				<?php echo $arrayProxFecha[$i]['nombre2']; ?>
			</td>
			<td>
				<div style="text-align: center;width: 50%;background-color:<?php echo  $teamB['color']!=null?$teamB['color']:''; ?>; height: 15px;"></div>
			</td>
			<td>
				<?php 
					echo $arrayProxFecha[$i]['aplazado']==1?'APLAZADO':$arrayProxFecha[$i]['hora_inicio']; ?>				  
			</td>
			<td>
				<?php echo $arrayProxFecha[$i]['hora_fin']; ?>
			</td>
			<td>
				<?php echo $arrayProxFecha[$i]['nombreEscena']; ?>
			</td>
			<td>
				<?php echo $arrayProxFecha[$i]['aplazado']==1?'APLAZADO':utf8_encode($date_a); ?>
			</td>
			<td>
				<?php echo $arrayProxFecha[$i]['tipo']; ?>
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