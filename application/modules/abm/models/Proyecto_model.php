<?php
 
class Proyecto_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get proyecto by id
     */
    function get_proyecto($id)
    {
        return $this->db->get_where('proyectos',array('id'=>$id))->row_array();
    }

    function get_proy_est($id_proyecto)
    {
        return $this->db->get_where('proyecto_estudiante',array('id_proyecto'=>$id_proyecto))->row_array();
    }
    
    /*
     * Get all proyectos count
     */
    function get_all_proyectos_count()
    {
        $this->db->from('proyectos');
        return $this->db->count_all_results();
    }
        
    /*
     * Get all proyectos
     */
    function get_all_proyectos($tipo)
    {
        $this->db->select('proyectos.id, tipo_proyecto.nombre AS tipo, proyectos.titulo, institucion.razon_social AS institucion, CONCAT(persona.apellido, ", ", persona.nombre) AS tutor, proyectos.activo, proyectos.creacion, proyectos.id_tipo');
        $this->db->from('proyectos'); 
        $this->db->join('tipo_proyecto', 'tipo_proyecto.id = proyectos.id_tipo');
        $this->db->join('institucion', 'institucion.id = proyectos.id_institucion');
        $this->db->join('docentes', 'docentes.id = proyectos.id_tutor');
        $this->db->join('persona', 'persona.id = docentes.persona_id');
        $this->db->where('proyectos.id_tipo',$tipo);
        $this->db->order_by('id', 'desc');
        return $this->db->get()->result();
    }

    function get_tipos_proyecto()
    {
        return $this->db->get('tipo_proyecto')->result();
    }

    function get_tipo_proyecto($id)
    {
        return $this->db->get_where('tipo_proyecto',array('id'=>$id))->result();
    }

    function get_instituciones()
    {
        $this->db->select('institucion.id, institucion.razon_social as nombre');  
        $this->db->order_by('id', 'desc');
        return $this->db->get('institucion')->result();
    }
        
    /*
     * function to add new proyecto
     */
    function add_proyecto($params)
    {
        $this->db->insert('proyectos',$params);
        return $this->db->insert_id();
    }

    function add_proyecto_estudiante($params)
    {
        $this->db->insert('proyecto_estudiante',$params);
        return $this->db->insert_id();
    }
    
    function add_proyecto_documento($params)
    {
        $this->db->insert('proyecto_documento',$params);
        return $this->db->insert_id();
    }

    /*
     * function to update proyecto
     */
    function update_proyecto($id,$params)
    {
        $this->db->where('id',$id);
        return $this->db->update('proyectos',$params);
    }
    
    function update_proy_est($id,$params)
    {
        $this->db->where('id_proyecto',$id);
        return $this->db->update('proyecto_estudiante',$params);
    }

    /*
     * function to delete proyecto
     */
    function delete_proyecto($id)
    {
        return $this->db->delete('proyectos',array('id'=>$id));
    }

    function delete_proy_est($id)
    {
        return $this->db->delete('proyecto_estudiante',array('id_proyecto'=>$id));
    }

    function change_status($id, $params)
    {
        $this->db->where('id',$id);
        return $this->db->update('proyectos',$params);
    }

    function change_finalizacion($id, $params)
    {
        $this->db->where('id',$id);
        return $this->db->update('proyectos',$params);
    }
}
