<?php 
require '../conexion.php';

if(isset($_GET[ 'opt' ]) && $_GET[ 'opt' ]==1 ){
	
$resultPagos = $connect->query( "select e.id,e.nombre nombreEquipo, c.nombre nombreComp, c.valor, ifnull(sum(p.abono),0) abono from equipo e left join pago p on e.id = p.id_equipo 
join competicion c on e.id_competicion = c.id and c.id = ".$_GET['idComp']." and c.activa =1 group by e.id order by abono desc" );
?>
<?php
while($row = mysqli_fetch_array($resultPagos)){	
?>	
<tr>	
	<td> <?php echo $row['nombreEquipo']; ?>	</td>
	<td> <?php echo $row['nombreComp']; ?>	</td>
	<td> <?php echo '$ '.number_format($row['valor']); ?>	</td>
	<td style="color:#016910;"> <strong><?php echo '$ '.number_format($row['abono']); ?></strong>	</td>
	<td style="color:#ef4040;"> <strong><?php echo '$ '.number_format($row['valor'] - $row['abono']); ?></strong>	</td>
	<td> <button type="button" class="btn btn-success btn-outline" data-toggle="modal" onClick="javascript:fnAddPago(<?php echo $row['id']; ?>);">Abonar</button> </td>
	<th> <a href="pagosDetalle.php?idEqui=<?php echo $row['id']; ?>&id_comp=<?php echo $_GET['idComp']; ?>"><button class="btn btn-info btn-outline" type="button"> Ver Abonos</button></a> </th>	
</tr>
<?php } ?>

<?php } ?>

<script>
$(document).ready(function () {
$('.dataTables-goleadores').DataTable({
	"searching": true,
	"bSort" : false,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 20,
	"oLanguage": {
	   "sSearch": "Buscar: "
	 },
	dom: '<"html5buttons" B>lTfgitp',
		buttons: [				
			{
				extend: 'excelHtml5',
				exportOptions: {
					columns: ':visible'
				}
			},
			{
				extend: 'pdfHtml5',
				exportOptions: {
					columns: ':visible'
				}
			}
		]
});
});
</script>
<?php $connect->close(); ?>