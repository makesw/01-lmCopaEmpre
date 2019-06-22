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
$arrayData = array();
$arrayDataOrder = array();
$matrizEmpateTemp = array();
$idsEquposEmpateTemp = array();
$empate = false;
$persisteEmpate = false;
$idFase = 1;
$idComp = 1;
$idGrupo = 1;
$criterio ='';
$operador ='menor';
//lista normal:
//$result = $connect->query( "select c.id idComp, c.nombre nombreComp, f.id idFase, f.nombre nombreFase, g.nombre nombreGrupo, e.nombre nombreEqui, eg.* from competicion c join fase f on c.id = f.id_competicion and c.id = 1 and f.id = 1 join grupo g on f.id = g.id_fase and g.id = 1 join equipo_grupo eg on g.id = eg.id_grupo join equipo e on eg.id_equipo = e.id order by eg.pts desc" );
$result = $connect->query( "select eg.id_equipo, eg.PTS, eg.DIF,eg.GAF,eg.GEC from competicion c join fase f on c.id = f.id_competicion and c.id = 1 and f.id = 1 join grupo g on f.id = g.id_fase and g.id = 1 join equipo_grupo eg on g.id = eg.id_grupo join equipo e on eg.id_equipo = e.id order by eg.pts desc" );
//generar arreglo de datos:
while($row = mysqli_fetch_array($result)){
	$arrayData [] = $row;
}
//Realizar proceso de desempates:
for($i=0; $i<count($arrayData); $i++){
	$matrizEmpateTemp = array();	
	for($j=$i+1; $j<count($arrayData); $j++){		
		if( $arrayData[$i]['PTS'] == $arrayData[$j]['PTS'] ){ //equipos empatados en i puntos
			array_push($matrizEmpateTemp, $arrayData[$j]);
			$idsEquposEmpateTemp [] = $arrayData[$j]['id_equipo'];
			$empate = true;
		}
	}
	if($empate){//desempatar equipo empatados en i puntos
		//adicionar i a lista de empates:
		array_push($matrizEmpateTemp, $arrayData[$i]);
		//adicionar i a lista de solo ids
		$idsEquposEmpateTemp [] = $arrayData[$i]['id_equipo'];		
		//ordenar por criterios de desempate de la liga
		ordenarPorCriterios();
		//adicionar $matrizEmpateTemp a la lista de ordenamiento
		adicionarOrdenados();		
		$empate = false;
		//salatar contador i:
		$i = $i+count($matrizEmpateTemp)-1;
	}else{//adicionar i a la lista de ordenamiento
		array_push($arrayDataOrder,$arrayData[$i]);
	}
	
}
function ordenarPorCriterios(){
	global $matrizEmpateTemp;
	global $criterio;
	global $operador;
	global $idComp;
	global $idFase;
	global $idGrupo;
	global $idsEquposEmpateTemp;
	global $persisteEmpate;
	$antidadEmpatados = count($matrizEmpateTemp);	
	//1.Por mayor número de puntos obtenidos entre los enfrentamientos directos de los equipos en cuestión.	
	calcularPuntosDirectos( $idFase, $idGrupo,$idsEquposEmpateTemp );
	$criterio = "ptsDirect"; $operador = "menor";
	ordenarPorCriterio($criterio,$matrizEmpateTemp);	
	if( $persisteEmpate ){
		$persisteEmpate = false;
		calcularJuegoLimpio( $idComp );
		//2.Quien presente el mejor promedio en el juego limpio.
		$criterio = "ptsJl"; $operador = "mayor";
		ordenarPorCriterio($criterio,$matrizEmpateTemp);
	}
	if( $persisteEmpate ){
		$persisteEmpate = false;
		//3.Quién presente mayor puntaje en el gol diferencia.
		$criterio = "DIF"; $operador = "menor";
		ordenarPorCriterio($criterio,$matrizEmpateTemp);
	}
	if( $persisteEmpate ){
		$persisteEmpate = false;
		//4.Quién tenga mayor número de goles a favor.
		$criterio = "GAF"; $operador = "menor";
		ordenarPorCriterio($criterio,$matrizEmpateTemp);
	}
	if( $persisteEmpate ){
		$persisteEmpate = false;
		//5.Quién tenga menos goles en contra.
		$criterio = "GEC"; $operador = "mayor";
		ordenarPorCriterio($criterio,$matrizEmpateTemp);
	}
}
function calcularPuntosDirectos( $idFase, $idGrupo,$idsEquposEmpateTemp ){
	require '../conexion.php';
	global $matrizEmpateTemp;
	$idsEquiposPorComa = implode(",", $idsEquposEmpateTemp);
	for($i=0; $i<count($matrizEmpateTemp); $i++){
		//--contar ganados
		$totalPuntosG = mysqli_fetch_array( $connect->query( "select (count(1) * 3) total from juego where id_grupo = ".$idGrupo." and id_fase = ".$idFase." and (id_equipo_1 in(".$idsEquiposPorComa.") and id_equipo_2 in(".$idsEquiposPorComa.")) and (id_ganador = ".$matrizEmpateTemp[$i]['id_equipo'].")" ) );
		$totalPuntosE = mysqli_fetch_array( $connect->query( "select (count(1) * 1) total from juego where id_grupo = ".$idGrupo." and id_fase = ".$idFase." and (id_equipo_1 in(".$idsEquiposPorComa.") and id_equipo_2 in(".$idsEquiposPorComa.")) and (id_ganador = 0 and(id_equipo_1 =".$matrizEmpateTemp[$i]['id_equipo']." or id_equipo_2 = ".$matrizEmpateTemp[$i]['id_equipo']."))" ) );		
		$matrizEmpateTemp[$i]['ptsDirect'] = ($totalPuntosG['total']+$totalPuntosE['total']);
	}	
}
function calcularJuegoLimpio( $idComp ){
	require '../conexion.php';
	global $matrizEmpateTemp;
	global $matrizEmpateTemp;
	for($i=0; $i<count($matrizEmpateTemp); $i++){
		//--contar puntos juego limpio en la comeptencia del equipo
		$totalPuntosJL = mysqli_fetch_array( $connect->query( "select sum(ts.puntos) total from sancion s join juego j on s.id_juego = j.id and (j.id_equipo_1 =  ".$matrizEmpateTemp[$i]['id_equipo']." or j.id_equipo_2 = ".$matrizEmpateTemp[$i]['id_equipo'].") join fase f on j.id_fase = f.id join competicion c on f.id_competicion = c.id and c.id = ".$idComp." join tipo_sancion ts on s.id_tipo_sancion = ts.id join jugador jug on jug.id = s.id_jugador and jug.id_equipo =".$matrizEmpateTemp[$i]['id_equipo'] ) );
		$matrizEmpateTemp[$i]['ptsJl'] = $totalPuntosJL['total'];
	}	
}
function adicionarOrdenados(){
	global $matrizEmpateTemp;
	global $arrayDataOrder;
	for( $i = 0; $i < count($matrizEmpateTemp); $i++){
		array_push($arrayDataOrder,$matrizEmpateTemp[$i]);
	}
	
}
function ordenarPorCriterio( $criterio, $matrizEmpateTemp ){
	global $matrizEmpateTemp;
	global $operador;
	global $persisteEmpate;
	for( $i = 0; $i < count($matrizEmpateTemp) - 1; $i++)
        {
            for($j = 0; $j < count($matrizEmpateTemp) - 1; $j++)
            {
				if( $operador == "menor"){
					if ($matrizEmpateTemp[$j][$criterio] < $matrizEmpateTemp[$j + 1][$criterio])
					{
						$tmp = $matrizEmpateTemp[$j+1];
						$matrizEmpateTemp[$j+1] = $matrizEmpateTemp[$j];
						$matrizEmpateTemp[$j] = $tmp;					
					}else if ($matrizEmpateTemp[$j][$criterio] == $matrizEmpateTemp[$j + 1][$criterio]){
						$persisteEmpate = true;
					}
				}else if($operador == "mayor"){
					if ($matrizEmpateTemp[$j][$criterio] > $matrizEmpateTemp[$j + 1][$criterio])
					{
						$tmp = $matrizEmpateTemp[$j+1];
						$matrizEmpateTemp[$j+1] = $matrizEmpateTemp[$j];
						$matrizEmpateTemp[$j] = $tmp;					
					}else if ($matrizEmpateTemp[$j][$criterio] == $matrizEmpateTemp[$j + 1][$criterio]){
						$persisteEmpate = true;
					}
				}
            }
	}
}
print_r($arrayDataOrder);
?>