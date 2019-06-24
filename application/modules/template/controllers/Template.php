<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Template extends MX_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/ion_auth/');
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->load->helper(array('language'));
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	}
	
	public function commonHeader()
	{
		$this->load->view('template/common-header');
	}
	
	public function adminHeader()
	{
		$this->load->view('template/header');
		if ($this->ion_auth->is_admin()){
			$data['abm'] = $this->load->view('template/navbar_abm', NULL, TRUE);
			$data['usuarios'] = $this->load->view('template/navbar_usuarios', NULL, TRUE);
		}
		else{
			$data['abm'] = $data['usuarios'] = '';
		}
		$this->load->view('template/navbar', $data);
	}

	public function footer()
	{
		$this->load->view('template/footer');
	}

	public function commonFooter()
	{
		$this->load->view('template/common-footer');
	}

	public function cargar_vista($vista, $data)
	{
		$this->commonHeader();
        $this->adminHeader();
        if ($this->ion_auth->is_admin())
            $data['_view'] = $vista;
        else
            $data['_view'] = 'sin-permiso';
        $this->_render_page('../../abm/views/layouts/main', $data);
        $this->footer();
        $this->commonFooter();
	}

	public function _render_page($view, $data = NULL, $returnhtml = FALSE)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		// This will return html on 3rd argument being true
		if ($returnhtml)
		{
			return $view_html;
		}
	}


	//Links

	public function get_params()
	{
		$params['limit'] = RECORDS_PER_PAGE; 
        $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
        return $params;
	}

	public function get_config($pag, $count)
	{
		$config = $this->config->item('pagination');
        $config['base_url'] = site_url($pag);
        $config['total_rows'] = $count;
        $config['attributes'] = array('class' => 'page-link');
        
        return $config;
	}

	public function get_links()
	{
		$links = '<div class="clearfix">
					<div class="float-right">
					    '.$this->pagination->create_links().'    
					</div>
				</div>';
        
        return $links;
	}

	//Fin Links


	public function subir_archivo($path, $type, $name)
	{
		$config['upload_path'] = $path;
        $config['allowed_types'] = $type;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($name))
        {
            $archivo = array('error' => $this->upload->display_errors());
        }
        else
        {
            $archivo = $this->upload->data();
        }
        
        return $archivo;
	}

	public function cargar_alerta($tipo, $titulo, $mensaje)
	{
		$data['titulo'] = $titulo;
		$data['tipo'] = $tipo;
		$data['mensaje'] = $mensaje;
		return $this->load->view('../../abm/views/layouts/alerta',$data, true);
	}


	public function boton_nuevo($url, $titulo)
	{
		$boton = '<div class="clearfix">
					<div class="float-right">
						<a href='.site_url($url).' class="btn btn-success">'.$titulo.'</a> 
					</div>
				</div>';
		return $boton;
	}


	//Formulario
	public function cargar_input()
	{
		$args = func_get_args();
		  
		$data['label'] = $args[0];
		$data['nombre'] = $args[1];
		$data['obligatorio'] = $args[3];
		$data['error'] = $args[4];
		$data['value'] = $args[5];
		$data['type'] = $args[2];
		 
		if ( count(func_get_args()) == 7){
			$data['mensaje'] = $args[6];
		  }
		  
		  elseif ( count(func_get_args()) == 7 ){

		  }

		return $this->load->view('label-input',$data, true);
	}	

	public function cargar_select($label, $nombre, $obligatorio, $error, $array, $comp)
	{
		$data['label'] = $label;
		$data['nombre'] = $nombre;
		$data['obligatorio'] = $obligatorio;
		$data['error'] = $error;
		$data['array'] = $array;
		$data['comp'] = $comp;

		return $this->load->view('label-select',$data, true);
	}	

	public function cargar_textarea($label, $nombre, $obligatorio, $error, $descripcion)
	{
		$data['label'] = $label;
		$data['nombre'] = $nombre;
		$data['obligatorio'] = $obligatorio;
		$data['error'] = $error;
		$data['descripcion'] = $descripcion;

		return $this->load->view('label-textarea',$data, true);
	}	

	public function cargar_submit()
	{
		return $this->load->view('form_button');
	}


	//carga de archivos
	public function pdf_file_check($str, $nombre)
    {
        if ($_FILES[$nombre]['size']>0){

            if($_FILES[$nombre]['type'] == 'application/pdf')
            {
                return TRUE;
            }
            else
            {
                $this->form_validation->set_message('pdf_file_check', '{field} solo puede ser del tipo PDF');
                return FALSE;
            }
        }

        
    }

    public function image_file_check($str, $nombre)
    {
        if ($_FILES[$nombre]['size']>0){

            if($_FILES[$nombre]['type'] == 'image/jpeg' || $_FILES[$nombre]['type'] == 'image/png')
            {
                return TRUE;
            }
            else
            {
                $this->form_validation->set_message('image_file_check', '{field} solo puede ser del tipo jpg o png');
                return FALSE;
            }
        }
        
    }


    public function get_item($array, $id, $value)
    {
    	$seleccion=null;
		foreach ($array as $item) {
			if($item->id == $id){
				$seleccion = $item->$value;
			}
		}
		return($seleccion);
    }


    public function get_perfil_docente($id)
	{
        $this->load->model('Categoria_model');
		$data['categorias'] = $this->Docente_categoria_model->get_all_categoria();
		$data['docente'] = $this->Docente_model->get_docente($id);
        $data['persona'] = $this->Persona_model->get_persona($data['docente']['persona_id']);
        $data['docente']= array_merge($data['persona'], $data['docente']); 

		return $this->load->view('docente-profile',$data, true);
	}

	public function get_perfil_materia($id)
	{
        $this->load->model('Ciclo_materia_model');
        $this->load->model('Materia_model');
        $this->load->model('Materias_tipo_model');
        $this->load->model('Regimen_model');
        $this->load->model('Ciclo_model');
		$data['ciclo_materia'] = $this->Ciclo_materia_model->get_ciclo_materia($id);
		$materia = $this->Materia_model->get_materia($data['ciclo_materia']['id_materia']);
		$data['ciclo_materia']= array_merge($materia, $data['ciclo_materia']);
		
		$data['regimenes']= $this->Regimen_model->get_all_regimen();;
		$data['tipos']= $this->Materias_tipo_model->get_all_materias_tipo();
		$data['ciclos']= $this->Ciclo_model->get_all_ciclos();;
		return $this->load->view('materia-profile',$data, true);
	}	

	public function get_anios($years)
    {
    	$anios = null;
    	for ($i=1; $i <= $years; $i++) { 
    		$anios[$i] = new stdClass();
    		$anios[$i]->id = $i;
    		$anios[$i]->nombre = $i;
    	}
		return($anios);
    }

}