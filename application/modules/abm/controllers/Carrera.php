<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Carrera extends MX_Controller {

    public $name = 'La carrera';
	function __construct(){
		parent::__construct();
        $this->load->module('template');
        $this->load->model('Carrera_model');
        $this->load->add_package_path(APPPATH.'third_party/ion_auth/');
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->load->helper(array('language'));
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
    }

	function index($mensaje=null)
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('login', 'refresh');
        }else {
            $params = $this->template->get_params();
            $config = $this->template->get_config('abm/carrera/index?', $this->Carrera_model->get_all_carrera_count());
            $this->pagination->initialize($config);

            $data['carreras'] = $this->Carrera_model->get_all_carrera($params);
            $data['user'] = $this->ion_auth->user()->row();
            if (isset($mensaje)) {
                $data['alerta'] = $mensaje;
            }

            $this->template->cargar_vista('abm/carrera/index', $data);
        }
    }

    /*
     * Adding a new carrera
     */
    function add()
    {   
        if (!$this->ion_auth->logged_in())
        {
            redirect('login', 'refresh');
        }else {

            $this->form_validation->set_rules('nombre','Nombre','required');
            $this->form_validation->set_rules('plan_pdf','Plan PDF','callback_pdf_file_check[plan_pdf]');
            $this->form_validation->set_rules('imagen','Imagen','callback_image_file_check[imagen]');

            if($this->form_validation->run($this))     
            {   
                $params = array(
                    'nombre' => $this->input->post('nombre'),
                    'imagen' => $_FILES['imagen']['name'],
                    'plan_pdf' => $_FILES['plan_pdf']['name'],
                    'presentacion' => $this->input->post('presentacion'),
                    'perfil' => $this->input->post('perfil'),
                );

                $pdf = $this->template->subir_archivo(PDFS_UPLOAD, 'pdf', 'plan_pdf');
                $imagen = $this->template->subir_archivo(IMAGES_UPLOAD, 'jpg|png', 'imagen');
             
                if ($this->Carrera_model->add_carrera($params))
                    $mensaje =  $this->template->cargar_alerta('success', lang('record_success'), 
                                    sprintf(lang('record_edit_success_text'), $this->name));    
                else   
                    $mensaje = $this->template->cargar_alerta('danger', lang('record_error'),
                                    sprintf(lang('record_edit_error_text'), $this->name));  

                $this->index($mensaje);
            }
            else
            {            
                $data['user'] = $this->ion_auth->user()->row();

                $this->template->cargar_vista('abm/carrera/add', $data);
            }
        }
    }  

    /*
     * Editing a carrera
     */
    function edit($id)
    {   
        if (!$this->ion_auth->logged_in())
        {
            redirect('login', 'refresh');
        }else {
            
            $data['carrera'] = $this->Carrera_model->get_carrera($id);
            
            if(isset($data['carrera']['id']))
            {
                $this->form_validation->set_rules('nombre','Nombre','required');
                $this->form_validation->set_rules('plan_pdf','Plan PDF','callback_pdf_file_check[plan_pdf]');
                $this->form_validation->set_rules('imagen','Imagen','callback_image_file_check[imagen]');
                
                if($this->form_validation->run($this))     
                {                       
                    $params = array(
                        'nombre' => $this->input->post('nombre'),
                        'presentacion' => $this->input->post('presentacion'),
                        'perfil' => $this->input->post('perfil'),
                    );

                    if($_FILES['plan_pdf']['name'] != ''){
                        $pdf = $this->template->subir_archivo(PDFS_UPLOAD, 'pdf', 'plan_pdf');
                        $params['plan_pdf'] = $pdf['file_name'];
                    }
                    
                    if($_FILES['imagen']['name'] != ''){
                        $imagen = $this->template->subir_archivo(IMAGES_UPLOAD, 'jpg|png', 'imagen');
                        $params['imagen']= $imagen['file_name'];
                    }

                    if ($this->Carrera_model->update_carrera($id, $params))
                        $mensaje =  $this->template->cargar_alerta('success', lang('record_success'), 
                                        sprintf(lang('record_edit_success_text'), $this->name));    
                    else   
                            $mensaje = $this->template->cargar_alerta('danger', lang('record_error'),
                                        sprintf(lang('record_edit_error_text'), $this->name));  

                    $this->index($mensaje);
                }
                else
                {
                    $data['user'] = $this->ion_auth->user()->row();
                    $this->template->cargar_vista('abm/carrera/edit', $data);
                }
            }
            else
                show_error(sprintf(lang('no_existe'), $this->name));
        }
    } 

    /*
     * Deleting carrera
     */
    function remove($id)
    {
        $carrera = $this->Carrera_model->get_carrera($id);

        // check if the carrera exists before trying to delete it
        if(isset($carrera['id']))
        {
            if ($this->Carrera_model->delete_carrera($id))
                    $mensaje =  $this->template->cargar_alerta('success', lang('record_success'), 
                                sprintf(lang('record_remove_success_text'), $this->name));    
            else   
                    $mensaje = $this->template->cargar_alerta('danger', lang('record_error'),
                                sprintf(lang('record_remove_error_text'), $this->name));    
                
            $this->index($mensaje);
        }
        else
            show_error(sprintf(lang('no_existe'), $this->name));
    }


    public function activate($id, $code = FALSE)
	{
		if ($this->ion_auth->is_admin())
		{
            $params['activo']= true;
            $this->Carrera_model->change_status($id, $params);
		}

		redirect('abm/carrera/', 'refresh');
	}


    public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			return show_error('You must be an administrator to view this page.');
		}

        $id = (int)$id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() === FALSE)
		{
			$data['carrera'] = $this->Carrera_model->get_carrera($id);
            $data['user'] = $this->ion_auth->user()->row();
            $this->template->cargar_vista('abm/carrera/deactivate_carrera', $data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($id != $this->input->post('id'))
				{
					return show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
                    $params['activo']= false;
					$this->Carrera_model->change_status($id, $params);
				}
			}

			// redirect them back to the auth page
			redirect('abm/carrera/', 'refresh');
		}
	}

    public function pdf_file_check($str, $nombre)
    {
        return $this->template->pdf_file_check($str, $nombre);
    }

    public function image_file_check($str, $nombre)
    {
        return $this->template->image_file_check($str, $nombre);
    }

}

?>
