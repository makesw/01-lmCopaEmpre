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
//header info for browser
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=planilla.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");

$idJuego = 0;
$idFase = 0;
$btnGoles = 0;
if ( isset( $_GET[ 'idJuego' ] ) ) {
	$idJuego = $_GET[ 'idJuego' ];
}
if ( isset( $_GET[ 'idFase' ] ) ) {
	$idFase = $_GET[ 'idFase' ];
}
if ( isset( $_GET[ 'btnGoles' ] ) ) {
	$btnGoles = $_GET[ 'btnGoles' ];
}

//consultar equipo 1;
$equipo1 = mysqli_fetch_array( $connect->query( "select e.* from juego j join equipo e on j.id=".$idJuego." and j.id_equipo_1 = e.id" ) );
//consultar equipo 2;
$equipo2 = mysqli_fetch_array( $connect->query( "select e.* from juego j join equipo e on j.id=".$idJuego." and j.id_equipo_2 = e.id" ) );
$juego = mysqli_fetch_array( $connect->query( "select j.*, e.nombre nombreEscena, s.nombre sede from juego j JOIN escenario e ON j.id_escenario = e.id AND j.id =" . $idJuego . " AND j.fecha is not null JOIN sede s ON e.id_sede = s.id" ) );
$resultJugadores1 = $connect->query( "select ju.*, a.id_jugador asistencia  from juego j JOIN equipo e ON j.id_equipo_1 = e.id AND j.fecha is not null AND j.id = " . $idJuego . " JOIN jugador ju ON e.id = ju.id_equipo and ju.activo is null LEFT JOIN asistencia a ON a.id_juego = j.id AND a.id_jugador = ju.id ORDER BY nombres ASC" );
//Generar arreglo con datos de jugadpres1:
$arrayJugadores1 = array();
while($row = mysqli_fetch_array($resultJugadores1)){
	$arrayJugadores1 [] = $row;
}	

$resultJugadores2 = $connect->query( "select ju.*, a.id_jugador asistencia  from juego j JOIN equipo e ON j.id_equipo_2 = e.id AND j.fecha is not null AND j.id = " . $idJuego . " JOIN jugador ju ON e.id = ju.id_equipo and ju.activo is null LEFT JOIN asistencia a ON a.id_juego = j.id AND a.id_jugador = ju.id ORDER BY nombres ASC" );
//Generar arreglo con datos de jugadpres2:
$arrayJugadores2 = array();
while($row = mysqli_fetch_array($resultJugadores2)){
	$arrayJugadores2 [] = $row;
}	
$date = new DateTime( $juego[ 'fecha' ] );
$fecha = $date->format( 'Y-m-d' );
//$resultGoles1 = mysqli_fetch_array($connect->query( "select count(1) total from gol where id_equipo=".$equipo1['id']." and id_juego=".$idJuego));
//$resultGoles2 = mysqli_fetch_array($connect->query( "select count(1) total from gol where id_equipo=".$equipo2['id']." and id_juego=".$idJuego));
$vetadosoSancionados = false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Mouldifi - A fully responsive, HTML5 based admin theme">
	<meta name="keywords" content="Responsive, HTML5, admin theme, business, professional, Mouldifi, web design, CSS3">
	<title>Planilla</title>	
