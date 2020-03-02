<?php 
require '../conexion.php';
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:0;
$idFase = isset($_GET[ 'idFase' ])?$_GET[ 'idFase' ]:0;
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
$resultEquipos = $connect->query( "select * from equipo where id_competicion = ".$idComp);
$arrayData = array();
$arrayDataCero = array();
$arrayFinal = array();
//Recorrer equipos calculando juego limpio de la fase seleccionada:
while($row = mysqli_fetch_array($resultEquipos)){
    $promedioJl = 0;
    $cantidadJuegos = mysqli_fetch_array($connect->query( "select count(1) total, f.nombre fase from juego j join equipo e
    on (j.id_equipo_1 = e.id or j.id_equipo_2 = e.id) and e.id = ".$row['id']." and j.informado =1 join fase f
    on j.id_fase = f.id and j.id_fase=".$idFase." and f.id_competicion =".$idComp));
    
    $juegoLimpio = mysqli_fetch_array($connect->query( "select sum(ts.puntos) pstJL from juego j join equipo e
    on (j.id_equipo_1 = e.id or j.id_equipo_2 = e.id) and e.id = ".$row['id']." and j.id_competicion = ".$idComp." and j.informado =1 join fase f
    on j.id_fase = f.id and j.id_fase=".$idFase."  join sancion s on j.id = s.id_juego join tipo_sancion ts on s.id_tipo_sancion = ts.id join
    jugador ju on s.id_jugador = ju.id and ju.id_equipo = ".$row['id']." order by pstJL desc"));
    
    if( $cantidadJuegos['total'] > 0){
        $promedioJl = $juegoLimpio['pstJL'] / $cantidadJuegos['total'];
        $arrayRow = array( "id" => $row['id'],"fase" => $cantidadJuegos['fase'], "nombreEquipo" =>  $row['nombre'],"juegos" =>  $cantidadJuegos['total'],
            "pstJL" =>  !empty($juegoLimpio['pstJL'])?$juegoLimpio['pstJL']:0,"prom" =>  $promedioJl);
        array_push($arrayData,$arrayRow);
    }else{
        $arrayRow = array( "id" => $row['id'],"fase" => '', "nombreEquipo" =>  $row['nombre'],"juegos" =>  '0',
            "pstJL" =>  '0',"prom" =>  '0');
        array_push($arrayDataCero,$arrayRow);
    }
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
$arrayFinal = array_merge($arrayData,$arrayDataCero);
?>
Tabla por fase:
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
for($i=0; $i<count($arrayFinal); $i++){
?>	
<tr style="<?php if($i == 0 ){echo "background-color: #bbebba;"; } ?>">
	<td> <?php echo $arrayFinal[$i]['fase']; ?>	</td>
	<td> <?php echo $arrayFinal[$i]['nombreEquipo']; ?>	</td>
	<td> <?php echo $arrayFinal[$i]['juegos']; ?>	</td>
	<td> <?php echo $arrayFinal[$i]['pstJL']; ?>	</td>
	<td> <strong><?php echo round ($arrayFinal[$i]['prom'],2); ?></strong>	</td>
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
	 }
});
});
</script>
<?php $connect->close(); ?>