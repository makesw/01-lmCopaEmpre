<?php
$arrayFases = array();
require '../conexion.php';
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
	$resultEquipos = $connect->query("select e.* from equipo e WHERE e.id NOT IN (select distinct(id_equipo) from inscripcion i WHERE id_competicion = ".$_POST["idComp"].")  ORDER BY e.nombre asc");
	$table='';
	while($row = mysqli_fetch_array($resultEquipos)){
		 $table .= '<tr><td>'.$row['nombre'].'</td><td><input name="checkbox[]" type="checkbox" value="'.$row["id"].'"></td></tr>';
	}
	echo $table;
}
?>