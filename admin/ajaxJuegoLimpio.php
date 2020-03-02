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
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:0;
$idFase = isset($_GET[ 'idFase' ])?$_GET[ 'idFase' ]:0;
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
//Consutar equipos de la competiciòn:
$resultEquipoPuntos = $connect->query( "select e.id, e.nombre, f.nombre fase, sum(ts.puntos) pstJL from juego j join fase f on j.id_fase = f.id and 
f.id_competicion = ".$idComp." and  j.id_fase = ".$idFase." and j.informado = 1 join sancion s on j.id = s.id_juego join tipo_sancion ts on s.id_tipo_sancion = ts.id join 
jugador ju on s.id_jugador = ju.id join equipo e on ju.id_equipo = e.id group by e.id order by pstJL desc");
//generar arreglo EquipoPuntos:
$arrayData = array();
while($row = mysqli_fetch_array($resultEquipoPuntos)){
	$promedioJl = 0;
	// por cada equipo contar partido jugados:	
	$resultJuegos = mysqli_fetch_array($connect->query( "select count(1) juegos from juego j join equipo e on (j.id_equipo_1 = e.id or j.id_equipo_2 = e.id) 
    and e.id = ".$row['id']." and j.informado =1 join fase f on j.id_fase = f.id and j.id_fase=".$idFase." and f.id_competicion =".$idComp));
	if( $resultJuegos['juegos']>0){
		$promedioJl = $row['pstJL'] / $resultJuegos['juegos'];
	}
	$arrayRow = array( "id" => $row['id'],"fase" => $row['fase'], "nombreEquipo" =>  $row['nombre'],"juegos" =>  $resultJuegos['juegos'],
				  "pstJL" =>  $row['pstJL'],"prom" =>  $promedioJl);
	array_push($arrayData,$arrayRow);
}

ordenarArrayData( $arrayData );

function ordenarArrayData( $arrayData ){
	global $arrayData;
	for( $i = 0; $i < count($arrayData) - 1; $i++)
        {
            for($j = 0; $j < count($arrayData) - 1; $j++)
            {				
				if ($arrayData[$j]['prom'] < $arrayData[$j + 1]['prom'])
				{				
					$tmp = $arrayData[$j+1];
					$arrayData[$j+1] = $arrayData[$j];
					$arrayData[$j] = $tmp;
				}
            }
	}
}

?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-JL" >
<thead>
	<tr>
		<th>Fase</th>
		<th>Equipo</th>
		<th>JUG</th>
		<th>PTS Negativos</th>
		<th>Promedio</th>
	</tr>
</thead>
<tbody>
<?php
for($i=0; $i<count($arrayData); $i++){
?>	
<tr style="<?php if($i == 0 ){echo "background-color: #bbebba;"; } ?>">
	<td> <?php echo $arrayData[$i]['fase']; ?>	</td>	
	<td> <?php echo $arrayData[$i]['nombreEquipo']; ?>	</td>
	<td> <?php echo $arrayData[$i]['juegos']; ?>	</td>
	<td> <?php echo $arrayData[$i]['pstJL']; ?>	</td>
	<td> <strong><?php echo round ($arrayData[$i]['prom'],2); ?></strong>	</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script>
$(document).ready(function () {
$('.dataTables-JL').DataTable({
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