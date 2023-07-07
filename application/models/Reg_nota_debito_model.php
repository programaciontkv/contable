<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_nota_debito_model extends CI_Model {


	public function lista_notas_debito(){
		$this->db->from('erp_registro_nota_debito n');
		$this->db->join('erp_vendedor v','n.vnd_id=v.vnd_id');
		$this->db->join('erp_estados e','f.rnd_estado=e.est_id');
		$this->db->order_by('rnd_numero');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_notas_empresa_emisor($emp_id,$emi_id){
		$query="SELECT rnd_id, rnd_fecha_emision,rnd_numero,rnd_identificacion,rnd_nombre,rnd_total_valor,est_descripcion, f.reg_total, rnd_num_comp_modifica, rnd_estado
			FROM erp_registro_nota_debito n, erp_estados e, erp_reg_documentos f  
			WHERE n.rnd_estado=e.est_id AND n.reg_id=f.reg_id AND n.emp_id= $emp_id 
			ORDER BY rnd_numero";
		$resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_nota_buscador($text,$f1,$f2,$emp_id,$emi_id){
		$query="SELECT rnd_id, rnd_fecha_emision,rnd_numero,rnd_identificacion,rnd_nombre,rnd_total_valor,est_descripcion, f.reg_total, rnd_num_comp_modifica, rnd_estado
			FROM erp_registro_nota_debito n, erp_estados e, erp_reg_documentos f
			WHERE n.rnd_estado=e.est_id AND n.reg_id=f.reg_id AND n.emp_id= $emp_id and (rnd_numero like '%$text%' or rnd_nombre like '%$text%' or rnd_identificacion like '%$text%') and rnd_fecha_emision between '$f1' and '$f2'
			ORDER BY rnd_numero desc";
		$resultado=$this->db->query($query);
		return $resultado->result();
	}

	
	public function lista_una_nota($id){
		$this->db->from('erp_registro_nota_debito n');
		$this->db->join('erp_i_cliente c','c.cli_id=n.cli_id');
		$this->db->join('erp_emisor m','m.emi_id=n.emi_id');
		$this->db->join('erp_empresas em','em.emp_id=n.emp_id');
		$this->db->join('erp_estados e','n.rnd_estado=e.est_id');
		$this->db->where('rnd_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}


	public function lista_detalle_nota($id){
		$this->db->from('erp_reg_det_nota_debito d');
		$this->db->where('rnd_id',$id);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	

	public function insert($data){
		$this->db->insert("erp_registro_nota_debito",$data);
		return $this->db->insert_id();
	}

	public function insert_detalle($data){
		return $this->db->insert("erp_reg_det_nota_debito",$data);
	}

	
	public function update($id,$data){
		$this->db->where('rnd_id',$id);
		return $this->db->update("erp_registro_nota_debito",$data);
			
	}

	public function delete($id){
		$this->db->where('id',$id);
		return $this->db->delete("erp_registro_nota_debito");
			
	}


	
   
	public function delete_detalle($id){
		$this->db->where('rnd_id',$id);
		return $this->db->delete("erp_reg_det_nota_debito");
			
	}


	

	public function lista_nota_sin_autorizar(){
		$this->db->from('erp_registro_nota_debito n');
		$this->db->join('erp_vendedor v','n.vnd_id=v.vnd_id');
		$this->db->join('erp_i_cliente c','c.cli_id=n.cli_id');
		$this->db->join('erp_emisor m','m.emi_id=n.emi_id');
		$this->db->join('erp_empresas em','em.emp_id=n.emp_id');
		$this->db->join('erp_estados e','n.rnd_estado=e.est_id');
		$this->db->where('rnd_estado', '4');
		$this->db->order_by('rnd_id','desc');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_suma_notas_factura($id){
		$query="SELECT sum(rnd_total_valor) as rnd_total_valor from erp_registro_nota_debito where (rnd_estado=4 or rnd_estado=6) and fac_id=$id";
		$resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_cheque_nota($id){
		$this->db->from('erp_cheques ch');
		$this->db->where('doc_id',$id);
		$this->db->where('chq_tipo_doc','11');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function update_pagos($id,$data){
		$this->db->where('pag_id_chq',$id);
		$this->db->where('pag_tipo','9');
		return $this->db->update("erp_pagos_documentos",$data);
			
	}


	public function lista_doc_duplicado($id,$num){
		$this->db->from('erp_registro_nota_debito');
		$this->db->where('cli_id',$id);
		$this->db->where('rnd_numero',$num);
		$this->db->where('rnd_estado!=3',null);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_suma_pagos($id){
		$this->db->from('pagosxdocumento');
		$this->db->where("reg_id=$id", null);
		$resultado=$this->db->get();
		return $resultado->row();
	}
    
}

?>