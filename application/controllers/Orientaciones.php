<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Orientaciones extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Orientaciones_model');
    } 

    /*
     * Listing of orientaciones
     */
    function index()
    {
        $params['limit'] = RECORDS_PER_PAGE; 
        $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
        
        $config = $this->config->item('pagination');
        $config['base_url'] = site_url('orientaciones/index?');
        $config['total_rows'] = $this->Orientaciones_model->get_all_orientaciones_count();
        $this->pagination->initialize($config);

        $data['orientaciones'] = $this->Orientaciones_model->get_all_orientaciones($params);
        
        $data['_view'] = '/abm/orientaciones/index';
        $this->load->view('layouts/main',$data);
    }

    /*
     * Adding a new orientacione
     */
    function add()
    {   
        $this->load->library('form_validation');

		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('id_plan','Id Plan','required');
		
		if($this->form_validation->run())     
        {   
            $params = array(
				'id_plan' => $this->input->post('id_plan'),
				'nombre' => $this->input->post('nombre'),
            );
            
            $Orientaciones_id = $this->Orientaciones_model->add_orientaciones($params);
            redirect('orientaciones/index');
        }
        else
        {
			$this->load->model('Planes_model');
			$data['all_planes'] = $this->Planes_model->get_all_planes();
            
            $data['_view'] = '/abm/orientaciones/add';
            $this->load->view('layouts/main',$data);
        }
    }  

    /*
     * Editing a orientacione
     */
    function edit($id)
    {   
        // check if the orientacione exists before trying to edit it
        $data['orientaciones'] = $this->Orientaciones_model->get_orientaciones($id);
        
        if(isset($data['orientaciones']['id']))
        {
            $this->load->library('form_validation');

			$this->form_validation->set_rules('nombre','Nombre','required');
			$this->form_validation->set_rules('id_plan','Id Plan','required');
		
			if($this->form_validation->run())     
            {   
                $params = array(
					'id_plan' => $this->input->post('id_plan'),
					'nombre' => $this->input->post('nombre'),
                );

                $this->Orientaciones_model->update_orientaciones($id,$params);            
                redirect('orientaciones/index');
            }
            else
            {
				$this->load->model('Planes_model');
				$data['all_planes'] = $this->Planes_model->get_all_planes();

                $data['_view'] = '/abm/orientaciones/edit';
                $this->load->view('layouts/main',$data);
            }
        }
        else
            show_error('The orientacione you are trying to edit does not exist.');
    } 

    /*
     * Deleting orientacione
     */
    function remove($id)
    {
        $orientaciones = $this->Orientaciones_model->get_orientaciones($id);


        // check if the orientacione exists before trying to delete it
        if(isset($orientaciones['id']))
        {
            $this->Orientaciones_model->delete_orientaciones($id);
            redirect('orientaciones/index');
        }
        else
            show_error('The orientacione you are trying to delete does not exist.');
    }
    
}
