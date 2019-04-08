<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Ciclo extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Ciclo_model');
    } 

    /*
     * Listing of ciclos
     */
    function index()
    {
        $params['limit'] = RECORDS_PER_PAGE; 
        $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
        
        $config = $this->config->item('pagination');
        $config['base_url'] = site_url('ciclo/index?');
        $config['total_rows'] = $this->Ciclo_model->get_all_ciclos_count();
        $config['attributes'] = array('class' => 'page-link');
        $this->pagination->initialize($config);

        $data['ciclos'] = $this->Ciclo_model->get_all_ciclos($params);
        
        $data['_view'] = 'abm/ciclo/index';
        $this->load->view('layouts/main',$data);
    }

    /*
     * Adding a new ciclo
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
				'id_orientacion' => $this->input->post('id_orientacion'),
				'nombre' => $this->input->post('nombre'),
            );
            
            $ciclo_id = $this->Ciclo_model->add_ciclo($params);
            redirect('ciclo/index');
        }
        else
        {
			$this->load->model('Planes_model');
			$data['all_planes'] = $this->Planes_model->get_all_planes();

			$this->load->model('Orientaciones_model');
			$data['all_orientaciones'] = $this->Orientaciones_model->get_all_orientaciones();
            
            $data['_view'] = 'abm/ciclo/add';
            $this->load->view('layouts/main',$data);
        }
    }  

    /*
     * Editing a ciclo
     */
    function edit($id)
    {   
        // check if the ciclo exists before trying to edit it
        $data['ciclo'] = $this->Ciclo_model->get_ciclo($id);
        
        if(isset($data['ciclo']['id']))
        {
            $this->load->library('form_validation');

			$this->form_validation->set_rules('nombre','Nombre','required');
			$this->form_validation->set_rules('id_plan','Id Plan','required');
		
			if($this->form_validation->run())     
            {   
                $params = array(
					'id_plan' => $this->input->post('id_plan'),
					'id_orientacion' => $this->input->post('id_orientacion'),
					'nombre' => $this->input->post('nombre'),
                );

                $this->Ciclo_model->update_ciclo($id,$params);            
                redirect('ciclo/index');
            }
            else
            {
				$this->load->model('Planes_model');
				$data['all_planes'] = $this->Planes_model->get_all_planes();

				$this->load->model('Orientaciones_model');
				$data['all_orientaciones'] = $this->Orientaciones_model->get_all_orientaciones();

                $data['_view'] = 'abm/ciclo/edit';
                $this->load->view('layouts/main',$data);
            }
        }
        else
            show_error('The ciclo you are trying to edit does not exist.');
    } 

    /*
     * Deleting ciclo
     */
    function remove($id)
    {
        $ciclo = $this->Ciclo_model->get_ciclo($id);

        // check if the ciclo exists before trying to delete it
        if(isset($ciclo['id']))
        {
            $this->Ciclo_model->delete_ciclo($id);
            redirect('ciclo/index');
        }
        else
            show_error('The ciclo you are trying to delete does not exist.');
    }
    
}
