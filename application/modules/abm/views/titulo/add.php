<?php echo form_open('abm/titulo/add',array("class"=>"form-horizontal")); ?>

	<?php echo $this->template->cargar_select(lang('form_plan'), 'id_plan', '*', form_error('plan'), $planes, $this->input->post('id_plan')); ?>

	<?php echo $this->template->cargar_select(lang('form_orientation'), 'id_orientacion', '', form_error('orientacion'), $orientaciones, $this->input->post('id_orientacion')); ?>

	<?php echo $this->template->cargar_input(lang('form_name'), 'nombre', 'text', '*', form_error('nombre'), $this->input->post('nombre')); ?>

	<?php echo $this->template->cargar_submit(); ?>

<?php echo form_close(); ?>