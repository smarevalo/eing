<!-- Common Pages -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<?php /*
	<section class="content-header">
		<h1><?php echo lang('create_user_heading');?></h1>
	</section>
	 */?>

	<!-- Main content -->
	<section class="content">
		<!-- Your Page Content Here -->
		<?php if($message != '') { ?>
		<div class="alert alert-success alert-dismissible">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<p><?php echo $message;?></p>
		</div>
		<?php } ?>
	<div class="row">
		<div class="col-md-8">
			<div class="box">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><?php echo lang('create_user_heading');?></h3>
					</div>
					<!-- /.box-header -->
					<?php echo form_open("auth/create_user");?>
						<div class="box-body">
							<div class="form-group">
								<?php echo lang('create_user_fname_label', 'first_name');?><br />
								<?php echo form_input($first_name);?>
							</div>
							
							<div class="form-group">
								<?php echo lang('create_user_lname_label', 'last_name');?> <br />
								<?php echo form_input($last_name);?>
							</div>

							<?php
							if($identity_column!=='email') {
								echo '<p>';
								echo lang('create_user_identity_label', 'identity');
								echo '<br />';
								echo form_error('identity');
								echo form_input($identity);
								echo '</p>';
							}
							?>
							<?php /*
							<div class="form-group">
								<?php echo lang('create_user_company_label', 'company');?> <br />
								<?php echo form_input($company);?>
							</div>
							 */ ?>

							<div class="form-group">
								<?php echo lang('create_user_email_label', 'email');?> <br />
								<?php echo form_input($email);?>
							</div>

							<div class="form-group">
								<?php echo lang('create_user_phone_label', 'phone');?> <br />
								<?php echo form_input($phone);?>
							</div>

							<div class="form-group">
								<?php echo lang('create_user_password_label', 'password');?> <br />
								<?php echo form_input($password);?>
							</div>

							<div class="form-group">
								<?php echo lang('create_user_password_confirm_label', 'password_confirm');?> <br />
								<?php echo form_input($password_confirm);?>
							</div>
						</div>
						
						<?php /* 
						<div class="box-footer">
							<?php echo form_submit('submit', lang('create_user_submit_btn'), 'class="btn btn-success"');?>
						</div>
						*/?>
					
						<div class="col-md-3 col-md-offset-9">
							<button style="border: 1px solid rgba(0,0,0,0.1); box-shadow: inset 0 1px 0 rgba(255,255,255,0.7);" type='submit' class='btn btn-block btn-success btn-md'>Guardar</button>
						</div>
						<br><br>
					<?php echo form_close();?>
				</div>
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->