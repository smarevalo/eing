<?php if(isset($alerta))  {  
	echo $alerta;
	} 
?>			
<?php echo form_open_multipart('abm/carrera/add',array("class"=>"form-horizontal")); ?>

	<?php echo $this->template->cargar_input(lang('form_name'), 'nombre', 'text', '*', form_error('nombre'), $this->input->post('nombre')); ?>


	<?php echo $this->template->cargar_input(lang('form_plan_pdf'), 'plan_pdf', 'file', '', form_error('plan_pdf'), $this->input->post('plan_pdf'), '*El archivo debe estar en formato PDF.'); ?>

	<?php echo $this->template->cargar_input(lang('form_image'), 'imagen', 'file', '', form_error('imagen'), $this->input->post('imagen'), $this->input->post('imagen').'*La imágen debe estar en formato JPG o PNG.'); ?>


	<?php echo $this->template->cargar_textarea(lang('form_presentation'), 'presentacion', '', form_error('presentacion'), $this->input->post('presentacion')); ?>

	<?php echo $this->template->cargar_textarea(lang('form_career_profile'), 'perfil', '', form_error('perfil'), $this->input->post('perfil')); ?>
		
	<?php echo $this->template->cargar_submit(); ?>

<?php echo form_close(); ?>
							

<script>
    CKEDITOR.replace( 'presentacion' );
    CKEDITOR.replace( 'perfil' );
</script>
