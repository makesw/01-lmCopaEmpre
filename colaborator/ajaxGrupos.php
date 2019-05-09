<?php 
require '../conexion.php';
$id = $_GET[ 'idFase' ];
$resultGrupos = $connect->query( "select g.* from grupo g JOIN fase f ON g.id_fase = f.id AND f.id = ".$id." order by nombre asc" );
?>
<?php 
$iter = 1;
while($row = mysqli_fetch_array($resultGrupos)){?>
	<div class="col-lg-6">
	<div class="panel panel-primary">
		<div class="panel-heading clearfix"> 
			<div class="panel-title"><?php echo($row['nombre']) ?></div> 
			<ul class="panel-tool-options">
				<li><a data-rel="collapse" href="#"><i class="icon-down-open"></i></a></li>
			</ul> 
		</div> 					
		<div class="panel-body"> 
			<!--<button type="button" class="btn btn-default" onClick="javascript:addEquToGru('<?php echo $row['id']; ?>')">Agregar Equipos</button>-->
			
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover dataTables-tpsancion" >
					<thead>
						<tr>
							<th>#</th>
							<th>Nombre</th>
							<!--<th></th> -->
						</tr>
					</thead>
					<tbody>
						<?php $iter = 1;
						$resultEquipoGrupo = $connect->query('select eg.*, e.nombre from equipo_grupo eg JOIN equipo e ON eg.id_equipo = e.id and eg.id_grupo = '.$row['id'].' order by e.nombre asc');
						while($rowEquiGru = mysqli_fetch_array($resultEquipoGrupo)){?>
								<tr>
									<td>
										<?php echo $iter; ?>
									</td>
									<td>
										<?php echo $rowEquiGru['nombre']; ?>
									</td>									
									<!--<td>									
									<a title="Borrar" href="javaScript:delEquiGru('<?php echo $rowEquiGru['id']; ?>');">
									<i class="icon-cancel icon-larger red-color"></i>
									</a>												
									</td>-->
								</tr>
								<?php $iter++; } ?>
					</tbody>
				</table>
			</div>								
		</div> 
	</div>
	</div>	
<?php $iter++; } ?>
<?php $connect->close(); ?>