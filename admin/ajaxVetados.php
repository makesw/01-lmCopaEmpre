<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
    header( 'Location: ../index.html' );
}else{
    if($_SESSION[ 'dataSession' ]['perfil'] != 'admin'){
        header( 'Location: ../salir.php' );
    }
}
require '../conexion.php';
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:null;
$resultJugVetados = $connect->query( "select jv.*,j.documento, concat(j.nombres,' ',j.apellidos) nombreJugador, e.nombre nombreEquipo from jugador_vetado jv join jugador j on jv.documento_jugador = j.documento join equipo e on j.id_equipo = e.id join competicion c on e.id_competicion = c.id and c.id = ".$idComp );
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
?>

<button type="button" class="btn btn-default" onClick="javascript:vetarJugadores()">Vetar Jugadores</button>
<br>Lista Jugadores Vetados:
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-jugVetados" >
<thead>
	<tr>
		<th>
			<input type="checkbox" name="select_all" value="1" id="chkAllVetados">Sel.Todo
			<a title="Borrar Seleccionados" href="javaScript:delVet();">
				<i class="icon-cancel icon-larger red-color"></i>
			</a>
		</th>
		<th>Documento</th>
		<th>Nombres</th>
		<th>Equipo</th>
		<th>Fecha</th>
		<th>Motivo</th>
	</tr>
</thead>
<tbody>
<?php
while($row = mysqli_fetch_array($resultJugVetados)){
	$date_a = "";
	if(!empty($row['fecha'])){
		$date_a = new DateTime($row['fecha']);
		$date_a = $date_a->format('d-m-Y');
	}
?>	
<tr>	
	<td>
		<input type="checkbox" name="id[]" value="<?php echo $row['documento']; ?>">
	</td>
	<td> <?php echo $row['documento']; ?>	</td>
	<td> <?php echo $row['nombreJugador']; ?>	</td>
	<td> <?php echo $row['nombreEquipo']; ?>	</td>
	<td> <?php echo $date_a; ?>	</td>
	<td> <textarea rows="2"><?php echo $row['motivo']; ?></textarea></td>	
</tr>
<?php } ?>
</tbody>
</table>
</div>
<script>
var table;
var arrSelectedPlayers=[];
var checkAll = false;
$(document).ready(function () {
	table = $('.dataTables-jugVetados').DataTable({
	"searching": true,
	"bSort" : false,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 20,
	"oLanguage": {
	   "sSearch": "Buscar: "
	 }
	});
});
function delVet(){
	// Iterate over all checkboxes in the table
	table.$('input[type="checkbox"]').each(function(){
	 // If checkbox doesn't exist in DOM
	 //if(!$.contains(document, this)){
		// If checkbox is checked
		if(this.checked){
		   arrSelectedPlayers.push( this.value );
		}
	 //}
	});
	if(arrSelectedPlayers.length < 1){
		alert('Sleccione al menos un jugador!.');
	}else{		
		if(confirm('¿Confirma Eliminar?')){
			$.ajax({
				type: "POST",
				url: "server.php?action=delVetados",
				data:{ array : JSON.stringify(arrSelectedPlayers) },
				dataType: "json",
				success: function (data) {
					//console.log(data);
					arrSelectedPlayers=[];
					checkAll = false;
					location.href='./jugadoresVetados.php?idComp='+<?php echo $idComp; ?>;
				},
				error: function (data) {
					//console.log(data);
					arrSelectedPlayers=[];
					checkAll = false;
					console.log(data);
					location.href='./jugadoresVetados.php?idComp='+<?php echo $idComp; ?>;
				}
			});
		}
	}
}
$('#chkAllVetados').on('click', function() { checkAll = true;
   // Get all rows with search applied
     var rows = table.rows({ 'search': 'applied' }).nodes();
	// Check/uncheck checkboxes for all rows in the table
      $('input[type="checkbox"]', rows).prop('checked', this.checked);	
});
</script>
<?php $connect->close(); ?>