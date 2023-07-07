<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Liquidacion_model extends CI_Model {

	public function lista_asientos(){
		$this->db->order_by('con_asiento');
		$resultado=$this->db->get('erp_config_asientos');
		return $resultado->result();
			
	}

	public function lista_asientos_buscador($emp,$f1,$f2,$txt){
		$query ="select con_asiento, con_concepto, con_fecha_emision, sum(con_valor_debe) as con_valor_debe, sum(con_valor_haber) as con_valor_haber, con_estado, est_descripcion, cli_raz_social from erp_asientos_contables a, erp_estados e, erp_i_cliente c where a.con_estado=e.est_id and c.cli_id=a.cli_id and (con_asiento like '%$txt%' or cli_raz_social like '%$txt%' or con_concepto like '%$txt%') and mod_id=23 and con_fecha_emision between '$f1' and '$f2' and emp_id=$emp group by con_asiento, con_concepto, con_fecha_emision, con_estado, est_descripcion, cli_raz_social order by con_fecha_emision, con_asiento";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function ultima_configuracion(){
		$query ="SELECT * FROM erp_config_asientos ORDER BY cfa_codigo DESC LIMIT 1";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

    public function ultimo_asiento_liquidacion(){
		$query ="SELECT * FROM erp_asientos_contables where con_asiento like 'AL%' ORDER BY con_asiento DESC LIMIT 1";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function siguiente_asiento_liquidacion() {
        
            $rst = $this->ultimo_asiento_liquidacion();
            if (!empty($rst)) {
                $sec = (substr($rst->con_asiento, -10) + 1);
                $n_sec = 'AL' . substr($rst->con_asiento, 2, (10 - strlen($sec))) . $sec;
            } else {
                $n_sec = 'AL0000000001';
            }
            return $n_sec;
        
    }

    public function insert($data){
		
		return $this->db->insert("erp_asientos_contables",$data);
			
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
		$this->db->where('con_asiento',$id);
		return $this->db->delete("erp_asientos_contables");
			
	}

	public function update($id,$data){
		$this->db->where('con_asiento',$id);
		return $this->db->update("erp_asientos_contables",$data);
			
	}


	public function lista_un_asiento($id){
		$this->db->from('erp_asientos_contables a');
		$this->db->join('erp_i_cliente c', 'c.cli_id=a.cli_id');
		$this->db->where('con_asiento',$id);
		$this->db->order_by('con_id');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_detalle_asiento($id){
		$this->db->where('con_asiento',$id);
		$this->db->order_by('con_id');
		$resultado=$this->db->get('erp_asientos_contables');
		return $resultado->result();
			
	}
}

?>