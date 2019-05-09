<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
require '../conexion.php';
$connect->query( "update usuario set acepto_terminos = 1 where id = ".$_SESSION[ 'dataSession' ]['id'] );
$_SESSION[ 'dataSession' ]['acepto_terminos']=1;
$connect->close();
?>