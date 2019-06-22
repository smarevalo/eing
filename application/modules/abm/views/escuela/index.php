<?php if(isset($alerta))  {  
	echo $alerta;
	} 
?>

<?php //echo $this->template->boton_nuevo('abm/escuela/add', 'Nueva escuela'); ?>

<hr>

<?php echo $this->template->get_links(); ?>

<div class="col-xs-12">
	<div class="box">

		<div class="box-header">
			<h3 class="box-title">Escuelas</h3>
		</div>

		<div class="box-body">
			<table id="example2" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th><?php echo lang('table_id_th');?></th>
						<th><?php echo lang('table_name_th');?></th>
						<th><?php echo lang('table_university_th');?></th>
						<th><?php echo lang('table_director_th');?></th>
						<th><?php echo lang('table_color_th');?></th>
						<th colspan="2"><?php echo lang('table_actions_th');?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($escuelas as $c):?>
					<tr>
						<td><?php echo htmlspecialchars($c->id,ENT_QUOTES,'UTF-8');?></td>
						<td><?php echo htmlspecialchars($c->nombre,ENT_QUOTES,'UTF-8');?></td>
						<td><?php echo htmlspecialchars($c->universidad,ENT_QUOTES,'UTF-8');?></td>
						<td><?php echo htmlspecialchars($c->director,ENT_QUOTES,'UTF-8');?></td>
						
						<td><span style="background:<?= $c->color  ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
						<td><?php echo anchor("abm/escuela/edit/".$c->id, '<span class="btn btn-primary btn-xs">Editar</span>') ;?>
					 	<?php echo anchor("abm/escuela/remove/".$c->id, '<span class="btn btn-danger btn-xs">Eliminar</span>') ;?></td>
					 </tr>
					 <?php endforeach;?>
				</tbody>
				<tfoot>
					<tr>
						<th><?php echo lang('table_id_th');?></th>
						<th><?php echo lang('table_name_th');?></th>
						<th><?php echo lang('table_university_th');?></th>
						<th><?php echo lang('table_director_th');?></th>
						<th><?php echo lang('table_color_th');?></th>
						<th colspan="2"><?php echo lang('table_actions_th');?></th>
					</tr>
				</tfoot>
			</table>
		</div>

	</div>
	<!-- /.box -->
</div>
<!-- /.col -->

<?php echo $this->template->get_links(); ?>

<hr>

							
