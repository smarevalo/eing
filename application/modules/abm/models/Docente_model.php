<?php
 
class Docente_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get docente by id
     */
    function get_docente($id)
    {
        return $this->db->get_where('docentes',array('id'=>$id))->row_array();
    }
    
    /*
     * Get all docente count
     */
    function get_all_docente_count()
    {
        $this->db->from('docentes');
        return $this->db->count_all_results();
    }
        
    /*
     * Get all docente
     */
    function get_all_docente($params = array())
    {
        $this->db->select('docentes.id, CONCAT(persona.apellido, ", ",  persona.nombre) as docente, docente_categoria.nombre AS categoria, docentes.descripcion');    
        $this->db->from('docentes');
        $this->db->join('persona', 'docentes.persona_id = persona.id');
        $this->db->join('docente_categoria', 'docentes.id_docente_categoria = docente_categoria.id', 'left');
        if(isset($params) && !empty($params))
            $this->db->limit($params['limit'], $params['offset']);   
        $this->db->order_by('id', 'desc');

        return $this->db->get()->result();
    }
        
    /*
     * function to add new docente
     */
    function add_docente($params)
    {
        $this->db->insert('docentes',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update docente
     */
    function update_docente($id,$params)
    {
        $this->db->where('id',$id);
        return $this->db->update('docentes',$params);
    }
    
    /*
     * function to delete docente
     */
    function delete_docente($id)
    {
        return $this->db->delete('docentes',array('id'=>$id));
    }



    //CVAR

    /*
     * Get docente by id
     */
    function get_cvar_by_docente($id)
    {
        return $this->db->get_where('cvar',array('id_docente'=>$id))->row_array();
    }


    /*
     * Get all cvar docente
     */
    function get_all_cvar_docente($params = array())
    {
        $this->db->select('docentes.id, CONCAT(persona.apellido, ", ",  persona.nombre) as docente, docente_categoria.nombre AS categoria, cvar.areas');    
        $this->db->from('docentes');
        $this->db->join('persona', 'docentes.persona_id = persona.id');
        $this->db->join('docente_categoria', 'docentes.id_docente_categoria = docente_categoria.id', 'left');
        $this->db->join('cvar', 'docentes.id = cvar.id_docente');
        if(isset($params) && !empty($params))
            $this->db->limit($params['limit'], $params['offset']);
        $this->db->order_by('id', 'desc');

        return $this->db->get()->result();
    }

    function add_cvar($params)
    {
        $this->db->insert('cvar',$params);
        return $this->db->insert_id();
    }

    /*
     * function to update cvar
     */
    function update_cvar($id,$params)
    {
        $this->db->where('id_docente',$id);
        return $this->db->update('cvar',$params);
    }


    //MATERIAS ASIGNADAS

    function get_materias_asignadas($id)
    {
        $this->db->select('materia_docente.id as id, carrera.nombre as carrera, planes.nombre as plan, ciclos.nombre as ciclo, orientaciones.nombre as orientacion, materias.nombre as materia');    
        $this->db->from('materia_docente');
        $this->db->join('docentes', 'docentes.id = materia_docente.id_docente');
        $this->db->join('ciclo_materia', 'ciclo_materia.id = materia_docente.id_ciclo_materia');
        $this->db->join('materias', 'materias.id = ciclo_materia.id_materia');
        $this->db->join('ciclos', 'ciclos.id = ciclo_materia.id_ciclo');
        $this->db->join('orientaciones', 'orientaciones.id = ciclos.id_orientacion', 'left');
        $this->db->join('planes', 'planes.id = ciclos.id_plan');
        $this->db->join('carrera', 'carrera.id = planes.id_carrera');
        $this->db->where('materia_docente.id_docente', $id); 
        $this->db->order_by('carrera, plan, ciclo', 'desc');

        return $this->db->get()->result();
    }

    function add_materia_docente($params)
    {
        $this->db->insert('materia_docente',$params);
        return $this->db->insert_id();
    }

    function delete_materia_docente($id)
    {
        return $this->db->delete('materia_docente',array('id'=>$id));
    }

    function get_materia_asignada($id)
    {
        return $this->db->get_where('materia_docente',array('id'=>$id))->row_array();
    }
}
