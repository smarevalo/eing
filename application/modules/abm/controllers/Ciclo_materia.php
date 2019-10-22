<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Ciclo_materia extends MX_Controller{

    private $name = 'El ciclo-materia';
    function __construct()
    {
        parent::__construct();    
        $this->load->add_package_path(APPPATH.'third_party/ion_auth/');
        $this->load->library(array('ion_auth', 'form_validation'));
        
        if (!$this->ion_auth->logged_in())
        {
            redirect('login', 'refresh');
        }else {
            $this->load->module('template');
            $this->load->model('Ciclo_materia_model');
            $this->load->model('Ciclo_model');
            $this->load->model('Materia_model');
            $this->load->model('Materias_tipo_model');
            $this->load->model('Regimen_model');
            $this->load->helper(array('language'));
            $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
            $this->lang->load('auth');
        }
    } 

    /*
     * Listing of ciclo_materia
     */
    public function index($mensaje=null)
    {
        $data['ciclo_materia'] = $this->Ciclo_materia_model->get_all_ciclo_materia();
        $data['user'] = $this->ion_auth->user()->row();
        if (isset($mensaje)) {
            $data['alerta'] = $mensaje;
        }

        $this->template->cargar_vista('abm/ciclo_materia/index', $data);
    }

    /*
     * Adding a new ciclo_materia
     */
    public function add($id_plan=null)
    {   
        $this->form_validation->set_rules('anio',lang('form_year'),'integer|required');
        $this->form_validation->set_rules('codigo',lang('form_code'),'integer');
        $this->form_validation->set_rules('horas',lang('form_hours'),'numeric');
        $this->form_validation->set_rules('hs_total',lang('form_total_hours'),'numeric');
        $this->form_validation->set_rules('id_ciclo',lang('form_cycle'),'required');
        $this->form_validation->set_rules('id_materia',lang('form_course'),'required');
        $this->form_validation->set_rules('id_regimen',lang('form_regimen'),'required');
        $this->form_validation->set_rules('programa',lang('form_program'),'callback_pdf_file_check[programa]');
		
		if($this->form_validation->run($this))     
        {   
            $params = array(
                'id_ciclo' => $this->input->post('id_ciclo'),
                'id_materia' => $this->input->post('id_materia'),
                'id_regimen' => $this->input->post('id_regimen'),
                'horas' => $this->input->post('horas'),
                'hs_total' => $this->input->post('hs_total'),
                'anio' => $this->input->post('anio'),
                'codigo' => $this->input->post('codigo'),
            );

            if($_FILES['programa']['name'] != ''){
                $pdf = $this->template->subir_archivo(PDFS_UPLOAD.'programas', 'pdf', 'programa');
                $params['programa'] = $pdf['file_name'];
            }
            
            if ($this->Ciclo_materia_model->add_ciclo_materia($params))
                    $mensaje =  $this->template->cargar_alerta('success', lang('record_success'), 
                                sprintf(lang('record_add_success_text'), $this->name));    
            else   
                    $mensaje = $this->template->cargar_alerta('danger', lang('record_error'),
                                sprintf(lang('record_add_error_text'), $this->name)); 
                    
            $this->index($mensaje);
        }
        else
        {
            if (is_null($id_plan)){
                $data['ciclos'] = $this->Ciclo_model->get_all_ciclos();    
            }
            else{
                $data['ciclos'] = $this->Ciclo_model->get_ciclos_by_plan($id_plan);

                for ($i=0; $i < $data['ciclos'][0]->duracion ; $i++) { 
                    $data['anios'][$i] = new stdClass;
                    $data['anios'][$i]->id = $i+1;
                    $data['anios'][$i]->nombre = $i+1;
                }
            }
            
            $data['materias'] = $this->Materia_model->get_all_materias();
            $data['regimenes'] = $this->Regimen_model->get_all_regimen();

            $this->template->cargar_vista('abm/ciclo_materia/add', $data);
        }

    }  

 
    function fetch_materias()
    {
        if($this->input->post('ciclo_id'))
        {
            echo $this->Ciclo_materia_model->fetch_materias($this->input->post('ciclo_id'));
        }
    }

    function fetch_anios()
    {
        if($this->input->post('ciclo_id'))
        {
            echo $this->Ciclo_materia_model->fetch_anios($this->input->post('ciclo_id'));
        }
    }

    

    /*
     * Editing a ciclo_materia
     */
    public function edit($id)
    {   
        $data['ciclo_materia'] = $this->Ciclo_materia_model->get_ciclo_materia($id);
        
        if(isset($data['ciclo_materia']['id']))
        {
			$this->form_validation->set_rules('anio',lang('form_year'),'integer|required');
			$this->form_validation->set_rules('codigo',lang('form_code'),'alpha_numeric');
			$this->form_validation->set_rules('horas',lang('form_hours'),'numeric');
			$this->form_validation->set_rules('hs_total',lang('form_total_hours'),'numeric');
			$this->form_validation->set_rules('id_ciclo',lang('form_cycle'),'required');
			$this->form_validation->set_rules('id_materia',lang('form_course'),'required');
			$this->form_validation->set_rules('id_regimen',lang('form_regimen'),'required');
            $this->form_validation->set_rules('programa',lang('form_program'),'callback_pdf_file_check[programa]');
		
			if($this->form_validation->run($this))     
            {   
                $params = array(
					'id_ciclo' => $this->input->post('id_ciclo'),
					'id_materia' => $this->input->post('id_materia'),
					'id_regimen' => $this->input->post('id_regimen'),
					'horas' => $this->input->post('horas'),
					'hs_total' => $this->input->post('hs_total'),
					'anio' => $this->input->post('anio'),
					'codigo' => $this->input->post('codigo'),
                );

                if($_FILES['programa']['name'] != ''){
                    $pdf = $this->template->subir_archivo(PDFS_UPLOAD.'programas', 'pdf', 'programa');
                    $params['programa'] = $pdf['file_name'];
                }

                if ($this->Ciclo_materia_model->update_ciclo_materia($id,$params))
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
				$data['ciclos'] = $this->Ciclo_model->get_all_ciclos(); 
				$data['materias'] = $this->Materia_model->get_all_materias();
				$data['regimenes'] = $this->Regimen_model->get_all_regimen();
                
                foreach ($data['ciclos'] as $ciclo) {
                    if ($ciclo->id == $data['ciclo_materia']['id_ciclo']) {
                        $data['anios'] = $this->template->get_anios($ciclo->duracion);
                    }
                }

                $this->template->cargar_vista('abm/ciclo_materia/edit', $data);
            }
        }
        else
            show_error(sprintf(lang('no_existe'), $this->name));
    } 

    /*
     * Deleting ciclo_materia
     */
    public function remove($id)
    {
        $ciclo_materia = $this->Ciclo_materia_model->get_ciclo_materia($id);

        if(isset($ciclo_materia['id']))
        {
            if ($this->Ciclo_materia_model->delete_ciclo_materia($id))
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


    public function pdf_file_check($str, $nombre)
    {
        return $this->template->pdf_file_check($str, $nombre);
    }
    

    public function asignar_correlativa($id)
    {   
        $data['ciclo_materia'] = $this->Ciclo_materia_model->get_ciclo_materia($id);
        
        if(isset($data['ciclo_materia']['id']))
        {
            $this->form_validation->set_rules('id_correlativa',lang('form_last_name'),'required');
            $this->form_validation->set_rules('id_correlativa_tipo',lang('form_last_name'),'required');
            
            if($this->form_validation->run())     
            {   
                $params = array(
                    'id_ciclo_materia' => $data['ciclo_materia']['id'],
                    'id_correlativa' => $this->input->post('id_correlativa'),
                    'id_correlativa_tipo' => $this->input->post('id_correlativa_tipo')
                );          
                
                if ($this->Ciclo_materia_model->add_correlativa($params))
                    $mensaje =  $this->template->cargar_alerta('success', lang('record_success'), 
                                    sprintf(lang('record_edit_success_text'), $this->name));    
                else   
                    $mensaje = $this->template->cargar_alerta('danger', lang('record_error'),
                                    sprintf(lang('record_edit_error_text'), $this->name));    
                    
                redirect('abm/ciclo_materia/asignar_correlativa/'.$data['ciclo_materia']['id'], 'refresh');
            }
            else
            {
                $data['ciclos_materias'] = $this->Ciclo_materia_model->get_ciclos_materias_by_plan($data['ciclo_materia']['id_plan'], $data['ciclo_materia']['codigo']);
                $data['tipos'] = $this->Ciclo_materia_model->get_all_correlativas_tipo();
                $data['correlativas'] = $this->Ciclo_materia_model->get_correlativas($id);
                
                $data['user'] = $this->ion_auth->user()->row(); 
        
                $this->template->cargar_vista('abm/ciclo_materia/asignar_correlativa', $data);
            }
        }
        else
            show_error(sprintf(lang('no_existe'), $this->name));
    }

    public function remove_correlativa($id)
    {
        $correlativa = $this->Ciclo_materia_model->get_correlativa($id);

        if(isset($correlativa['id']))
        {
            if ($this->Ciclo_materia_model->delete_correlativa($id))
                $mensaje =  $this->template->cargar_alerta('success', lang('record_success'), 
                                sprintf(lang('record_remove_success_text'), $this->name));    
            else   
                $mensaje = $this->template->cargar_alerta('danger', lang('record_error'),
                                sprintf(lang('record_remove_error_text'), $this->name));    
                
            redirect('abm/ciclo_materia/asignar_correlativa/'.$correlativa['id_ciclo_materia'], 'refresh');
        }
        else
            show_error(sprintf(lang('no_existe'), $this->name));
    
    }


    public function asignar_optativas($id)
    {   
        $data['ciclo_materia'] = $this->Ciclo_materia_model->get_ciclo_materia($id);

        if(isset($data['ciclo_materia']['id']))
        {
            $this->form_validation->set_rules('id_optativa',lang('form_optional'),'required');
        
            if($this->form_validation->run())     
            {   
                $params = array(
                    'id_origen' => $data['ciclo_materia']['id'],
                    'id_optativa' => $this->input->post('id_optativa')
                );          

                if ($this->Ciclo_materia_model->add_optativa($params))
                    $mensaje =  $this->template->cargar_alerta('success', lang('record_success'), 
                                    sprintf(lang('record_edit_success_text'), $this->name));    
                else   
                    $mensaje = $this->template->cargar_alerta('danger', lang('record_error'),
                                    sprintf(lang('record_edit_error_text'), $this->name));    
                    
                redirect('abm/ciclo_materia/asignar_optativas/'.$data['ciclo_materia']['id'], 'refresh');
            }
            else
            {
                $data['ciclos_materias'] = $this->Ciclo_materia_model->get_ciclos_materias();
                $data['optativas'] = $this->Ciclo_materia_model->get_all_optativas();
                $data['optativas_materia'] = $this->Ciclo_materia_model->get_optativas_by_materia($id);

                $data['user'] = $this->ion_auth->user()->row(); 
        
                $this->template->cargar_vista('abm/ciclo_materia/asignar_optativa', $data);
            }
        }
        else
            show_error(sprintf(lang('no_existe'), $this->name));
    }


    public function remove_optativa($id)
    {
        $optativa = $this->Ciclo_materia_model->get_optativa($id);

        if(isset($optativa['id']))
        {
            if ($this->Ciclo_materia_model->delete_optativa($id))
                $mensaje =  $this->template->cargar_alerta('success', lang('record_success'), 
                                sprintf(lang('record_remove_success_text'), $this->name));    
            else   
                $mensaje = $this->template->cargar_alerta('danger', lang('record_error'),
                                sprintf(lang('record_remove_error_text'), $this->name));    
                
            redirect('abm/ciclo_materia/asignar_optativas/'.$optativa['id_origen'], 'refresh');
        }
        else
            show_error(sprintf(lang('no_existe'), $this->name));
    
    }
}
