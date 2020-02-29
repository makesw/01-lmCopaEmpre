<?php
$connect = new mysqli( 'localhost', 'copaempre_lm', 'copaempre-lm@2019', 'copaempre_lm' );
if ( $connect->connect_errno ) {
	echo "Fallo al conectar a MySQL: (" . $connect->connect_errno . ") " . $connect->connect_error;
}
$connect->query( "SET NAMES 'utf8'" );
?>