<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
    header( 'Location: ../index.html' );
}else{
    if($_SESSION[ 'dataSession' ]['perfil'] != 'admin'){
        header( 'Location: ../salir.php' );
    }
}
$arrayFases = array();
require '../conexion.php';
$resultJornadas = $connect->query( "select distinct jornada from juego where id_fase =".$_POST["elegido"] );
$options='<option value="0">Seleccione una jornada</option>';
while($row = mysqli_fetch_array($resultJornadas)){
	 $options .= '<option value="'.$row['jornada'].'">'.$row["jornada"].'</option>';
}
echo $options;
?>