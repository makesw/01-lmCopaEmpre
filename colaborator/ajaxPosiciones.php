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
$idFase = $_GET[ 'idFase' ];
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
$arrayData = array();
$arrayDataOrder = array();
$arrayDataOrderFinal = array();
$matrizEmpateTemp = array();
$idsEquposEmpateTemp = array();
$empate = false;
$persisteEmpate = false;
$criterio ='';
$operador ='menor';
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
$grue = isset($_GET[ 'grue' ]);
if( $grue == 1 ){
	//consultar datos la fase nueva:
	$fase = mysqli_fetch_array($connect->query("select * from fase where id=".$idFase));
	if ( $fase['numero'] > 1 ){//consultar tabla de la fase anterior		
		$faseAnterior = mysqli_fetch_array($connect->query("select * from fase where numero=".($fase['numero']-1)." and id_competicion=".$idComp));	
		$idFase = $faseAnterior['id'];
		$resultGrupos = $connect->query( "select g.* from fase f join grupo g on f.id = g.id_fase and f.id =".$idFase." order by g.nombre asc" );
		?>
		<strong>Criterios</strong>: JUG=Jugados, GAN=Ganados, EMP=Empatados, PER=Perdidos, GAF=Goles a Favor, GEC=Goles en Contra, DIF=Difrencia Goles, PTS=Puntos<br/>
		<strong>Colores:</strong> Verde=Equipos Clasificados, Amarillo=Ganador por Penales
		<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover dataTables-tableGrupos" >
		<thead>
			<tr>
				<th colspan="9"><strong>Tabla de Posiciones</strong></th>	
			</tr>
		</thead>
		<tbody>
		<?php while($row = mysqli_fetch_array($resultGrupos)){ 
			calaculateTableGroup($row['id'],$idFase,$idComp);	
		?>	
		<tr>	
			<td  colspan="9" style="background-color: #E02D32;color: white;" >
				<strong><?php echo $row['nombre']; ?></strong>
			</td>
			<td hidden="true"></td>			
			<td hidden="true"></td>
			<td hidden="true"></td>
			<td hidden="true"></td>
			<td hidden="true"></td>
			<td hidden="true"></td>
			<td hidden="true"></td>
			<td hidden="true"></td>				
		</tr>
		<tr>	<?php if( $grue ==1 ){ ?> <td>Sel.</td> <?php } ?>
				<td>Equipo</td>
				<td>JUG</td>
				<td>GAN</td>
				<td>EMP</td>
				<td>PER</td>
				<td>GAF</td>
				<td>GEC</td>
				<td>DIF</td>
				<td>PTS</td>		
			</tr>
		<?php 
			for( $i = 0; $i < count($arrayDataOrderFinal); $i++){?>
			<tr style="<?php if($i < $row['clasifican'] ){echo "background-color: #ACFA58;"; } ?><?php if(!empty($arrayDataOrderFinal[$i]['ptsPenales']) && $arrayDataOrderFinal[$i]['ptsPenales']>0){echo "background-color: #F4FA58 !important;"; } ?>">	
				<?php if( $grue ==1 ){ ?> <td><input name="checkbox[]" type="checkbox" value="<?php echo $arrayDataOrderFinal[$i]['id_equipo']; ?>"></td> <?php } ?>
				<td><?php echo $arrayDataOrderFinal[$i]['nombreEqui']; ?></td>
				<td><?php echo $arrayDataOrderFinal[$i]['JUG']; ?></td>
				<td><?php echo $arrayDataOrderFinal[$i]['GAN']; ?></td>
				<td><?php echo $arrayDataOrderFinal[$i]['EMP']; ?></td>
				<td><?php echo $arrayDataOrderFinal[$i]['PER']; ?></td>
				<td><?php echo $arrayDataOrderFinal[$i]['GAF']; ?></td>
				<td><?php echo $arrayDataOrderFinal[$i]['GEC']; ?></td>
				<td><?php echo $arrayDataOrderFinal[$i]['DIF']; ?></td>
				<td><strong><?php echo $arrayDataOrderFinal[$i]['PTS']; ?></strong></td>					
			</tr>												 
		<?php } 
		} 
		?>
		</tbody>
		</table>
		</div>
		<?php 
	}else if( $fase['numero'] ==1 ){ // listar equipos de la competición aun no inscritos		
		$resultEquipos = $connect->query("select distinct e.* from equipo e JOIN inscripcion i ON e.id = i.id_equipo AND i.id_competicion = ".$idComp." AND e.id NOT IN( select distinct e.id from grupo g JOIN fase f ON g.id_fase = f.id AND f.id = ".$idFase." JOIN equipo_grupo eg ON g.id = eg.id_grupo JOIN equipo e ON eg.id_equipo = e.id) ORDER BY e.nombre asc");	
		
		$table='<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover dataTables-tableGrupos" >
		<thead>
			<tr>
				<th colspan="9"><strong>Equipos Inscritos</strong></th>	
			</tr>
		</thead>
		<tbody>';
		while($row = mysqli_fetch_array($resultEquipos)){
			 $table .= '<tr><td>'.$row['nombre'].'</td><td><input name="checkbox[]" type="checkbox" value="'.$row["id"].'"></td></tr>';
		}
		$table .= '</tbody>
		</table>
		</div>';
		echo $table;
		}
}else{//consultar tabla de la fase actual:
		$fase = mysqli_fetch_array($connect->query("select * from fase where id=".$idFase));
		$resultGrupos = $connect->query( "select g.* from fase f join grupo g on f.id = g.id_fase and f.id =".$idFase." order by g.nombre asc" );
		?>
		<strong>Criterios</strong>: JUG=Jugados, GAN=Ganados, EMP=Empatados, PER=Perdidos, GAF=Goles a Favor, GEC=Goles en Contra, DIF=Difrencia Goles, PTS=Puntos<br/>
		<strong>Colores:</strong> Verde=Equipos Clasificados o Ganador, Amarillo=Ganador por Penales
		<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover dataTables-tableGrupos" >
		<thead>
			<tr>
				<th colspan="9"><strong>Tabla de Posiciones</strong></th>	
			</tr>
		</thead>
		<tbody>
		<?php while($row = mysqli_fetch_array($resultGrupos)){ 
			calaculateTableGroup($row['id'],$idFase,$idComp);	
		?>	
		<tr>	
			<td  colspan="9" style="background-color: #E02D32;color: white;" >
				<strong><?php echo $row['nombre']; ?></strong>
			</td>
			<td hidden="true"></td>			
			<td hidden="true"></td>
			<td hidden="true"></td>
			<td hidden="true"></td>
			<td hidden="true"></td>
			<td hidden="true"></td>
			<td hidden="true"></td>
			<td hidden="true"></td>				
		</tr>
		<tr>	<?php if( $grue ==1 ){ ?> <td>Sel.</td> <?php } ?>
				<td>Equipo</td>
				<td>JUG</td>
				<td>GAN</td>
				<td>EMP</td>
				<td>PER</td>
				<td>GAF</td>
				<td>GEC</td>
				<td>DIF</td>
				<td>PTS</td>
				<!--td>PEN</td-->		
			</tr>
		<?php 
			for( $i = 0; $i < count($arrayDataOrderFinal); $i++){?>
			<tr style="<?php if($i < $row['clasifican'] ){echo "background-color: #ACFA58;"; } ?><?php if(!empty($arrayDataOrderFinal[$i]['ptsPenales']) && $arrayDataOrderFinal[$i]['ptsPenales']>0){echo "background-color: #F4FA58 !important;"; } ?>">	
				<?php if( $grue ==1 ){ ?> <td><input name="checkbox[]" type="checkbox" value="<?php echo $arrayDataOrderFinal[$i]['id_equipo']; ?>"></td> <?php } ?>
				<td><?php echo $arrayDataOrderFinal[$i]['nombreEqui']; ?></td>
				<td><?php echo $arrayDataOrderFinal[$i]['JUG']; ?></td>
				<td><?php echo $arrayDataOrderFinal[$i]['GAN']; ?></td>
				<td><?php echo $arrayDataOrderFinal[$i]['EMP']; ?></td>
				<td><?php echo $arrayDataOrderFinal[$i]['PER']; ?></td>
				<td><?php echo $arrayDataOrderFinal[$i]['GAF']; ?></td>
				<td><?php echo $arrayDataOrderFinal[$i]['GEC']; ?></td>
				<td><?php echo $arrayDataOrderFinal[$i]['DIF']; ?></td>
				<td><strong><?php echo $arrayDataOrderFinal[$i]['PTS']; ?></strong></td>
				<!--td><strong><?php echo !empty($arrayDataOrderFinal[$i]['ptsPenales'])?$arrayDataOrderFinal[$i]['ptsPenales']:''; ?></strong></td -->					
			</tr>												 
		<?php } 
		} 
		?>
		</tbody>
		</table>
		</div>
		<?php 
}
function calaculateTableGroup($idGrupo,$idFase,$idComp){
	require '../conexion.php';
	global $arrayDataOrder;
	global $arrayDataOrderFinal;
	global $arrayData;
	global $matrizEmpateTemp;
	global $idsEquposEmpateTemp;
	global $empate;
	$arrayData = array();
	$arrayDataOrder = array();
	$arrayDataOrderFinal = array();
	$matrizEmpateTemp = array();
	$idsEquposEmpateTemp = array();
	
	//1. consultar equipos del grupo $idGrupo
	$result = $connect->query( "select c.id idComp, c.nombre nombreComp, f.id idFase, f.nombre nombreFase, g.nombre nombreGrupo, e.nombre nombreEqui, eg.* from competicion c join fase f on c.id = f.id_competicion and c.id = ".$idComp." and f.id = ".$idFase." join grupo g on f.id = g.id_fase and g.id = ".$idGrupo." join equipo_grupo eg on g.id = eg.id_grupo join equipo e on eg.id_equipo = e.id order by eg.pts desc" );
	
	//2. Generar arreglo con datos del grupo $idGrupo:
	while($row = mysqli_fetch_array($result)){
		$arrayData [] = $row;
	}		
	
	//3. Realizar proceso de verificación para desempates:
	for($i=0; $i<count($arrayData); $i++){
		$matrizEmpateTemp = array();
		$idsEquposEmpateTemp = array();
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
			ordenarPorCriterios($idGrupo);
			//adicionar $matrizEmpateTemp a la lista de ordenamiento
			adicionarOrdenados();		
			$empate = false;
			//salatar contador i:
			$i = $i+count($matrizEmpateTemp)-1;
		}else{//adicionar i a la lista de ordenamiento
			array_push($arrayDataOrder,$arrayData[$i]);
		}	
	}
	//ordenar partidos jugados 0 de ultimo 
	for($i=0; $i<count($arrayDataOrder); $i++){
		if($arrayDataOrder[$i]['JUG'] > 0 ){
			array_push($arrayDataOrderFinal,$arrayDataOrder[$i]);
		}
	}
	for($i=0; $i<count($arrayDataOrder); $i++){
		if($arrayDataOrder[$i]['JUG'] < 1 ){
			array_push($arrayDataOrderFinal,$arrayDataOrder[$i]);
		}
	}
	//print_r($arrayDataOrderFinal);
}
function ordenarPorCriterios($idGrupo){
	global $matrizEmpateTemp;
	global $criterio;
	global $operador;
	global $idComp;
	global $idFase;
	global $idsEquposEmpateTemp;
	global $persisteEmpate;
	$antidadEmpatados = count($matrizEmpateTemp);	
	//1.Por mayor número de puntos obtenidos entre los enfrentamientos directos de los equipos en cuestión.	
	calcularPuntosDirectos( $idFase, $idGrupo,$idsEquposEmpateTemp );
	$criterio = "ptsDirect"; $operador = "menor";
	ordenarPorCriterio($criterio,$matrizEmpateTemp);
	if( $persisteEmpate ){
		//desempate por ganador en penales.
		$persisteEmpate = false;
		calcularPenales( $idFase, $idGrupo,$idsEquposEmpateTemp );		
		$criterio = "ptsPenales"; $operador = "mayor";
		ordenarPorCriterio($criterio,$matrizEmpateTemp);
	}
	if( $persisteEmpate ){
		//2.Quien presente el mejor promedio en el juego limpio.
		$persisteEmpate = false;
		calcularJuegoLimpio( $idComp, $idFase );		
		$criterio = "ptsJl"; $operador = "mayor";		
		ordenarPorCriterio($criterio,$matrizEmpateTemp);
	}	
	if( $persisteEmpate ){
		//3.Quién presente mayor puntaje en el gol diferencia.
		$persisteEmpate = false;		
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
		//5.Quién tenga menos goles en contra.
		$persisteEmpate = false;		
		$criterio = "GEC"; $operador = "mayor";
		ordenarPorCriterio($criterio,$matrizEmpateTemp);
	}
}
function calcularPuntosDirectos( $idFase, $idGrupo,$idsEquposEmpateTemp ){
	require '../conexion.php';
	global $matrizEmpateTemp;
	global $idsEquposEmpateTemp;
	$idsEquiposPorComa = implode(",", $idsEquposEmpateTemp);
	for($i=0; $i<count($matrizEmpateTemp); $i++){
		//--contar ganados
		$totalPuntosG = mysqli_fetch_array( $connect->query( "select (count(1) * 3) total from juego where id_grupo = ".$idGrupo." and id_fase = ".$idFase." and (id_equipo_1 in(".$idsEquiposPorComa.") and id_equipo_2 in(".$idsEquiposPorComa.")) and (id_ganador = ".$matrizEmpateTemp[$i]['id_equipo'].")" ) );		
		$totalPuntosE = mysqli_fetch_array( $connect->query( "select (count(1) * 1) total from juego where id_grupo = ".$idGrupo." and id_fase = ".$idFase." and (id_equipo_1 in(".$idsEquiposPorComa.") and id_equipo_2 in(".$idsEquiposPorComa.")) and (id_ganador = 0 and(id_equipo_1 =".$matrizEmpateTemp[$i]['id_equipo']." or id_equipo_2 = ".$matrizEmpateTemp[$i]['id_equipo']."))" ) );
		$matrizEmpateTemp[$i]['ptsDirect'] = ($totalPuntosG['total']+$totalPuntosE['total']);		
	}	
}
function calcularPenales( $idFase, $idGrupo,$idsEquposEmpateTemp ){
	require '../conexion.php';
	global $matrizEmpateTemp;
	global $idsEquposEmpateTemp;
	$idsEquiposPorComa = implode(",", $idsEquposEmpateTemp);
	for($i=0; $i<count($matrizEmpateTemp); $i++){
		//--contar ganados por penales
		$totalPuntosPenales = mysqli_fetch_array( $connect->query( "select (count(1) * 3) total from juego where id_grupo = ".$idGrupo." and id_fase = ".$idFase." and (id_equipo_1 in(".$idsEquiposPorComa.") and id_equipo_2 in(".$idsEquiposPorComa.")) and (id_ganador_penales = ".$matrizEmpateTemp[$i]['id_equipo'].")" ) );		
		$matrizEmpateTemp[$i]['ptsPenales'] = ($totalPuntosPenales['total']);
	}
}
function calcularJuegoLimpio( $idComp, $idFase ){
	require '../conexion.php';
	global $matrizEmpateTemp;
	global $matrizEmpateTemp;
	for($i=0; $i<count($matrizEmpateTemp); $i++){
		//--contar puntos juego limpio en la fase de la comeptencia del equipo
		$totalPuntosJL = mysqli_fetch_array( $connect->query( "select COALESCE(SUM(ts.puntos),0) total from sancion s join juego j on s.id_juego = j.id and (j.id_equipo_1 =  ".$matrizEmpateTemp[$i]['id_equipo']." or j.id_equipo_2 = ".$matrizEmpateTemp[$i]['id_equipo'].") join fase f on j.id_fase = f.id and f.id = ".$idFase." join competicion c on f.id_competicion = c.id and c.id = ".$idComp." join tipo_sancion ts on s.id_tipo_sancion = ts.id join jugador jug on jug.id = s.id_jugador and jug.id_equipo =".$matrizEmpateTemp[$i]['id_equipo'] ) );
		
		$resultJuegos = mysqli_fetch_array($connect->query( "select count(1) juegos from juego j join equipo e on (j.id_equipo_1 = e.id or j.id_equipo_2 = e.id) and e.id = ".$matrizEmpateTemp[$i]['id_equipo']." and j.informado =1 join fase f on j.id_fase = f.id and f.id = ".$idFase." and  f.id_competicion =".$idComp));
		
		//contar partidos jugados en la comeptencia del equipo
		if( $resultJuegos['juegos']>0){
			$matrizEmpateTemp[$i]['ptsJl'] = ($totalPuntosJL['total'] / $resultJuegos['juegos']);
		}else{
			$matrizEmpateTemp[$i]['ptsJl'] = 0;
		}
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
					if ($matrizEmpateTemp[$j+1][$criterio] > $matrizEmpateTemp[$j][$criterio])
					{
						$tmp = $matrizEmpateTemp[$j];
						$matrizEmpateTemp[$j] = $matrizEmpateTemp[$j+1];
						$matrizEmpateTemp[$j+1] = $tmp;					
					}else if ($matrizEmpateTemp[$j+1][$criterio] == $matrizEmpateTemp[$j][$criterio]){
						//validar gaador por penales
						
						$persisteEmpate = true;
					}
				}
            }
	}
}
?>
<?php $connect->close(); ?>