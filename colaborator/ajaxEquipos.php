<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
    header( 'Location: ../index.html' );
}else{
    if($_SESSION[ 'dataSession' ]['perfil'] != 'colaborador'){
        header( 'Location: ../salir.php' );
    }
}
$arrayFases = array();
require '../conexion.php';
$idComp = 0;
$idFase = 0;
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

if(isset($_GET[ 'opt' ]) && $_GET[ 'opt' ]==1 ){
	
	$resultEquipos = $connect->query("select distinct e.* from equipo e JOIN inscripcion i ON e.id = i.id_equipo AND i.id_competicion = ".$_GET["idComp"]." AND e.id NOT IN( select distinct e.id from grupo g JOIN fase f ON g.id_fase = f.id AND f.id = ".$_POST["idFase"]." JOIN equipo_grupo eg ON g.id = eg.id_grupo JOIN equipo e ON eg.id_equipo = e.id) ORDER BY e.nombre asc");	
	
	$table='';
	while($row = mysqli_fetch_array($resultEquipos)){
		 $table .= '<tr><td>'.$row['nombre'].'</td><td><input name="checkbox[]" type="checkbox" value="'.$row["id"].'"></td></tr>';
	}
	echo $table;
}
if(isset($_GET[ 'opt' ]) && $_GET[ 'opt' ]==2 ){
	$resultEquipos = $connect->query("select e.* from equipo e JOIN inscripcion i ON e.id = i.id_equipo AND i.id_competicion = ".$_POST["idComp"]." ORDER BY nombre asc");
	$table='';
	while($row = mysqli_fetch_array($resultEquipos)){
		 $table .= '<tr><td>'.$row['nombre'].'</td><td><input name="checkbox[]" type="checkbox" value="'.$row["id"].'"></td></tr>';
	}
	echo $table;
}
if(isset($_GET[ 'opt' ]) && $_GET[ 'opt' ]==3 ){
	$idcomp = 0;
	if(isset($_GET[ 'idComp' ])){$idcomp=$_GET[ 'idComp' ];}
	$resultTeams = $connect->query("select e.*, c.nombre competicion from equipo e join competicion c on e.id_competicion = c.id and e.id_competicion = ".$idcomp." and e.id NOT IN (select distinct(i.id_equipo) from inscripcion i  join competicion c on i.id_competicion = c.id and c.activa =1 and c.id = ".$idcomp.")  ORDER BY e.nombre asc");
	$table='';
	while($row = mysqli_fetch_array($resultTeams)){
		 $table .= '<tr><td>'.$row['nombre'].'</td>><td>'.$row['competicion'].'</td><td><input name="checkbox[]" type="checkbox" value="'.$row["id"].'"></td</tr>';
	}
	echo $table;
}
if(isset($_GET[ 'opt' ]) && $_GET[ 'opt' ]==4 ){//Listar equipos con la respectiva posición actual	
	$idGrupo = 0;
	global $idComp;
	global $idFase;
	if(isset($_GET[ 'idComp' ])){$idComp=$_GET[ 'idComp' ];}
	if(isset($_POST[ 'idFase' ])){$idFase=$_POST[ 'idFase' ];}
	if(isset($_GET[ 'idGrupo' ])){$idGrupo=$_GET[ 'idGrupo' ];}	
	//consultar datos la fase nueva:
	$fase = mysqli_fetch_array($connect->query("select * from fase where id=".$idFase));
	//consultar datos de la fase anterior
	$faseAnterior = mysqli_fetch_array($connect->query("select * from fase where numero=".($fase['numero']-1)." and id_competicion=".$idComp));	
	//consultar datos de lacompeticion:
	$competicion = mysqli_fetch_array($connect->query("select * from competicion where id=".$idComp));
	
	if( $fase['numero'] == 1 ){ // se listan los equipos con cero posiciones
			$resultTeams = $connect->query("select e.*, c.nombre competicion from equipo e join competicion c on e.id_competicion = c.id_parent and c.id_parent = ".$competicion['id_parent']."
			and e.id NOT IN (select distinct(eg.id_equipo) from equipo_grupo eg join grupo g on eg.id_grupo = g.id join fase f on g.id_fase = f.id  join competicion c on f.id_competicion = c.id and c.id = ".$competicion['id']." and c.activa =1)  ORDER BY e.nombre asc");
			$table='';
			while($row = mysqli_fetch_array($resultTeams)){
				 $table .= '<tr><td><input name="checkbox[]" type="checkbox" value="'.$row["id"].'"></td><td></td><td></td><td>'.$row['nombre'].'</td><td></td></tr>';
			}
			echo $table;
	 }else if ( $fase['numero'] > 1 ){ // mostrar tabla de posciones actual con check:
		
		
		?>
			<table class="table table-striped table-bordered table-hover dataTables-tableGrupos" >
			<thead>
				<tr>
					<th colspan="10"><strong>Tabla de Posiciones</strong></th>	
				</tr>
			</thead>
			<tbody>
			<?php 
			//consultar grupos de la fase anterior:
			$grupos = $connect->query("select * from grupo where id_fase=".$faseAnterior['id']);
			while($grupo = mysqli_fetch_array($grupos)){	 
				calaculateTableGroup($grupo['id'],$faseAnterior['id'],$idComp);	
			?>	
			<tr>	
				<td  colspan="10" style="background-color: #E02D32;color: white;" >
					<strong><?php echo $grupo['nombre']; ?></strong>
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
			<tr>
					<td>Sel.</td>
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
				<tr style="<?php if($i < $grupo['clasifican'] ){echo "background-color: #bbebba;"; } ?>">	 <td><input name="checkbox[]" type="checkbox" value="<?php echo $arrayDataOrderFinal[$i]['id_equipo']; ?>"></td>
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
		
<?php }
?>

<script>
$(document).ready(function () {
$('.dataTables-tableGrupos').DataTable({
	"searching": false,
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
	global $idsEquposEmpateTemp;
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
		$totalPuntosJL = mysqli_fetch_array( $connect->query( "select COALESCE(SUM(ts.puntos),0) total from sancion s join juego j on s.id_juego = j.id and (j.id_equipo_1 =  ".$matrizEmpateTemp[$i]['id_equipo']." or j.id_equipo_2 = ".$matrizEmpateTemp[$i]['id_equipo'].") join fase f on j.id_fase = f.id join competicion c on f.id_competicion = c.id and c.id = ".$idComp." join tipo_sancion ts on s.id_tipo_sancion = ts.id join jugador jug on jug.id = s.id_jugador and jug.id_equipo =".$matrizEmpateTemp[$i]['id_equipo'] ) );
		
		$resultJuegos = mysqli_fetch_array($connect->query( "select count(1) juegos from juego j join equipo e on (j.id_equipo_1 = e.id or j.id_equipo_2 = e.id) and e.id = ".$matrizEmpateTemp[$i]['id_equipo']." and j.informado =1 join fase f on j.id_fase = f.id and f.id_competicion =".$idComp));
		
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
						$persisteEmpate = true;
					}
				}
            }
	}
}
$connect->close();
?>