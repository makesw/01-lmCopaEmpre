<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: /index.php' );
}
$arrayFases = array();
require '../conexion.php';
if(isset($_GET[ 'opt' ]) && $_GET[ 'opt' ]==1 ){
	$resultEquipos = $connect->query("select * from equipo e WHERE e.id_competicion = (select id_competicion from grupo g JOIN fase f ON g.id_fase = f.id AND g.id = ".$_POST["idGrupo"]." limit 1) AND e.id NOT IN (select distinct (eg2.id_equipo) from equipo_grupo eg2 JOIN grupo g2 ON eg2.id_grupo = g2.id JOIN fase f ON g2.id_fase = f.id AND f.activa = 1) ORDER BY nombre asc");
	$table='';
	while($row = mysqli_fetch_array($resultEquipos)){
		 $table .= '<tr><td>'.$row['nombre'].'</td><td><input name="checkbox[]" type="checkbox" value="'.$row["id"].'"></td></tr>';
	}
	echo $table;
}
if(isset($_GET[ 'opt' ]) && $_GET[ 'opt' ]==2 ){
	$resultEquipos = $connect->query("select * from equipo e WHERE id_competicion = ".$_POST["idComp"]." ORDER BY nombre asc");
	$table='';
	while($row = mysqli_fetch_array($resultEquipos)){
		 $table .= '<tr><td>'.$row['nombre'].'</td><td><input name="checkbox[]" type="checkbox" value="'.$row["id"].'"></td></tr>';
	}
	echo $table;
}
if(isset($_GET[ 'opt' ]) && $_GET[ 'opt' ]==3 ){
	$resultEquipos = $connect->query("select e.* from equipo e WHERE e.id NOT IN (select distinct(id_equipo) from inscripcion i WHERE id_competicion = ".$_POST["idComp"].")  ORDER BY e.nombre asc");
	$table='';
	while($row = mysqli_fetch_array($resultEquipos)){
		 $table .= '<tr><td>'.$row['nombre'].'</td><td><input name="checkbox[]" type="checkbox" value="'.$row["id"].'"></td></tr>';
	}
	echo $table;
}
if(isset($_GET[ 'opt' ]) && $_GET[ 'opt' ]==4 ){
	$resultEquipos = $connect->query("select e.* from equipo e WHERE e.id_usuario = ".$_SESSION[ 'dataSession' ]['id']." AND e.id NOT IN (select distinct(id_equipo) from inscripcion i WHERE id_competicion = ".$_POST["idComp"].")  ORDER BY e.nombre asc");
	$table='';
	while($row = mysqli_fetch_array($resultEquipos)){
		 $table .= '<tr><td>'.$row['nombre'].'</td><td><input name="checkbox[]" type="checkbox" value="'.$row["id"].'"></td></tr>';
	}
	echo $table;
}
?>