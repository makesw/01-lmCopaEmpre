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
$idComp = 0;
if(isset($_GET[ 'opt' ]) && $_GET[ 'opt' ]==1 ){
if(isset($_GET[ 'idComp' ])){
	$idComp = $_GET[ 'idComp' ];
}
$resultJugadores = $connect->query( "select j.documento, concat(j.nombres,' ',j.apellidos) nombreJugador, e.nombre nombreEquipo  from jugador j join equipo e on j.id_equipo = e.id join competicion c on e.id_competicion = c.id and c.id = ".$idComp." and c.activa  = 1 and j.documento not in (select documento_jugador  from jugador_vetado) order by nombreEquipo asc, nombreJugador asc" );
?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-players" id="tblPlayers">

		<thead>
			<tr>
				<th><input type="checkbox" name="select_all" value="1" id="chkAllPlayers">Sel.Todo</th>
				<th>Documento</th>
				<th>Nombre</th>
				<th>Equipo</th>				
			</tr>
		</thead>
		<tbody>
			<?php 
			while($row = mysqli_fetch_array($resultJugadores)){
			?>
			<tr>
				<td><input type="checkbox" name="id[]" value="<?php echo $row['documento']; ?>" id="chkOne"></td>
				<td>
					<?php echo $row['documento']; ?>
				</td>
				<td>
					<?php echo $row['nombreJugador']; ?>
				</td>
				<td>
					<?php echo $row['nombreEquipo']; ?>
				</td>								
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
Motivo: <input type="text" size="200" class="form-control" id="motivo" name="motivo" placeholder="motivo" required>
<?php } ?>
<script>
var arrSelectedPlayers=[];
$(document).ready(function () {
var table = $('.dataTables-players').DataTable({
	"searching": true,
	"bSort" : false,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 15,
	"oLanguage": {
	   "sSearch": "Buscar: "
	 }
});

$('#chkAllPlayers').on('click', function() {
   // Get all rows with search applied
     var rows = table.rows({ 'search': 'applied' }).nodes();
	// Check/uncheck checkboxes for all rows in the table
      $('input[type="checkbox"]', rows).prop('checked', this.checked);	
});

	
$('#formVetar').on('submit', function(e){		
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
	var motivo = $('#motivo').val();
	if ( arrSelectedPlayers.length < 1 ) {
		alert( "Seleccione por lo menos un jugador!." );
		return false;
	}else {
	 $.ajax({
			type: "POST",
			url: "server.php?action=addVetados&motivo="+motivo,
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
				console.log(data);
				location.href='./jugadoresVetados.php?idComp='+<?php echo $idComp; ?>;
			}
		});
		
	}
});	

 // Handle click on checkbox to set state of "Select all" control
   $('#tblPlayers tbody').on('change', 'input[type="checkbox"]', function(){
      // If checkbox is not checked
      if(!this.checked){
         var el = $('#chkAllPlayers').get(0);
         // If "Select all" control is checked and has 'indeterminate' property
         if(el && el.checked && ('indeterminate' in el)){
            // Set visual state of "Select all" control
            // as 'indeterminate'
            el.indeterminate = true;
         }
      }
   });
	
});
</script>