</head>
<body>
<table border="1">
	<tr style="text-align: left;height: 70px;vertical-align: middle">
		<td colspan="14"><img src="https://www.copaemprendedores.com/wp-content/uploads/2013/01/logo-copa-emprendedores.png" /></td>
	</tr>
	<tr style="text-align: left;">
		<td colspan="7" style="font-size:  25px;font-weight: bold;"><?php echo($juego['nombre1']);?></td>
		<td colspan="7" style="font-size:  25px;font-weight: bold;"><?php echo($juego['nombre2']);?></td>
	</tr>
	<tr style="text-align: left;height: 20px">
		<td colspan="2" style="font-size:  12px;font-weight: bold;">HORA: <?php echo($juego['hora_inicio']); ?></td>
		<td colspan="5" style="font-size:  12px;font-weight: bold;">FECHA: <?php echo($fecha); ?></td>
		<td colspan="4" style="font-size:  12px;font-weight: bold;">LUGAR: <?php echo($juego['sede']); ?></td>
		<td colspan="3" style="font-size:  12px;font-weight: bold;">CAMPO: <?php echo($juego['nombreEscena']); ?></td>
	</tr>
	<tr style="text-align: center;;height: 50px;">
		<td colspan="2" style="font-size:  12px;font-weight: bold;vertical-align: bottom;">NOMBRE ASISTENTE 1</td>
		<td colspan="8" style="font-size:  12px;font-weight: bold;vertical-align: bottom;">NOMBRE JUEZ CENTRAL</td>
		<td colspan="4" style="font-size:  12px;font-weight: bold;vertical-align: bottom;">NOMBRE ASISTENTE 2</td>
	</tr>
	<tr style="text-align: left;height: 20px">
		<td style="font-size:  12px;font-weight: bold;width: 10px;">No</td>
		<td style="font-size:  12px;font-weight: bold;">NOMBRES Y APELLIDOS</td>
		<td style="font-size:  12px;font-weight: bold;">IDENTIFICACIÓN</td>
		<td style="font-size:  12px;font-weight: bold;">A</td>
		<td style="font-size:  12px;font-weight: bold;">R</td>
		<td style="font-size:  12px;font-weight: bold;">GOL</td>
		<td colspan="2" style="font-size:  12px;font-weight: bold;width: 10px;"></td>
		<td style="font-size:  12px;font-weight: bold;width: 10px;">No</td>
		<td style="font-size:  12px;font-weight: bold;">NOMBRES Y APELLIDOS</td>
		<td style="font-size:  12px;font-weight: bold;">IDENTIFICACIÓN</td>
		<td style="font-size:  12px;font-weight: bold;">A</td>
		<td style="font-size:  12px;font-weight: bold;">R</td>
		<td style="font-size:  12px;font-weight: bold;">GOL</td>
	</tr>
	
	<?php
	for ($i = 0; $i < 25; $i++) {
	$vetadoSancion1 = false;
	$vetadoSancion2 = false;
	$printValue = false;
	if(array_key_exists($i, $arrayJugadores1)){$printValue=true;}
	$printValue2 = false;
	if(array_key_exists($i, $arrayJugadores2)){$printValue2=true;}
	
	//validar jugador 1:
	if($printValue){
	   $jugador_vetado = mysqli_fetch_array( $connect->query( "select count(1) total from jugador_vetado where documento_jugador = ".$arrayJugadores1[$i]['documento'] ));
	   if($jugador_vetado['total']>0){
	       $vetadoSancion1 = true;
	       $vetadosoSancionados = true;
	   }else{
	       //validar jugador sancionado:
	       //consultar la última sancion del jugador en la competicion:
	       $sancion = mysqli_fetch_array( $connect->query( "select s.*, ts.veta_jugador,ts.fechas_suspencion, j.fecha fechJuego from sancion s join tipo_sancion ts ON s.id_jugador = ".$arrayJugadores1[$i]['id']." and s.id_tipo_sancion = ts.id join juego j on s.id_juego = j.id join fase f on j.id_fase = f.id and f.activa =1 join competicion c on f.id_competicion = c.id and c.id = ".$juego['id_competicion']." order by s.fecha desc limit 1") );
	       if($sancion!= null && $sancion['fechas_suspencion']>0 ){
	           //si aplica fechas de suspenció se valida si el jugador puede jugar este juego:
	           //juegos del equipo después de la fecha de sancion:
	           $resultJuegosTeam =  $connect->query( "select distinct j.id, j.fecha from juego j join fase f on j.fecha is not null and j.id_fase = f.id and f.id_competicion = ".$juego['id_competicion']." and (j.id_equipo_1 = ".$juego['id_equipo_1']." or j.id_equipo_2 = ".$juego['id_equipo_1'].") order by j.fecha asc");
	           $contador = $sancion['fechas_suspencion']+1;
	           $fechaHabil = null;
	           //echo "contador-->".$contador;
	           //echo "idJugador-->".$row1['id'];
	           while ( $rowGame = mysqli_fetch_array( $resultJuegosTeam ) ) {
	               if( $rowGame['fecha'] > $sancion['fechJuego']){
	                   $contador --;
	               }
	               if( $contador == 0 ){
	                   $fechaHabil = $rowGame['fecha'];
	                   //echo "fechaHabil-->".$fechaHabil;
	                   break;
	               }
	           }
	           if(($juego['fecha'] < $fechaHabil) || $contador > 0 ){
	               $vetadoSancion1 = true;
	               $vetadosoSancionados = true;
	           }
	       }
	   }
	   //consultar goles de jugador en el partido:
	   //$golesPlayer1 = mysqli_fetch_array( $connect->query( "select count(1) total from gol where id_jugador = ".$arrayJugadores1[$i]['id']." and id_juego=".$idJuego ));
	}
	//validar jugador 2:
	if($printValue2){
	   $jugador_vetado = mysqli_fetch_array( $connect->query( "select count(1) total from jugador_vetado where documento_jugador = ".$arrayJugadores2[$i]['documento'] ));
	   if($jugador_vetado['total']>0){
	       $vetadoSancion2 = true;
	       $vetadosoSancionados = true;
	   }else{
	       //validar jugador sancionado:
	       //consultar la última sancion del jugador en la competicion:
	       $sancion = mysqli_fetch_array( $connect->query( "select s.*, ts.veta_jugador,ts.fechas_suspencion, j.fecha fechJuego from sancion s join tipo_sancion ts ON s.id_jugador = ".$arrayJugadores2[$i]['id']." and s.id_tipo_sancion = ts.id join juego j on s.id_juego = j.id join fase f on j.id_fase = f.id and f.activa =1 join competicion c on f.id_competicion = c.id and c.id = ".$juego['id_competicion']." order by s.fecha desc limit 1") );
	       if($sancion!= null && $sancion['fechas_suspencion']>0 ){
	           //si aplica fechas de suspenció se valida si el jugador puede jugar este juego:
	           //juegos del equipo después de la fecha de sancion:
	           $resultJuegosTeam =  $connect->query( "select distinct j.id, j.fecha from juego j join fase f on j.fecha is not null and j.id_fase = f.id and f.id_competicion = ".$juego['id_competicion']." and (j.id_equipo_1 = ".$juego['id_equipo_2']." or j.id_equipo_2 = ".$juego['id_equipo_2'].") order by j.fecha asc");
	           $contador = $sancion['fechas_suspencion']+1;
	           $fechaHabil = null;
	           //echo "contador-->".$contador;
	           //echo "idJugador-->".$row1['id'];
	           while ( $rowGame = mysqli_fetch_array( $resultJuegosTeam ) ) {
	               if( $rowGame['fecha'] > $sancion['fechJuego']){
	                   $contador --;
	               }
	               if( $contador == 0 ){
	                   $fechaHabil = $rowGame['fecha'];
	                   //echo "fechaHabil-->".$fechaHabil;
	                   break;
	               }
	           }
	           if(($juego['fecha'] < $fechaHabil) || $contador > 0 ){
	               $vetadoSancion2 = true;
	               $vetadosoSancionados = true;
	           }
	       }
	   }
	   //consultar goles de jugador en el partido:
	   //$golesPlayer2 = mysqli_fetch_array( $connect->query( "select count(1) total from gol where id_jugador = ".$arrayJugadores2[$i]['id']." and id_juego=".$idJuego ));	   
	}
	
	?>
	<tr style="text-align: left;font-size:  12px;height: 23px">
		<td style="width: 20px;"><!-- ?php if($printValue){echo $arrayJugadores1[$i]['numero'];} ?--></td>
		<td <?php if($vetadoSancion1){ echo 'style="background-color:red;"' ; }?>><?php if($printValue){echo strtoupper ( $arrayJugadores1[$i]['nombres'].' '.$arrayJugadores1[$i]['apellidos']);} ?></td>
		<td <?php if($vetadoSancion1){ echo 'style="background-color:red;"' ; }?>><?php if($printValue){echo $arrayJugadores1[$i]['documento'];} ?></td>
		<td></td>
		<td></td>
		<td><?php //echo $golesPlayer1['total'] ?></td>
		<td colspan="2"></td>
		<td style="width: 20px;"><!--?php if($printValue2){echo $arrayJugadores2[$i]['numero'];} ?--></td>
		<td <?php if($vetadoSancion2){ echo 'style="background-color:red;"' ; }?>><?php if($printValue2){echo strtoupper ( $arrayJugadores2[$i]['nombres'].' '.$arrayJugadores2[$i]['apellidos']);} ?></td>
		<td <?php if($vetadoSancion2){ echo 'style="background-color:red;"' ; }?>><?php if($printValue2){echo $arrayJugadores2[$i]['documento'];} ?></td>
		<td></td>
		<td></td>
		<td><?php //echo $golesPlayer2['total'] ?></td>
	</tr>
	<?php } ?>
	
	
	<tr style="font-size:  15px;font-weight: bold;">
		<td colspan="7">TOTAL GOLES: <?php //echo $resultGoles1['total']; ?></td>
		<td colspan="7">TOTAL GOLES: <?php //echo $resultGoles2['total']; ?></td>
	</tr>
	<tr style="text-align: left;font-size:  15px;font-weight: bold;">
		<td colspan="5">COLOR UNIFORME:</td>
		<td colspan="2" style="background-color: <?php echo $equipo1['color']; ?>;"></td>
		<td colspan="5">COLOR UNIFORME:</td>
		<td colspan="2" style="background-color: <?php echo $equipo2['color']; ?>;"></td>
	</tr>
	<tr style="text-align: left;font-size:  15px;font-weight: bold;height: 50px;vertical-align: bottom;">
		<td colspan="7">FIRMA Y Nº DEL DELEGADO O CAPITAN:</td>
		<td colspan="7">FIRMA Y Nº DEL DELEGADO O CAPITAN:</td>
	</tr>	
	<tr style="text-align: center;">
		<td colspan="14" style="font-size:  15px;">CON ESTA FIRMA CERTIFICO QUE COMOZCO EL REGLAMENTO DEL PRESENTE TORNEO Y ME HAGO RESPONSABLE DE MI EQUIPO. </td>
	</tr>
	<tr style="text-align: center;">
		<td colspan="14" style="font-size:  15px;"><strong style="color: red;"><?php if( $vetadosoSancionados) echo 'NOTA: Los Jugadores Marcados se encuentran Sancionados o Vetados para este Juego'; ?></strong></td>
	</tr>
	<tr style="text-align: center;">
		<td colspan="14" style="font-size:  20px; vertical-align: bottom">INFORME ARBITRAL</td>
	</tr>
	<tr style="text-align: left;height: 200px">
		<td colspan="14" style="font-size:  20px; vertical-align: top"><?php //echo($juego['informe']); ?></td>
	</tr>		
</table>	
</body>
</html>
<?php $connect->close(); ?>