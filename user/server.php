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
if ( $action == 'getDataUser' ) {	
	$id = $_GET[ 'id' ];
	$comp = mysqli_fetch_array( $connect->query( "select u.*, up.perfil from usuario u JOIN usuario_perfil up ON u.id = up.id_usuario  AND u.id=".$id ));
	echo json_encode($comp);
}
if ( $action == 'addUpdUser' ) {
	$bthAction = 0;
	$activo = 0;
	if(isset($_POST[ "activo" ])){$activo=1;}
	$foto = '../images/fotosUsuarios/default.png';	
	if(isset($_POST[ "bthAction"])){$bthAction=$_POST[ "bthAction" ];}
	if( $bthAction == 2 ){ //update data
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
		$query = "UPDATE usuario SET nombres='".$_POST["nombres"]."',apellidos='".$_POST["apellidos"]."',telefono='".$_POST["telefono"]."',correo='".$_POST["correo"]."',password='".$_POST["password"]."',usuario_modificador='".$_SESSION[ 'dataSession' ]['id']."',fecha_modificacion=NOW() WHERE id = ".$_POST[ "bthValId" ];
		$result = $connect->query( $query );
		//update foto:		
		if(!empty ($_FILES['foto']['name'])){
			$query2 = "UPDATE usuario SET url_foto='".$foto."' WHERE id = ".$_POST[ "bthValId"];
			$_SESSION[ 'dataSession' ]['url_foto']=$foto;
			$result = $connect->query( $query2 );
		}			
		if( $result == 1){		
			echo json_encode(array('error'=>false,'description'=>'Registro Actualizado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Actualizar Registro'));
		}
	}
}
if ( $action == 'addUpdEqui' ) {	
	$bthAction = 0;
	$foto = '../images/fotosEquipos/default.png';
	if(isset($_POST[ "bthAction"])){$bthAction=$_POST[ "bthAction" ];}
	if( $bthAction == 1 ){ //insert data		
		$foto;
		if( !empty ($_FILES['foto']['name']) ){
			//Upload foto:
			$sourcePathFoto = $_FILES['foto']['tmp_name'];		
			$targetPathFoto = "../images/fotosEquipos/".$_POST[ 'nombre' ].date("YmdHms").".png"; 
			move_uploaded_file($sourcePathFoto,$targetPathFoto) ;
			$foto = $targetPathFoto;
		}		
		$query = "INSERT INTO equipo (nombre,color,id_usuario,fecha,id_competicion, url_foto) VALUES ('" . $_POST[ "nombre" ] ."','".$_POST[ "color" ]."','".$_SESSION[ 'dataSession' ]['id']."'"." ,NOW(),".$_POST["cmbComp"].",'".$foto."' )";
		$result = $connect->query( $query );
		if( $result == 1){		
			echo json_encode(array('error'=>false,'description'=>'Registro Creado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Crear Registro'));
		}
	}else if( $bthAction == 2 ){ //update data
		$nombre = "";
		$comp = "";
		if( isset($_POST[ "hidNombre" ]) ){
			$nombre = $_POST[ "hidNombre" ];
		}else if( isset($_POST[ "nombre" ]) ){
			$nombre = $_POST[ "nombre" ];
		}
		if( isset($_POST[ "hidComp" ]) ){
			$comp = $_POST[ "hidComp" ];
		}else if( isset($_POST[ "cmbComp" ]) ){
			$comp = $_POST[ "cmbComp" ];
		}
		$foto;
		if( !empty ($_FILES['foto']['name']) ){
			//Upload foto:
			$sourcePathFoto = $_FILES['foto']['tmp_name'];		
			$targetPathFoto = "../images/fotosEquipos/".$nombre.date("YmdHms").".png"; 
			move_uploaded_file($sourcePathFoto,$targetPathFoto) ;
			$foto = $targetPathFoto;
			//Delete old foto from server:
			$equipo = mysqli_fetch_array($connect->query( "select * from equipo WHERE id=".$_POST[ "bthValId" ] ));
			if(!strpos($equipo['url_foto'], 'default.png')){
				unlink($equipo[ 'url_foto']);
			}
			//update foto:		
			if(!empty ($_FILES['foto']['name'])){
				$query2 = "UPDATE equipo SET url_foto='".$foto."' WHERE id = ".$_POST[ "bthValId" ];
				$result = $connect->query( $query2 );
			}	
		}		
		$query = "UPDATE equipo SET nombre='".$nombre."',color='".$_POST["color"]."',id_competicion='".$comp."' WHERE id = ".$_POST[ "bthValId" ];
		$result = $connect->query( $query );
		if( $result == 1){		
			//update juego:
			$result = $connect->query( "UPDATE juego SET nombre1='".$nombre."' WHERE id_equipo_1 = ".$_POST[ "bthValId" ] );
			$result = $connect->query( "UPDATE juego SET nombre2='".$nombre."' WHERE id_equipo_2 = ".$_POST[ "bthValId" ] );
			echo json_encode(array('error'=>false,'description'=>'Registro Actualizado'));
		}else{
			echo json_encode(array('error'=>true,'description'=>'No se pudo Actualizar Registro'));
		}
	}
}
if ( $action == 'addUpdJug' ) {
	$bthAction = 0;
	$delegado = 0;
	$foto = '../images/fotosJugadores/default.png';	
	if(isset($_POST[ "delegado" ])){$delegado=1;}
	if(isset($_POST[ "bthAction"])){$bthAction=$_POST[ "bthAction" ];}
	
	$documento=NULL;
	if(isset($_POST[ 'documento' ])){
		$documento = $_POST[ 'documento' ];
	}else if (isset($_POST[ 'docHide' ])){
		$documento = $_POST[ 'docHide' ];
	}
	
	//Validar jugador vetado:
	$vetado = mysqli_fetch_array($connect->query( "select count(1) total from jugador_vetado WHERE documento_jugador='".$documento."'"));
	//validar si el jugador ya existe:
	$existeJugador = mysqli_fetch_array($connect->query( "select count(1) total from jugador WHERE documento='".$documento."'"));	

	if( $vetado['total'] < 1 ){	
		if( $bthAction == 1 ){ //insert data
			if( !empty ($_FILES['foto']['name']) ){
				//Upload foto:
				$sourcePathFoto = $_FILES['foto']['tmp_name'];		
				$targetPathFoto = "../images/fotosJugadores/".$documento.date("YmdHms").".png"; 
				move_uploaded_file($sourcePathFoto,$targetPathFoto) ;
				$foto = $targetPathFoto;
			}	
			
			if( $existeJugador['total'] < 1 ){ //Si NO existe se crea nuevo
				$query = "INSERT INTO jugador (documento,nombres,apellidos,numero,telefono,correo,id_equipo,goles,es_delegado,url_foto) VALUES ('" . $documento ."','".$_POST["nombres"]."','".$_POST["apellidos"]."','".$_POST["numero"]."','".$_POST["telefono"]."','".$_POST["correo"]."','".$_POST["bthValEqui"]."','".'0'."','".$delegado."','".$foto."'".")";
			}else{ //Si existe se activa jugador con los nuevos datos
				$query = "UPDATE jugador SET documento='".$documento."',nombres='".$_POST["nombres"]."',apellidos='".$_POST["apellidos"]."',numero='".$_POST["numero"]."',telefono='".$_POST["telefono"]."',correo='".$_POST["correo"]."',es_delegado='".$delegado."', activo=NULL,id_equipo=".$_POST["bthValEqui"]." WHERE documento = ".$documento;
			}
			
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
				$targetPathFoto = "../images/fotosJugadores/".$documento.date("YmdHms").".png"; 
				move_uploaded_file($sourcePathFoto,$targetPathFoto) ;
				$foto = $targetPathFoto;
				//Delete old foto from server:
				$jugador = mysqli_fetch_array($connect->query( "select * from jugador WHERE id=".$_POST[ "bthValId" ] ));
				if(!strpos($jugador['url_foto'], 'default.png')){
					unlink($jugador[ 'url_foto']);
				}
			}	
			$query = "UPDATE jugador SET documento='".$documento."',nombres='".$_POST["nombres"]."',apellidos='".$_POST["apellidos"]."',numero='".$_POST["numero"]."',telefono='".$_POST["telefono"]."',correo='".$_POST["correo"]."',es_delegado='".$delegado."',id_equipo=".$_POST["bthValEqui"]." WHERE id = ".$_POST[ "bthValId" ];
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
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se puede crear, el jugador o su documento se encuentra vetado'));
	}
}
if ( $action == 'getDataJug' ) {	
	$id = $_GET[ 'id' ];
	$comp = mysqli_fetch_array( $connect->query( "SELECT * FROM jugador WHERE id=".$id ));
	echo json_encode($comp);
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
if ( $action == 'delPlayer' ) {	
	$id = $_GET[ 'id' ];
	//$sql = "DELETE FROM jugador WHERE id=".$id;
	$sql = "UPDATE jugador SET activo=0 WHERE id = ".$id;
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Desactivado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Desactivar Registro'));
	}
}
if ( $action == 'getDataEqui' ) {	
	$id = $_GET[ 'id' ];
	$comp = mysqli_fetch_array( $connect->query( "SELECT * FROM equipo WHERE id=".$id ));
	echo json_encode($comp);
}
if ( $action == 'addAbonoPlayer' ) {
	$query = "INSERT INTO pago_jugador (id_jugador,id_competicion,valor,fecha) VALUES (".$_POST['hdIdPlayer'].",".$_POST['hdIdComp'].",".$_POST['valor'].",'".$_POST["fecha"]."'".")";
	$connect->query( $query );	
	echo json_encode(array('error'=>false,'description'=>'Registro Creado'));
}
if ( $action == 'delPayPlayer' ) {	
	$id = $_GET[ 'id' ];
	$sql = "DELETE FROM pago_jugador WHERE id=".$id;
	$result = $connect->query( $sql );
	if( $result == 1){		
		echo json_encode(array('error'=>false,'description'=>'Registro Eliminado'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo Eliminar Registro'));
	}
}
?>
<?php $connect->close(); ?>