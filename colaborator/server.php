<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: /index.php' );
}
require '../conexion.php';
$connect->query( "SET NAMES 'utf8'" );
header( 'Content-Type: application/json' );

$action = $_GET[ 'action' ];

if ( $action == 'addSede' ) {
	$query = "INSERT INTO sede ( nombre ) VALUES
		(   '" . $_POST[ "nombre" ] . "')";
	$result = $connect->query( $query );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Creado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Crear Registro'));
	}
}
if ( $action == 'delSede' ) {	
	$idSede = $_GET[ 'idSede' ];
	$sql = "DELETE FROM sede WHERE id=".$idSede;	
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro'));
	}
}
if ( $action == 'getDataSede' ) {	
	$idSede = $_GET[ 'idSede' ];
	$sede = mysqli_fetch_array( $connect->query( "SELECT * FROM sede WHERE id=".$idSede ));
	echo json_encode(array('id'=>$sede['id'],'nombre'=>$sede['nombre']));	
}
if ( $action == 'editSede' ) {	
	$result = $connect->query( "update sede SET nombre = '".$_POST[ "nombreSede" ]."' WHERE id=".$_POST[ "btnhIdsede" ] );
	echo json_encode(array('result'=>$result));	
}
if ( $action == 'addTSancion' ) {
	$veta = 0;
	if(isset($_POST[ "veta" ])){$veta=1;}
	$query = "INSERT INTO tipo_sancion ( nombre,puntos,fechas_suspencion, valor, veta_jugador ) VALUES
		(   '" . $_POST[ "nombre" ]. "',".$_POST[ "puntos" ].",".$_POST[ "fechas" ].",".$_POST[ "valor" ].",".$veta.")";
	$result = $connect->query( $query );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Creado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Crear Registro'));
	}
}
if ( $action == 'getDataTsancion' ) {	
	$id = $_GET[ 'id' ];
	$tSancion = mysqli_fetch_array( $connect->query( "SELECT * FROM tipo_sancion WHERE id=".$id ));
	echo json_encode($tSancion);	
}
if ( $action == 'editTSancion' ) {
	$veta = 0;
	if(isset($_POST[ "veta" ])){$veta=1;}
	$result = $connect->query( "update tipo_sancion SET nombre = '".$_POST[ "nombreEts" ]."',puntos =".$_POST[ "puntosEts" ].",fechas_suspencion =".$_POST[ "fechasEts" ].",valor =".$_POST[ "valorEts" ].", veta_jugador=".$veta." WHERE id=".$_POST[ "btnhIdEts" ] );
	echo json_encode(array('result'=>$result));	
}
if ( $action == 'addEscena' ) {
	$query = "INSERT INTO escenario ( nombre,id_sede ) VALUES
		(   '" . $_POST[ "nombre" ]. "',".$_POST[ "sedeSelectId" ].")";
	$result = $connect->query( $query );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Creado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Crear Registro'));
	}
}
if ( $action == 'getDataEscena' ) {	
	$id = $_GET[ 'id' ];
	$escenario = mysqli_fetch_array( $connect->query( "SELECT * FROM escenario WHERE id=".$id ));
	echo json_encode($escenario);	
}
if ( $action == 'editEscena' ) {	
	$result = $connect->query( "update escenario SET nombre = '".$_POST[ "nombreEscena" ]."',id_sede=".$_POST[ "sedeSelectId" ]." WHERE id=".$_POST[ "btnhIdEscena" ] );
	echo json_encode(array('result'=>$result));	
}
if ( $action == 'delEscena' ) {	
	$id = $_GET[ 'id' ];
	$sql = "DELETE FROM escenario WHERE id=".$id;	
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro'));
	}
}
if ( $action == 'delTsanc' ) {	
	$id = $_GET[ 'id' ];
	$sql = "DELETE FROM tipo_sancion WHERE id=".$id;	
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro'));
	}
}
if ( $action == 'addUpdComp' ) {
	$active = 0;
	$inscription = 0;
	$bthAction = 0;
	$parent = 'null';
	if(isset($_POST[ "activa" ])){$active=1;}
	if(isset($_POST[ "cmbComp" ]) && $_POST[ "cmbComp" ]!=-1){$parent=$_POST[ "cmbComp" ];}
	if(isset($_POST[ "inscripcion" ])){$inscription=1;}
	if(isset($_POST[ "bthAction"])){$bthAction=$_POST[ "bthAction" ];}
	if( $bthAction == 1 ){ //insert data
		$query = "INSERT INTO competicion (nombre,fecha_inicio,fecha_fin,puntos_ganador,puntos_perdedor,puntos_empate,activa,habilitar_inscripciones,fecha_creacion,id_parent, valor) VALUES ('" . $_POST[ "nombre" ] ."',null,null,'".$_POST["puntosg"]."','".$_POST["puntosp"]."','".$_POST["puntose"]."','".$active."','".$inscription."'"." ,NOW(),".$parent.",".$_POST[ "valor" ]." )";
		$result = $connect->query( $query );		
		if( $result == 1){		
			echo json_encode(array('error'=>false,'description'=>'Registro Creado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Crear Registro'));
		}
	}else if( $bthAction == 2 ){ //update data		
		$query = "UPDATE competicion SET nombre='".$_POST["nombre"]."',puntos_ganador='".$_POST["puntosg"]."',puntos_perdedor='".$_POST["puntosp"]."',puntos_empate='".$_POST["puntose"]."',activa='".$active."',habilitar_inscripciones='".$inscription."',id_parent='".$parent."',valor=".$_POST["valor"]." WHERE id = ".$_POST[ "bthValId" ];
		$result = $connect->query( $query );
		if( $result == 1){		
			echo json_encode(array('error'=>false,'description'=>'Registro Actualizado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Actualizar Registro'));
		}
	}
}
if ( $action == 'getDataComp' ) {	
	$id = $_GET[ 'id' ];
	$comp = mysqli_fetch_array( $connect->query( "SELECT * FROM competicion WHERE id=".$id ));
	echo json_encode($comp);
}
if ( $action == 'delComp' ) {	
	$id = $_GET[ 'id' ];
	$result = $connect->query( "delete from equipo_grupo where id_equipo in (select x.id_equipo from (select eg.id_equipo from competicion c join fase f on c.id = ".$id." and c.id = f.id_competicion join grupo g on f.id = g.id_fase join equipo_grupo eg on g.id = eg.id_grupo) AS x)" );
	$result = $connect->query( "delete from juego where id_fase in( select f.id from fase f where f.id_competicion =".$id.")" );
	$sql = "DELETE FROM competicion WHERE id=".$id;	
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro'));
	}
}
if ( $action == 'addUpdFase' ) {
	$active = 0;
	$bthAction = 0;
	if(isset($_POST[ "activa" ])){$active=1;}
	if(isset($_POST[ "bthAction"])){$bthAction=$_POST[ "bthAction" ];}
	if( $bthAction == 1 ){ //insert data
		$query = "INSERT INTO fase (nombre,numero,id_competicion,activa) VALUES ('" . $_POST[ "nombre" ] ."','".$_POST["numero"]."','".$_POST["cmbComp"]."','".$active."'".")";
		$result = $connect->query( $query );
		if( $result == 1){		
			echo json_encode(array('error'=>false,'description'=>'Registro Creado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Crear Registro'));
		}
	}else if( $bthAction == 2 ){ //update data
		$active = 0;
		if(isset($_POST[ "activa" ])){$active=1;}
		$query = "UPDATE fase SET nombre='".$_POST["nombre"]."',id_competicion='".$_POST["cmbComp"]."',activa='".$active."' WHERE id = ".$_POST[ "bthValId" ];
		$result = $connect->query( $query );
		if( $result == 1){		
			echo json_encode(array('error'=>false,'description'=>'Registro Actualizado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Actualizar Registro'));
		}
	}
}
if ( $action == 'getDataFase' ) {	
	$id = $_GET[ 'id' ];
	$comp = mysqli_fetch_array( $connect->query( "SELECT * FROM fase WHERE id=".$id ));
	echo json_encode($comp);
}
if ( $action == 'delFase' ) {	
	$id = $_GET[ 'id' ];
	$sql = "DELETE FROM fase WHERE id=".$id;	
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro'));
	}
}
if ( $action == 'addUpdGrupo' ) {	
	$bthAction = 0;
	if(isset($_POST[ "bthAction"])){$bthAction=$_POST[ "bthAction" ];}
	if( $bthAction == 1 ){ //insert data
		$query = "INSERT INTO grupo (nombre,id_fase, clasifican) VALUES ('" . $_POST[ "nombre" ] ."','".$_POST[ "cmbFase" ]."',".$_POST[ "clasi" ].")";		
		$result = $connect->query( $query );
		if( $result == 1){		
			echo json_encode(array('error'=>false,'description'=>'Registro Creado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Crear Registro'));
		}
	}else if( $bthAction == 2 ){ //update data
		$query = "UPDATE grupo SET nombre='".$_POST["nombre"]."',id_fase='".$_POST["cmbFase"]."',clasifican=".$_POST["clasi"]." WHERE id = ".$_POST[ "bthValId" ];
		$result = $connect->query( $query );
		if( $result == 1){		
			echo json_encode(array('error'=>false,'description'=>'Registro Actualizado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Actualizar Registro'));
		}
	}
}
if ( $action == 'getDataGrupo' ) {	
	$id = $_GET[ 'id' ];
	$comp = mysqli_fetch_array( $connect->query( "SELECT g.*, f.id_competicion FROM grupo g JOIN fase f ON g.id_fase = f.id and g.id=".$id ));
	echo json_encode($comp);
}
if ( $action == 'delGrupo' ) {	
	$id = $_GET[ 'id' ];
	$sql = "DELETE FROM grupo WHERE id=".$id;	
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro'));
	}
}
if ( $action == 'addUpdEqui' ) {	
	$bthAction = 0;
	$idComp = 0;
	if(isset($_POST[ "bthAction"])){$bthAction=$_POST[ "bthAction" ];}
	if(isset($_POST[ "cmbComp"])){$idComp=$_POST[ "cmbComp" ];}
	if( $bthAction == 1 ){ //insert data
		$query = "INSERT INTO equipo (nombre,color,id_usuario,fecha, id_competicion) VALUES ('" . $_POST[ "nombre" ] ."','".$_POST[ "color" ]."','".$_POST[ "cmbUser" ]."'"." ,NOW() ,".$idComp.")";
		$result = $connect->query( $query );
		if( $result == 1){		
			echo json_encode(array('error'=>false,'description'=>'Registro Creado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Crear Registro'));
		}
	}else if( $bthAction == 2 ){ //update data
		$query = "UPDATE equipo SET nombre='".$_POST["nombre"]."',color='".$_POST["color"]."',id_usuario='".$_POST[ "cmbUser" ]."',id_competicion=".$idComp." WHERE id = ".$_POST[ "bthValId" ];
		$result = $connect->query( $query );
		if( $result == 1){		
			//update juego:
			$result = $connect->query( "UPDATE juego SET nombre1='".$_POST["nombre"]."' WHERE id_equipo_1 = ".$_POST[ "bthValId" ] );
			$result = $connect->query( "UPDATE juego SET nombre2='".$_POST["nombre"]."' WHERE id_equipo_2 = ".$_POST[ "bthValId" ] );
			echo json_encode(array('error'=>false,'description'=>'Registro Actualizado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Actualizar Registro'));
		}
	}
}
if ( $action == 'getDataEqui' ) {	
	$id = $_GET[ 'id' ];
	$comp = mysqli_fetch_array( $connect->query( "SELECT * FROM equipo WHERE id=".$id ));
	echo json_encode($comp);
}
if ( $action == 'delEqui' ) {	
	$id = $_GET[ 'id' ];
	$sql = "DELETE FROM equipo WHERE id=".$id;	
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro'));
	}
}
if ( $action == 'addUpdJug' ) {
	$bthAction = 0;
	$delegado = 0;
	$foto = '../images/fotosJugadores/default.png';	
	if(isset($_POST[ "delegado" ])){$delegado=1;}
	if(isset($_POST[ "bthAction"])){$bthAction=$_POST[ "bthAction" ];}
	if( $bthAction == 1 ){ //insert data
		if( !empty ($_FILES['foto']['name']) ){
			//Upload foto:
			$sourcePathFoto = $_FILES['foto']['tmp_name'];		
			$targetPathFoto = "../images/fotosJugadores/".$_POST[ 'documento' ].date("YmdHms").".png"; 
			move_uploaded_file($sourcePathFoto,$targetPathFoto) ;
			$foto = $targetPathFoto;
		}	
		$query = "INSERT INTO jugador (documento,nombres,apellidos,numero,telefono,correo,id_equipo,goles,es_delegado,url_foto) VALUES ('" . $_POST[ "documento" ] ."','".$_POST["nombres"]."','".$_POST["apellidos"]."','".$_POST["numero"]."','".$_POST["telefono"]."','".$_POST["correo"]."','".$_POST["bthValEqui"]."','".'0'."','".$delegado."','".$foto."'".")";

		$result = $connect->query( $query );
		if( $result == 1){		
			echo json_encode(array('error'=>false,'description'=>'Registro Creado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Crear Registro'));
		}
	}else if( $bthAction == 2 ){ //update data
		$delegado = 0;
		if(isset($_POST[ "delegado" ])){$delegado=1;}
		$foto;
		if( !empty ($_FILES['foto']['name']) ){
			//Upload foto:
			$sourcePathFoto = $_FILES['foto']['tmp_name'];		
			$targetPathFoto = "../images/fotosJugadores/".$_POST[ 'documento' ].date("YmdHms").".png"; 
			move_uploaded_file($sourcePathFoto,$targetPathFoto) ;
			$foto = $targetPathFoto;
			//Delete old foto from server:
			$jugador = mysqli_fetch_array($connect->query( "select * from jugador WHERE id=".$_POST[ "bthValId" ] ));
			if(!strpos($jugador['url_foto'], 'default.png')){
				unlink($jugador[ 'url_foto']);
			}
		}	
		$query = "UPDATE jugador SET documento='".$_POST["documento"]."',nombres='".$_POST["nombres"]."',apellidos='".$_POST["apellidos"]."',numero='".$_POST["numero"]."',telefono='".$_POST["telefono"]."',correo='".$_POST["correo"]."',es_delegado='".$delegado."' WHERE id = ".$_POST[ "bthValId" ];
		$result = $connect->query( $query );
		//update foto:		
		if(!empty($foto)){
			$query2 = "UPDATE jugador SET url_foto='".$foto."' WHERE id = ".$_POST[ "bthValId"];
			$result = $connect->query( $query2 );
		}		
		if( $result == 1){		
			echo json_encode(array('error'=>false,'description'=>'Registro Actualizado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Actualizar Registro'));
		}
	}
}
if ( $action == 'getDataJug' ) {	
	$id = $_GET[ 'id' ];
	$comp = mysqli_fetch_array( $connect->query( "SELECT * FROM jugador WHERE id=".$id ));
	echo json_encode($comp);
}
if ( $action == 'addEquiGru' ) {
	$idGrupo = $_POST[ "idGrupHid"];
	if(isset($_POST[ "checkbox"]) ){
		foreach (array_values( $_POST[ 'checkbox' ] ) as $valor) {
			$query = "INSERT INTO equipo_grupo (id_equipo,id_grupo,JUG,GAN,EMP,PER,GAF,GEC,DIF,PTS) VALUES (".$valor.",".$idGrupo.",0,0,0,0,0,0,0,0)";
			$result = $connect->query( $query );
		}		
	}
	echo json_encode(array('error'=>false,'description'=>'Registros Creados'));
}
if ( $action == 'delEquiGru' ) {	
	$id = $_GET[ 'id' ];
	//validate juegos:
	$juego = mysqli_fetch_array($connect->query( "select count(1) total from juego where id_equipo_1 = (select id_equipo  FROM equipo_grupo WHERE id= ".$id." and PTS <> 0 limit 1) OR id_equipo_2=(select id_equipo  FROM equipo_grupo WHERE id= ".$id." and PTS <> 0 limit 1) and j.id_fase=".$fase['id_fase']));	
	if( $juego['total']<1 ){
		$sql = "DELETE FROM equipo_grupo WHERE id=".$id;	
		$result = $connect->query( $sql );
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro, Ya tiene juegos asignados'));
	}
}
if ( $action == 'genGames' ) {	
	$idFase = $_GET[ 'idFase' ];
	$idaYvuelta = $_GET[ 'idaYvuelta' ];
	$resultGrupos = $connect->query( "select g.* from grupo g JOIN fase f ON g.id_fase = f.id AND f.id = ".$idFase." order by nombre asc" );
	while($rowGru = mysqli_fetch_array($resultGrupos)){
		//quipos del grupo:
		$resultEquipos = $connect->query( "select id_equipo from equipo_grupo eg JOIN equipo e ON eg.id_equipo = e.id WHERE eg.id_grupo =".$rowGru['id'] );
		//Generar Array Equipos:
		$arrayEquipos = array();
		while ($row = mysqli_fetch_array($resultEquipos)){
			 $arrayEquipos[] = $row['id_equipo'];
		}
		//Generar encuentros solo ida:
		// If odd number of teams add a "ghost".
		if (count($arrayEquipos) % 2 == 1) {
			$arrayEquipos[] = "DESCANSA";
		}
		$totalTeams = count($arrayEquipos);
		$totalRounds = $totalTeams - 1;
		$matchesPerRound = $totalTeams / 2;
		$rounds = array();
		for ($round = 0; $round < $totalRounds; $round++) {
			$maches = array();
			for ($match = 0; $match < $matchesPerRound; $match++) {
				$home = ($round + $match) % ($totalTeams - 1);
				$away = ($totalTeams - 1 - $match + $round) % ($totalTeams - 1);
				// Last team stays in the same place while the others
				// rotate around it.
				if ($match == 0) {
					$away = $totalTeams - 1;
				}			
				$maches[] = $arrayEquipos[$home].' Vs '.$arrayEquipos[$away];
			}
			$rounds[] = $maches;
		}
		// Interleave so that home and away games are fairly evenly dispersed.
		$interleaved = array();
		$evn = 0;
		$odd = ($totalTeams / 2);
		for ($i = 0; $i < count($rounds); $i++) {
			if ($i % 2 == 0) {
				$interleaved[$i] = $rounds[$evn++];
			} else {
				$interleaved[$i] = $rounds[$odd++];
			}
		}
		$rounds = $interleaved;  
		// Last team can't be away for every game so flip them
		// to home on odd rounds.
		for ($round = 0; $round < count($rounds); $round++) {
			if ($round % 2 == 1) {
				$components = preg_split('/ Vs /', ($rounds[$round][0]));			
				$encuentro = $components[1] . " Vs " . $components[0];
				$rounds[$round][0] = $encuentro;
			}
		}
		//insertar juegos ida:
		for ($fila = 0; $fila < count($rounds); $fila++) {
			for ($columna = 0; $columna < count($rounds[$fila]); $columna++) {
				$idsEquipos = preg_split('/ Vs /', ($rounds[$fila][$columna]));
				if( $idsEquipos[0] != 'DESCANSA' && $idsEquipos[1] != 'DESCANSA'){
					//validar si ya existe el juego:
					$juegoExiste = mysqli_fetch_array($connect->query("select count(1) total from juego where id_equipo_1 = ".$idsEquipos[0]." and id_equipo_2 = ".$idsEquipos[1]." and id_grupo = ".$rowGru['id']." and id_fase = ".$idFase));
					if( $juegoExiste['total'] < 1 ){	//el juego no existe se crea:
						$local = mysqli_fetch_array($connect->query("select * from equipo where id = ".$idsEquipos[0]));
						$visitante = mysqli_fetch_array($connect->query("select * from equipo where id = ".$idsEquipos[1]));
						$query = "INSERT INTO juego (id_equipo_1,nombre1,id_equipo_2,nombre2, tipo,id_grupo, id_fase, jornada) VALUES (".$local['id'].",'".$local['nombre']."',".$visitante['id'].",'".$visitante['nombre']."','OFICIAL',".$rowGru['id'].",".$idFase.",".($fila+1).")";
						$result = $connect->query( $query );
					}
				}
			}				
		}
		if($idaYvuelta == 1){
			//insertar juegas vuelta:
			$jornada = count($rounds)+1;
			for ($fila = 0; $fila < count($rounds); $fila++) {
				for ($columna = 0; $columna < count($rounds[$fila]); $columna++) {
					$idsEquipos = preg_split('/ Vs /', ($rounds[$fila][$columna]));
					if( $idsEquipos[0] != 'DESCANSA' && $idsEquipos[1] != 'DESCANSA'){
						//validar si ya existe el juego:
						$juegoExiste = mysqli_fetch_array($connect->query("select count(1) total from juego where id_equipo_1 = ".$idsEquipos[1]." and id_equipo_2 = ".$idsEquipos[0]." and id_grupo = ".$rowGru['id']." and id_fase = ".$idFase));
						if( $juegoExiste['total'] < 1 ){	//el juego no existe se crea:
							$local = mysqli_fetch_array($connect->query("select * from equipo where id = ".$idsEquipos[1]));
							$visitante = mysqli_fetch_array($connect->query("select * from equipo where id = ".$idsEquipos[0]));
							$query = "INSERT INTO juego (id_equipo_1,nombre1,id_equipo_2,nombre2, tipo,id_grupo, id_fase, jornada) VALUES (".$local['id'].",'".$local['nombre']."',".$visitante['id'].",'".$visitante['nombre']."','OFICIAL',".$rowGru['id'].",".$idFase.",".($jornada).")";
							$result = $connect->query( $query );
						}
					}			
				}	
				$jornada++;	
			}
		}			
	}
	echo json_encode(array('error'=>false,'description'=>'Juegos Generados'));
}
if ( $action == 'delJuegos' ) {	
	$idFase = $_GET[ 'idFase' ];
	$resultGrupos = $connect->query( "select j.id from fase f JOIN grupo g ON f.id = g.id_fase AND f.activa = 1 and f.id = ".$idFase." JOIN equipo_grupo eg ON g.id = eg.id_grupo JOIN juego j ON eg.id_equipo = j.id_equipo_1");
	while($rowGru = mysqli_fetch_array($resultGrupos)){
		$result = $connect->query( "DELETE FROM juego WHERE id=".$rowGru['id'] );
	}
	echo json_encode(array('error'=>false,'description'=>'Registros Eliminados'));	
}
if ( $action == 'getDataJuego' ) {	
	$idJuego = $_GET[ 'idJuego' ];
	$comp = mysqli_fetch_array( $connect->query( "SELECT * FROM juego WHERE id=".$idJuego ));
	echo json_encode($comp);
}
if ( $action == 'programJuego' ) {
	$error = false;
	$descError = "";
	$alerta = false;
	$descAlerta = "";
	$fechaV = $_POST["fecha"];
	//validar cruce horario juegos equipo:	
	$game = mysqli_fetch_array( $connect->query("select * from juego WHERE id =".$_POST[ "bthValId" ]) );
	$resultGames = null;
	if( $game['tipo'] == 'OFICIAL' ){
		$resultGames = $connect->query("SELECT *,DATE_FORMAT(fecha,'%Y-%m-%d') fechaF FROM juego WHERE (id_equipo_1=".$game[ "id_equipo_1" ]." OR id_equipo_2=".$game[ "id_equipo_1" ].") and DATE_FORMAT(fecha,'%d-%m-%Y') = DATE_FORMAT('".$_POST["fecha"]."','%d-%m-%Y')");	
	}else{		
		$resultGames = $connect->query("SELECT *,DATE_FORMAT(fecha,'%Y-%m-%d') fechaF FROM juego WHERE (nombre1='".$game[ "nombre1" ]."' OR nombre2='".$game[ "nombre2" ]."') and DATE_FORMAT(fecha,'%d-%m-%Y') = DATE_FORMAT('".$_POST["fecha"]."','%d-%m-%Y')");
	}	
	while ( $rowGam = mysqli_fetch_array( $resultGames ) ) {		
		$hIniV = empty($rowGam['hora_inicio'])?null:explode(":",$rowGam['hora_inicio'])[0];
		$hFinV = empty($rowGam['hora_fin'])?null:explode(":",$rowGam['hora_fin'])[0];
		if( $hIniV!= null && $hFinV!= null ){
			if( $hIniV >= $_POST["cmbHoraInicio"] && $hIniV <= $_POST["cmbHoraFin"]){
				$error =true;
				$descError = "No se puede programar uno de los equipos ya juega a esa hora en esa fecha";
			}
			if( $hFinV <= $_POST["cmbHoraFin"] && $hIniV >= $_POST["cmbHoraInicio"]){
				$error =true;
				$descError = "No se puede programar uno de los equipos ya juega a esa hora en esa fecha";
			}
			if( $rowGam['fechaF'] == $_POST["fecha"]){
				$alerta =true;
				$descAlerta = "Atención uno de los equipo ya tiene juego en esa fecha";
			}
			
		}	
	}
	//validar escenarios
	$resultGamesDate =  $connect->query("SELECT * from juego WHERE DATE_FORMAT(fecha,'%d-%m-%Y') = DATE_FORMAT('".$_POST["fecha"]."','%d-%m-%Y')") ;
	while ( $rowGamDay = mysqli_fetch_array( $resultGamesDate ) ) {
		$hIniV = empty($rowGamDay['hora_inicio'])?null:explode(":",$rowGamDay['hora_inicio'])[0];
		$hFinV = empty($rowGamDay['hora_fin'])?null:explode(":",$rowGamDay['hora_fin'])[0];
		if( $hIniV >= $_POST["cmbHoraInicio"] && $hIniV <= $_POST["cmbHoraFin"] && $rowGamDay['id_escenario'] == $_POST["cmbEscena"] ){
			$error =true;
			$descError = "No se puede programar, ya existe un juego en el escenario a esa hora para esa fecha";
		}
		if( $hFinV <= $_POST["cmbHoraFin"] && $hIniV >= $_POST["cmbHoraInicio"] && $rowGamDay['id_escenario'] == $_POST["cmbEscena"]){
			$error =true;
			$descError = "No se puede programar, ya existe un juego en el escenario a esa hora para esa fecha";
		}
	}		
	
	if( !$error ){
		$horaInicio = $_POST["cmbHoraInicio"].":".$_POST["minInicio"].":".$_POST["cmbAmpmInicio"];
		$horaFin = $_POST["cmbHoraFin"].":".$_POST["minFin"].":".$_POST["cmbAmpmFin"];
		$fecha = $_POST["fecha"];
		$escenario = $_POST["cmbEscena"];
		$query = "UPDATE juego SET fecha='".$fecha."',hora_inicio='".$horaInicio."',hora_fin='".$horaFin."',id_escenario=".$escenario." WHERE id = ".$_POST[ "bthValId" ];
		$result = $connect->query( $query );
		if($result == 1 && $alerta ){
			echo json_encode(array('error'=>false,'description'=>'Registro Programado','alerta'=>true,'descAlerta'=>$descAlerta));
		}else if( $result == 1 && !$alerta ){
			echo json_encode(array('error'=>false,'description'=>'Registro Programado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Programar el Registro'));
		}
	}else{
		echo json_encode(array('error'=>true,'description'=>$descError));	
	}	
}
if ( $action == 'clearProg' ) {	
	$id = $_GET[ 'id' ];
	$result = $connect->query( "update juego SET fecha= null, hora_inicio=null, hora_fin=null, id_escenario = null WHERE id=".$id);	
	echo json_encode(array('error'=>false,'description'=>'Registros Restablecido'));	
}
if ( $action == 'addManualGame' ) {
	if(isset($_POST['array'])){
		$array = json_decode($_POST['array']);
		$team1 = mysqli_fetch_array( $connect->query("select * from equipo WHERE id =".$array[0]) );
		$team2 = mysqli_fetch_array( $connect->query("select * from equipo WHERE id =".$array[1]) );
		$query = "INSERT INTO juego (id_equipo_1,id_equipo_2,nombre1,nombre2,tipo) VALUES (".$team1['id'].",".$team2['id'].",'".$team1['nombre']."','".$team2['nombre']."','OFICIAL')";		
		$result = $connect->query( $query );
	}
	echo json_encode(array('error'=>false,'description'=>'Registros Creados'));
}
if ( $action == 'delJuego' ) {	
	$id = $_GET[ 'id' ];
	$sql = "DELETE FROM juego WHERE id=".$id;	
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro'));
	}
}
if ( $action == 'addFreeGame' ) {
	$query = "INSERT INTO juego (nombre1,nombre2,tipo) VALUES ('".$_POST["nombre1"]."','".$_POST["nombre2"]."','AMISTOSO')";	
	$result = $connect->query( $query );	
	echo json_encode(array('error'=>false,'description'=>'Registros Creados'));
}
if ( $action == 'delFreeGames' ) {	
	$sql = "DELETE FROM juego WHERE tipo='AMISTOSO'";	
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registros Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registros'));
	}
}
if ( $action == 'addUpdUser' ) {
	$bthAction = 0;
	$activo = 0;
	if(isset($_POST[ "activo" ])){$activo=1;}
	$foto = '../images/fotosUsuarios/default.png';	
	if(isset($_POST[ "bthAction"])){$bthAction=$_POST[ "bthAction" ];}
	if( $bthAction == 1 ){ //insert data
		if( !empty ($_FILES['foto']['name']) ){
			//Upload foto:
			$sourcePathFoto = $_FILES['foto']['tmp_name'];		
			$targetPathFoto = "../images/fotosUsuarios/".$_POST[ 'nombres' ].date("YmdHms").".png"; 
			move_uploaded_file($sourcePathFoto,$targetPathFoto) ;
			$foto = $targetPathFoto;
		}	
		$query = "INSERT INTO usuario (nombres,apellidos,telefono,correo,password,url_foto,fecha_creacion,usuario_creador,activo) VALUES ('" . $_POST[ "nombres" ] ."','".$_POST["apellidos"]."','".$_POST["telefono"]."','".$_POST["correo"]."','".$_POST["password"]."','".$foto."'".",NOW(),".$_SESSION[ 'dataSession' ]['id'].",".$activo.")";
		$result = $connect->query( $query );
		if( $result == 1){
			//create profile:
			$lastId = mysqli_fetch_array($connect->query( "SELECT MAX(id) id FROM usuario " ));
			$connect->query( "INSERT INTO usuario_perfil (id_usuario,perfil) VALUES (".$lastId['id'].",'".$_POST["cmbPerfil"]."')" );			
			echo json_encode(array('error'=>false,'description'=>'Registro Creado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Crear Registro'));
		}
	}else if( $bthAction == 2 ){ //update data
		$activo = 0;
		if(isset($_POST[ "activo" ])){$activo=1;}
		$foto;
		if( !empty ($_FILES['foto']['name']) ){
			//Upload foto:
			$sourcePathFoto = $_FILES['foto']['tmp_name'];		
			$targetPathFoto = "../images/fotosUsuarios/".$_POST[ 'nombres' ].date("YmdHms").".png"; 
			move_uploaded_file($sourcePathFoto,$targetPathFoto) ;
			$foto = $targetPathFoto;
			//Delete old foto from server:
			$user = mysqli_fetch_array($connect->query( "select * from usuario WHERE id=".$_POST[ "bthValId" ] ));
			if(!strpos($user['url_foto'], 'default.png')){
				unlink($user[ 'url_foto']);
			}
		}	
		$query = "UPDATE usuario SET nombres='".$_POST["nombres"]."',apellidos='".$_POST["apellidos"]."',telefono='".$_POST["telefono"]."',correo='".$_POST["correo"]."',password='".$_POST["password"]."',activo='".$activo."',usuario_modificador='".$_SESSION[ 'dataSession' ]['id']."',fecha_modificacion=NOW() WHERE id = ".$_POST[ "bthValId" ];
		$result = $connect->query( $query );
		//update foto:		
		if(!empty(!empty ($_FILES['foto']['name']))){
			$query2 = "UPDATE usuario SET url_foto='".$foto."' WHERE id = ".$_POST[ "bthValId"];
			$result = $connect->query( $query2 );
		}
		//update perfil:		
		$query2 = "UPDATE usuario_perfil SET perfil='".$_POST["cmbPerfil"]."' WHERE id_usuario = ".$_POST[ "bthValId"];
		$result = $connect->query( $query2 );			
		if( $result == 1){		
			echo json_encode(array('error'=>false,'description'=>'Registro Actualizado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Actualizar Registro'));
		}
	}
}
if ( $action == 'getDataUser' ) {	
	$id = $_GET[ 'id' ];
	$comp = mysqli_fetch_array( $connect->query( "select u.*, up.perfil from usuario u JOIN usuario_perfil up ON u.id = up.id_usuario  AND u.id=".$id ));
	echo json_encode($comp);
}
if ( $action == 'delUser' ) {	
	$id = $_GET[ 'id' ];
	//Delete old foto from server:
	$user = mysqli_fetch_array($connect->query( "select * from usuario WHERE id=".$id ));
	if(!strpos($user['url_foto'], 'default.png')){
		unlink($user[ 'url_foto']);
	}
	$sql = "DELETE FROM usuario WHERE id=".$id;	
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro'));
	}
}
if ( $action == 'inscriptionEqui' ) {
	$idComp = $_GET[ "idComp"];
	if(isset($_POST[ "checkbox"]) ){
		foreach (array_values( $_POST[ 'checkbox' ] ) as $valor) {
			$query = "INSERT INTO inscripcion (fecha,id_competicion,id_equipo) VALUES (NOW(),".$idComp.",".$valor.")";
			$result = $connect->query( $query );
		}		
	}
	echo json_encode(array('error'=>false,'description'=>'Registros Creados'));
}
if ( $action == 'delInscrip' ) {	
	$id = $_GET[ 'id' ];
	$sql = "DELETE FROM inscripcion WHERE id=".$id;	
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro'));
	}
}
if ( $action == 'delPlayer' ) {	
	$id = $_GET[ 'id' ];
	$sql = "DELETE FROM jugador WHERE id=".$id;
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro'));
	}
}
if ( $action == 'addGol' ) {
	if(isset($_POST['array'])){
		$array = json_decode($_POST['array']);
		foreach ($array as &$valor) {
			//find equipo:
			$equipo = mysqli_fetch_array( $connect->query( "select e.* from jugador j join equipo e on j.id_equipo = e.id and j.id = ".$valor." limit 1") );			
			$query = "INSERT INTO gol (id_jugador,id_juego,minuto,valor,id_equipo) VALUES (".$valor.",".$_GET["idJuego"].",".$_GET["minutog"].",".$_GET["cmbCantgol"].",".$equipo['id'].")";	
			$result = $connect->query( $query );	
		}
	}
	echo json_encode(array('error'=>false,'error'=>false,'description'=>'Registros Creados'));
}
if ( $action == 'delGol' ) {	
	$id = $_GET[ 'id' ];
	$sql = "DELETE FROM gol WHERE id=".$id;
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro'));
	}
}
if ( $action == 'addSan' ) {
	if(isset($_POST['array'])){		
		$array = json_decode($_POST['array']);
		$id_tipo_sancion = $_GET["cmbSan"];
		$minuto_Sancion = $_GET["minutos"];
		$tipoSancion = mysqli_fetch_array( $connect->query( "select * from tipo_sancion where id = ".$id_tipo_sancion ));
		$juego = mysqli_fetch_array( $connect->query( "select * from juego where id = ".$_GET["idJuego"] ));
		foreach ($array as &$valor) { //por cada id_jugador:			
			$query = "INSERT INTO sancion (id_jugador,id_juego,minuto,id_tipo_sancion,jornada_sancion, fecha) VALUES (".$valor.",".$juego['id'].",".$minuto_Sancion.",".$id_tipo_sancion.",".$juego['jornada'].",NOW())";
			$connect->query( $query );							
			//validar vetar jugador:
			if($tipoSancion['veta_jugador']){
				$jugador = mysqli_fetch_array( $connect->query( "select * from jugador where id = ".$valor ));
				$connect->query( "INSERT INTO jugador_vetado (documento_jugador,fecha) VALUES (".$jugador['documento'].",NOW())" );
			}
		}
	}
	echo json_encode(array('error'=>false,'description'=>'Registros Creados'));
}
if ( $action == 'delSan' ) {	
	$id = $_GET[ 'id' ];
	$sql = "DELETE FROM sancion WHERE id=".$id;
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro'));
	}
}
if ( $action == 'addAsis' ) {
	$idJuego = $_GET["idJuego"];
	if(isset($_POST['array'])){
		$array = json_decode($_POST['array']);
		foreach ($array as &$valor) {
			$query = "INSERT INTO asistencia (id_jugador,id_juego) VALUES (".$valor.",".$idJuego.")";
			$result = $connect->query( $query );	
		}
	}
	echo json_encode(array('error'=>false,'description'=>'Registros Creados'));
}
if ( $action == 'delAsis' ) {
	$idJuego = $_GET["idJuego"];
	$array = json_decode($_POST['array']);
	$query = "DELETE FROM asistencia WHERE id_juego =".$idJuego." AND id_jugador IN(".implode(",", $array).")";
	$result = $connect->query( $query );
	echo json_encode(array('error'=>false,'description'=>'Registros Creados'));
}
if ( $action == 'saveInforme' ) {
	$idJuego = $_GET["idJuego"];
	$idFase = $_GET["idFase"];
	//actualziar informe arbitral:
	$connect->query( "UPDATE juego SET informe ='".$_POST["informe"]."', informado=1 WHERE id=".$idJuego);
	//recalcular puntuaciones de equipos:
	updateTableGroup($idFase,$idJuego);	
	echo json_encode(array('error'=>false,'description'=>'Informe Guardado y Tabla Actualizada'));	
}
function updateTableGroup( $idFase,$idJuego ) {	
	require '../conexion.php';
	//consultar equipo grupo actual equipo_1:
	$equipog1 = mysqli_fetch_array( $connect->query( "select eg.* from juego j JOIN equipo_grupo eg ON j.id_equipo_1 = eg.id_equipo and j.id =".$idJuego." JOIN grupo g ON eg.id_grupo = g.id join fase f on g.id_fase = f.id and f.id=".$idFase ) );
	//consultar equipo grupo actual equipo_2:
	$equipog2 = mysqli_fetch_array( $connect->query( "select eg.* from juego j JOIN equipo_grupo eg ON j.id_equipo_2 = eg.id_equipo and j.id =".$idJuego." JOIN grupo g ON eg.id_grupo = g.id join fase f on g.id_fase = f.id and f.id=".$idFase ) );
	
	//actualizar partidos jugados equipo_1:
	$connect->query("UPDATE equipo_grupo SET JUG = ( SELECT x.cant FROM (select count(1) as cant from juego j join equipo_grupo eg ON (j.id_equipo_1 = ".$equipog1['id_equipo']." or j.id_equipo_2 = ".$equipog1['id_equipo'].") and j.id_fase = ".$idFase."  AND eg.id_equipo = ".$equipog1['id_equipo']." and j.informado is not null join grupo g  On eg.id_grupo = g.id join fase f on g.id_fase = f.id and f.id = ".$idFase.") AS x) where id =".$equipog1['id']);
	
	//actualizar partidos jugados equipo_2:
	$connect->query("UPDATE equipo_grupo SET JUG = ( SELECT x.cant FROM (select count(1) as cant from juego j join equipo_grupo eg ON (j.id_equipo_1 = ".$equipog2['id_equipo']." or j.id_equipo_2 = ".$equipog2['id_equipo'].") and j.id_fase = ".$idFase." AND eg.id_equipo = ".$equipog2['id_equipo']." and j.informado is not null join grupo g  On eg.id_grupo = g.id join fase f on g.id_fase = f.id and f.id = ".$idFase.") AS x) where id =".$equipog2['id']);
		
	//contar goles del juego para equipo_1
	$goles1 = mysqli_fetch_array($connect->query( "select ifnull(sum(g.valor),0) total from gol g where g.id_juego = ".$idJuego." and g.id_equipo =".$equipog1['id_equipo']));
	//contar goles del juego para equipo_2
	$goles2 = mysqli_fetch_array($connect->query( "select ifnull(sum(g.valor),0) total from gol g where g.id_juego = ".$idJuego." and g.id_equipo =".$equipog2['id_equipo']));
	
	$partidosGanados1 = 0;
	$partidosPerdidos1 = 0;
	$partidosEmpatados1 = 0;
	$partidosGanados2 = 0;
	$partidosPerdidos2 = 0;
	$partidosEmpatados2 = 0;

	if( $goles1['total'] > $goles2['total'] ){ //GANADOR LOCAL
		//actualizar juego ganador equipo_1:
		$connect->query("update juego set id_ganador =".$equipog1['id_equipo']." where id=".$idJuego);		
	}else if($goles1['total'] < $goles2['total']){ //GANADOR VISITANTE
		//actualizar juego ganador equipo_2:
		$connect->query("update juego set id_ganador =".$equipog2['id_equipo']." where id=".$idJuego);		
	}else if($goles1['total'] == $goles2['total']){ //EMPATE
		//actualizar juego ganador empate:
		$connect->query("update juego set id_ganador = 0 where id=".$idJuego);		
	}
	//contar cuantos juegos ganados tiene hasta ahora equipo_1 en el grupo de la fase
	$ganados1 = mysqli_fetch_array($connect->query( "select count(1) total from juego j join equipo_grupo eg on j.id_ganador = ".$equipog1['id_equipo']." and j.id_fase = ".$idFase." and j.id_ganador = eg.id_equipo join grupo g on eg.id_grupo = g.id join fase f on g.id_fase = f.id and f.id = ".$idFase ));
	//actualizar partidos ganados equipo_1:
	$connect->query("update equipo_grupo set GAN = (".$ganados1['total'].") where id =".$equipog1['id']);
	$partidosGanados1 = $ganados1['total'];
	//contar cuantos juegos perdidos tiene hasta ahora equipo_2 en el grupo de la fase
	$perdidos2 = mysqli_fetch_array($connect->query( "select count(1) total from juego j join equipo_grupo eg on eg.id_equipo = ".$equipog2['id_equipo']." and j.id_fase = ".$idFase." and (j.id_equipo_1 = ".$equipog2['id_equipo']." or j.id_equipo_2 = ".$equipog2['id_equipo'].") and j.id_ganador <>".$equipog2['id_equipo']." and j.id_ganador <> 0 join grupo g on eg.id_grupo = g.id join fase f on g.id_fase = f.id and f.id = ".$idFase ));	
	//actualizar partidos perdidos equipo_2:
	$connect->query("update equipo_grupo set PER = (".$perdidos2['total'].") where id =".$equipog2['id']);
	$partidosPerdidos2 = $perdidos2['total'];
	
	//contar cuantos juegos ganados tiene hasta ahora equipo_2 en el grupo de la fase
	$ganados2 = mysqli_fetch_array($connect->query( "select count(1) total from juego j join equipo_grupo eg on j.id_ganador = ".$equipog2['id_equipo']." and j.id_fase = ".$idFase." and j.id_ganador = eg.id_equipo join grupo g on eg.id_grupo = g.id join fase f on g.id_fase = f.id and f.id = ".$idFase ));
	//actualizar partidos ganados equipo_2:
	$connect->query("update equipo_grupo set GAN = (".$ganados2['total'].") where id =".$equipog2['id']);
	$partidosGanados2 = $ganados2['total'];
	//contar cuantos juegos perdidos tiene hasta ahora equipo_1 en el grupo de la fase
	$perdidos1 = mysqli_fetch_array($connect->query( "select count(1) total from juego j join equipo_grupo eg on eg.id_equipo = ".$equipog1['id_equipo']." and j.id_fase = ".$idFase." and (j.id_equipo_1 = ".$equipog1['id_equipo']." or j.id_equipo_2 = ".$equipog1['id_equipo'].") and j.id_ganador <>".$equipog1['id_equipo']." and j.id_ganador <> 0 join grupo g on eg.id_grupo = g.id join fase f on g.id_fase = f.id and f.id = ".$idFase ));	
	//actualizar partidos perdidos equipo_2:
	$connect->query("update equipo_grupo set PER = (".$perdidos1['total'].") where id =".$equipog1['id']);
	$partidosPerdidos1 = $perdidos1['total'];
	
	//contar cuantos EMPATES tiene hasta ahora equipo_1 en el grupo de la fase
	$empates1 = mysqli_fetch_array($connect->query( "select count(1) total from juego j join equipo_grupo eg on j.id_ganador = 0 and j.id_fase = ".$idFase." and (j.id_equipo_1 = ".$equipog1['id_equipo']." or j.id_equipo_2 = ".$equipog1['id_equipo'].") and (eg.id_equipo = j.id_equipo_1 or eg.id_equipo = j.id_equipo_2 ) join grupo g on eg.id_grupo = g.id  join fase f on g.id_fase = f.id and eg.id_equipo = ".$equipog1['id_equipo']." and f.id = ".$idFase));			
	//contar cuantos EMPATES tiene hasta ahora equipo_2
	$empates2 = mysqli_fetch_array($connect->query( "select count(1) total from juego j join equipo_grupo eg on j.id_ganador = 0 and j.id_fase = ".$idFase." and (j.id_equipo_1 = ".$equipog2['id_equipo']." or j.id_equipo_2 = ".$equipog2['id_equipo'].") and (eg.id_equipo = j.id_equipo_1 or eg.id_equipo = j.id_equipo_2 ) join grupo g on eg.id_grupo = g.id  join fase f on g.id_fase = f.id and eg.id_equipo = ".$equipog2['id_equipo']." and f.id = ".$idFase));		
	//actualizar partidos empates equipo_1:
	$connect->query("update equipo_grupo set EMP = (".$empates1['total'].") where id =".$equipog1['id']);
	//actualizar partidos empates equipo_2:
	$connect->query("update equipo_grupo set EMP = (".$empates2['total'].") where id =".$equipog2['id']);
	$partidosEmpatados1 = $empates1['total'];
	$partidosEmpatados2 = $empates2['total'];
		
	//contar cuantos goles tiene hasta ahora el equipo_1 en el grupo de la fase:
	$golesTotal1 = mysqli_fetch_array($connect->query( "select ifnull(sum(g.valor),0) total from gol g join juego j ON g.id_juego = j.id and j.id_fase = ".$idFase." and g.id_equipo = ".$equipog1['id_equipo']." join equipo_grupo eg on eg.id_equipo = g.id_equipo join grupo gr ON eg.id_grupo = gr.id and gr.id_fase =".$idFase));	
	//actualizar GAF equipo_1:	
	$connect->query("update equipo_grupo set GAF = (".$golesTotal1['total'].") where id =".$equipog1['id']);
	
	//contar cuantos goles tiene hasta ahora el equipo_2 en el grupo de la fase:
	$golesTotal2 = mysqli_fetch_array($connect->query( "select ifnull(sum(g.valor),0) total from gol g join juego j ON g.id_juego = j.id and j.id_fase = ".$idFase." and g.id_equipo = ".$equipog2['id_equipo']." join equipo_grupo eg on eg.id_equipo = g.id_equipo join grupo gr ON eg.id_grupo = gr.id and gr.id_fase =".$idFase));	
	//actualizar GAF equipo_2:	
	$connect->query("update equipo_grupo set GAF = (".$golesTotal2['total'].") where id =".$equipog2['id']);
	
	//contar cuantos goles en contra tiene hasta ahora el equipo_1 en el grupo de la fase:
	$golesContraTotal1 = mysqli_fetch_array($connect->query( "select ifnull(sum(g.valor),0) total from gol g  join equipo_grupo eg ON g.id_equipo = eg.id_equipo and g.id_equipo <> ".$equipog1['id_equipo']."  join grupo gr ON eg.id_grupo = gr.id and gr.id_fase = ".$idFase." and g.id_juego in(select id from juego where (id_equipo_1 = ".$equipog1['id_equipo']." or id_equipo_2 = ".$equipog1['id_equipo'].") and id_fase = ".$idFase." )" ));	
	//actualizar GEC equipo_1:	
	$connect->query("update equipo_grupo set GEC = (".$golesContraTotal1['total'].") where id =".$equipog1['id']);
	
	//contar cuantos goles en contra tiene hasta ahora el equipo_2 en el grupo de la fase:
	$golesContraTotal2 = mysqli_fetch_array($connect->query( "select ifnull(sum(g.valor),0) total from gol g  join equipo_grupo eg ON g.id_equipo = eg.id_equipo and g.id_equipo <> ".$equipog2['id_equipo']."  join grupo gr ON eg.id_grupo = gr.id and gr.id_fase = ".$idFase." and g.id_juego in(select id from juego where (id_equipo_1 = ".$equipog2['id_equipo']." or id_equipo_2 = ".$equipog2['id_equipo'].") and id_fase = ".$idFase.")" ));		
	//actualizar GEC equipo_2:	
	$connect->query("update equipo_grupo set GEC = (".$golesContraTotal2['total'].") where id =".$equipog2['id']);
	
	//actualizar DIF equipo_1
	$connect->query("update equipo_grupo set DIF = (".$golesTotal1['total']."-(".$golesContraTotal1['total'].")) where id =".$equipog1['id']);
	
	//actualizar DIF equipo_2
	$connect->query("update equipo_grupo set DIF = (".$golesTotal2['total']."-".$golesContraTotal2['total'].") where id =".$equipog2['id']);
	
	//consultar pts por ganar competencia:
	$ptsGanador = mysqli_fetch_array($connect->query( "select puntos_ganador from fase f join competicion c on f.id_competicion = c.id and f.id = ".$idFase ));
	
	//consultar pts por empatar competencia:
	$ptsEmpate = mysqli_fetch_array($connect->query( "select puntos_empate from fase f join competicion c on f.id_competicion = c.id and f.id = ".$idFase ));
	
	//consultar pts por perder competencia:
	$ptsPerdedor = mysqli_fetch_array($connect->query( "select puntos_perdedor from fase f join competicion c on f.id_competicion = c.id and f.id = ".$idFase ));
	
	//calculo puntos equipo_1
	$pts= 0;
	$ptsG = $ptsGanador['puntos_ganador']*$partidosGanados1;
	$ptsE = $ptsEmpate['puntos_empate']*$partidosEmpatados1;
	$ptsP = $ptsPerdedor['puntos_perdedor']*$partidosPerdidos1;	
		
	$pts = $ptsG+$ptsE+$ptsP;
	//actualizar PTS equipo_1
	$connect->query("update equipo_grupo set PTS = (".$pts.") where id =".$equipog1['id']);
	
	//calculo puntos equipo_2
	$pts= 0;
	$ptsG = $ptsGanador['puntos_ganador']*$partidosGanados2;
	$ptsE = $ptsEmpate['puntos_empate']*$partidosEmpatados2;
	$ptsP = $ptsPerdedor['puntos_perdedor']*$partidosPerdidos2;		
	
	$pts = $ptsG+$ptsE+$ptsP;
	//actualizar PTS equipo_2
	$connect->query("update equipo_grupo set PTS = (".$pts.") where id =".$equipog2['id']);
}
if ( $action == 'addAbono' ) {
	$query = "INSERT INTO pago (id_equipo,abono,fecha,id_competicion) VALUES (".$_POST['hdIdEqui'].",".$_POST['valor'].",NOW(),".$_POST['hdIdComp'].")";
	$connect->query( $query );	
	echo json_encode(array('error'=>false,'description'=>'Registro Creado'));
}
if ( $action == 'delAbono' ) {
	$query = "delete from pago where id = ".$_GET['idAbono'];
	$connect->query( $query );	
	echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
}
?>
<?php $connect->close(); ?>