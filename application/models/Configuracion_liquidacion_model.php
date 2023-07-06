<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion_liquidacion_model extends CI_Model {

	public function lista_asientos(){
		$this->db->order_by('con_asiento');
		$resultado=$this->db->get('erp_config_asientos');
		return $resultado->result();
			
	}

	public function lista_configuracion_buscador($emp,$est,$txt){
		$query ="select cfa_codigo, cfa_descripcion, est_descripcion,cfa_estado from erp_config_asientos a, erp_estados e where a.cfa_estado=e.est_id and (cfa_codigo like '%$txt%' or cfa_descripcion like '%$txt%') and emp_id=$emp and cfa_estado='$est' group by cfa_codigo, cfa_descripcion, est_descripcion,cfa_estado order by cfa_codigo";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function ultima_configuracion(){
		$query ="SELECT * FROM erp_config_asientos ORDER BY cfa_codigo DESC LIMIT 1";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

    public function insert($data){
		
		return $this->db->insert("erp_config_asientos",$data);
			
	}



    public function lista_una_configuracion($id){
		$this->db->where('cfa_codigo',$id);
		$this->db->order_by('cfa_id');
		$resultado=$this->db->get('erp_config_asientos');
		return $resultado->row();
			
	}
	
	public function lista_detalle_configuracion($id){
		$this->db->where('cfa_codigo',$id);
		$this->db->order_by('cfa_id');
		$resultado=$this->db->get('erp_config_asientos');
		return $resultado->result();
			
	}

	public function delete($id){
		$this->db->where('cfa_codigo',$id);
		return $this->db->delete("erp_config_asientos");
			
	}

	public function update($id,$data){
		$this->db->where('cfa_codigo',$id);
		return $this->db->update("erp_config_asientos",$data);
			
	}




	public function listar_asientos_debe($as, $cuenta, $id) {
		$query ="select * from erp_config_asientos where con_concepto_debe='$cuenta' and con_asiento='$as' and con_id='$id'";
        $resultado=$this->db->query($query);
        return $resultado->row();
    }

    public function listar_asientos_haber($as, $cuenta, $id) {
    	$query ="select * from erp_config_asientos where con_concepto_haber='$cuenta' and con_asiento='$as' and con_id='$id'";
        $resultado=$this->db->query($query);
        return $resultado->row();
        //echo $this->db->last_query().'<br>';

        
    }	

    public function asientos_pago($con_documento)
    {
    	$query="select *from erp_config_asientos where con_documento='$con_documento'";
    	$resultado =$this->db->query($query);
    	return $resultado->result();
			
    }

    public function asiento_reg_fac($id){
    	$this->db->select('con_asiento');
    	$this->db->where('doc_id',$id);
    	$this->db->group_by('con_asiento');
    	$resultado=$this->db->get('erp_config_asientos');
		return $resultado->row();
    }
    public function asiento_reg_fac_detalle($id){
    	$this->db->where('doc_id',$id);
    	$resultado=$this->db->get('erp_config_asientos');
		return $resultado->result();
		//echo $this->db->last_query().'<br>';

    }
    public function asiento_reg_ret_detalle($id){
    	$this->db->where('doc_id',$id);
    	$this->db->limit(1);
    	$resultado=$this->db->get('erp_config_asientos');
		return $resultado->row();
		//echo $this->db->last_query().'<br>';

    }

    public function lista_tipo_configuraciones($id){
		$this->db->select('cfa_codigo,cfa_descripcion',$id);
		$this->db->where('cfa_estado',$id);
		$this->db->group_by('cfa_codigo,cfa_descripcion');
		$this->db->order_by('cfa_descripcion');
		$resultado=$this->db->get('erp_config_asientos');
		return $resultado->result();
			
	}
}

?>