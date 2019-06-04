<?php
 
class Cvar extends MX_Controller{
    
    public $name = 'CVAR';
    function __construct(){
        parent::__construct();
        $this->load->module('template');
        $this->load->model('Docente_model');
        $this->load->model('Persona_model');
        $this->load->model('Docente_categoria_model');
        $this->load->add_package_path(APPPATH.'third_party/ion_auth/');
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->load->helper(array('language'));
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
    } 


    function add()
    {   

        $this->form_validation->set_rules('apellido',lang('form_last_name'),'required');
        $this->form_validation->set_rules('nombre','1º Nombre','required');
        $this->form_validation->set_rules('cuit',lang('form_cuit'),'alpha_numeric');
        $this->form_validation->set_rules('email1',sprintf(lang('form_email'),'1'),'required|valid_email');
        $this->form_validation->set_rules('email2',sprintf(lang('form_email'),'2'),'valid_email');

        
        if($this->form_validation->run())     
        {   
            $params_persona = array(
                'apellido' => $this->input->post('apellido'),
                'nombre' => $this->input->post('nombre'),
                'nombre_2' => $this->input->post('nombre_2'),
                'dni' => $this->input->post('dni'),
                'cuit' => $this->input->post('cuit'),
                'email1' => $this->input->post('email1'),
                'email2' => $this->input->post('email2')
            );

            $persona_id = $this->Persona_model->add_persona($params_persona);

            $params_docente = array(
                'persona_id' => $persona_id,
                'id_docente_categoria' => $this->input->post('id_docente_categoria'),
                'descripcion' => $this->input->post('descripcion')
            );

            
            $docente_id = $this->Docente_model->add_docente($params_docente);
            
            if ($persona_id && $docente_id)
                    $mensaje =  $this->template->cargar_alerta('success', lang('record_success'), 
                                sprintf(lang('record_add_success_text'), $this->name));    
            else   
                    $mensaje = $this->template->cargar_alerta('danger', lang('record_error'),
                                sprintf(lang('record_add_error_text'), $this->name)); 
                    
            $this->index($mensaje);
        }
        else
        {
            $data['categorias'] = $this->Docente_categoria_model->get_all_categoria(); 
            $data['user'] = $this->ion_auth->user()->row(); 
        
            $this->template->cargar_vista('abm/docente/add', $data);
        }
    }  

    /*
     * Editing a docente
     */
    function edit($id)
    {   
        $data['docente'] = $this->Docente_model->get_docente($id);
        $data['persona'] = $this->Persona_model->get_persona($data['docente']['id']);

        $data['docente']=array_merge($data['docente'], $data['persona']); 
        
        if(isset($data['docente']['id']))
        {
            $this->form_validation->set_rules('apellido',lang('form_last_name'),'required');
            $this->form_validation->set_rules('nombre','1º Nombre','required');
            $this->form_validation->set_rules('cuit',lang('form_cuit'),'alpha_numeric');
            $this->form_validation->set_rules('email1',sprintf(lang('form_email'),'1'),'required|valid_email');
            $this->form_validation->set_rules('email2',sprintf(lang('form_email'),'2'),'valid_email');
        
            if($this->form_validation->run())     
            {   

                $params_persona = array(
                    'apellido' => $this->input->post('apellido'),
                    'nombre' => $this->input->post('nombre'),
                    'nombre_2' => $this->input->post('nombre_2'),
                    'dni' => $this->input->post('dni'),
                    'cuit' => $this->input->post('cuit'),
                    'email1' => $this->input->post('email1'),
                    'email2' => $this->input->post('email2')
                );

                $persona_id = $this->Persona_model->update_persona($id, $params_persona);

                $params_docente = array(
                    'persona_id' => $data['persona']['id'],
                    'id_docente_categoria' => $this->input->post('categoria'),
                    'descripcion' => $this->input->post('descripcion'),
                );

                $docente_id = $this->Docente_model->update_docente($id,$params_docente);            

                if ($persona_id && $docente_id)
                    $mensaje =  $this->template->cargar_alerta('success', lang('record_success'), 
                                    sprintf(lang('record_edit_success_text'), $this->name));    
                else   
                    $mensaje = $this->template->cargar_alerta('danger', lang('record_error'),
                                    sprintf(lang('record_edit_error_text'), $this->name));    
                    
                $this->index($mensaje);
            }
            else
            {
                $data['categorias'] = $this->Docente_categoria_model->get_all_categoria(); 
                $data['user'] = $this->ion_auth->user()->row(); 
        
                $this->template->cargar_vista('abm/docente/edit', $data);
            }
        }
        else
            show_error(sprintf(lang('no_existe'), $this->name));
    } 

    
}