<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion_liquidacion extends CI_Controller {

	private $permisos;

	function __construct(){
		parent:: __construct();
		if(!$this->session->userdata('s_login')){
			redirect(base_url());
		}
		$this->load->library('backend_lib');
		$this->load->model('backend_model');
		$this->permisos=$this->backend_lib->control();
		$this->load->library('form_validation');
		$this->load->model('configuracion_model'); 
		$this->load->model('configuracion_liquidacion_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('caja_model');
		$this->load->model('estado_model');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
	}

	public function _remap($method, $params = array()){
    
	    if(!method_exists($this, $method))
	      {
	       $this->index($method, $params);
	    }else{
	      return call_user_func_array(array($this, $method), $params);
	    }
  	}

	public function menus()
	{
		$menu=array(
					'menus' =>  $this->menu_model->lista_opciones_principal('1',$this->session->userdata('s_idusuario')),
					'sbmopciones' =>  $this->menu_model->lista_opciones_submenu('1',$this->session->userdata('s_idusuario'),$this->permisos->sbm_id),
					'actual'=>$this->permisos->men_id,
					'actual_sbm'=>$this->permisos->sbm_id,
					'actual_opc'=>$this->permisos->opc_id
				);
		return $menu;
	}
	
	

	public function index($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		///buscador 
		if($_POST){
			$text= trim($this->input->post('txt'));
			$estado= $this->input->post('estado');
		}else{
			$text= '';
			$estado='1';
		}

		
		$cns_configuracion=$this->configuracion_liquidacion_model->lista_configuracion_buscador($rst_cja->emp_id,$estado,$text);

		$data=array(
					'permisos'=>$this->permisos,
					'configuraciones'=>$cns_configuracion,
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'estado'=>$estado,
					'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
					'cns_estados'=>$this->estado_model->lista_estados_modulo($rst_opc->opc_id),
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('configuracion_liquidacion/lista',$data);
		$modulo=array('modulo'=>'configuracion_liquidacion');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cuentas'=>$this->plan_cuentas_model->lista_plan_cuentas_estado_tipo('1','1'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'opc_id'=>$rst_opc->opc_id,
						'configuracion'=> (object) array(
											'cfa_codigo'=>'',
											'cfa_descripcion'=>'',
											'emp_id'=>$rst_cja->emp_id,
										),
						'cns_det'=>(object) array(),
						'action'=>base_url().'configuracion_liquidacion/guardar/'.$opc_id
						);
			$this->load->view('configuracion_liquidacion/form',$data);
			$modulo=array('modulo'=>'configuracion_liquidacion');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$cfa_descripcion = $this->input->post('cfa_descripcion');
		$emp_id = $this->input->post('emp_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('cfa_descripcion','Descripcion','required');

		if($this->form_validation->run()){
			$n=0;
			$v=0;
			$rst_sec =$this->configuracion_liquidacion_model->ultima_configuracion();
			if(empty($rst_sec)){
				$sec=1;
			}else{
				$sec = ($rst_sec->cfa_codigo + 1);
			}	

		    if ($sec >= 0 && $sec < 10) {
		        $txt = '000000000';
		    } else if ($sec >= 10 && $sec < 100) {
		        $txt = '00000000';
		    } else if ($sec >= 100 && $sec < 1000) {
		        $txt = '0000000';
		    } else if ($sec >= 1000 && $sec < 10000) {
		        $txt = '000000';
		    } else if ($sec >= 10000 && $sec < 100000) {
		        $txt = '00000';
		    } else if ($sec >= 100000 && $sec < 1000000) {
		        $txt = '0000';
		    } else if ($sec >= 1000000 && $sec < 10000000) {
		        $txt = '000';
		    } else if ($sec >= 10000000 && $sec < 100000000) {
		        $txt = '00';
		    } else if ($sec >= 100000000 && $sec < 1000000000) {
		        $txt = '0';
		    } else if ($sec >= 1000000000 && $sec < 10000000000) {
		        $txt = '';
		    }
		    $secuencial = $txt . $sec;

		    while($n<$count_det){
			    $n++;
			    if($this->input->post("cfa_debe$n")!='' || $this->input->post("cfa_haber$n")!=''){
			    	$cfa_debe = $this->input->post("cfa_debe$n");
			    	$cfa_haber = $this->input->post("cfa_haber$n");
					$data=array(
							 	'cfa_codigo'=>trim($secuencial),
								'cfa_descripcion'=>trim($cfa_descripcion),
								'cfa_debe'=>$cfa_debe,
								'cfa_haber'=>$cfa_haber,
								'cfa_estado'=>'1',
								'emp_id'=>$emp_id,

					);
					if(!$this->configuracion_liquidacion_model->insert($data)){
						$v+=1;
					}
				}
			}			

			if($v==0){	
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CONFIGURACION LIQUIDACIONES',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'configuracion_liquidacion/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'configuracion_liquidacion/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	
	}

	public function editar($id,$opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		if($this->permisos->rop_actualizar){
			$cns_det=array();
			$detalle=$this->configuracion_liquidacion_model->lista_detalle_configuracion($id);
			foreach ($detalle as $det) {
				$cta_db=$this->plan_cuentas_model->lista_un_plan_cuentas($det->cfa_debe);
				
				if(!empty($cta_db->pln_descripcion)){
					$des_db=$cta_db->pln_descripcion;	
					$cod_db=$cta_db->pln_codigo;	
				}else{
					$des_db='';
					$cod_db='';
				}
				$cta_hb=$this->plan_cuentas_model->lista_un_plan_cuentas($det->cfa_haber);
				if(!empty($cta_hb->pln_descripcion)){
					$des_hb=$cta_hb->pln_descripcion;	
					$cod_hb=$cta_hb->pln_codigo;	
				}else{
					$des_hb='';
					$cod_hb='';
				}
				
				$dt= (object) array(
							'cfa_debe'=>$det->cfa_debe,
							'cfa_haber'=>$det->cfa_haber,
							'concepto_debe'=>$cod_db,
							'concepto_haber'=>$cod_hb,
							'descripcion_debe'=>$des_db,
							'descripcion_haber'=>$des_hb,
						);
				array_push($cns_det, $dt);
			}
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cuentas'=>$this->plan_cuentas_model->lista_plan_cuentas_estado_tipo('1','1'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'opc_id'=>$rst_opc->opc_id,
						'configuracion'=> $this->configuracion_liquidacion_model->lista_una_configuracion($id),
						'cns_det'=>$cns_det,
						'action'=>base_url().'configuracion_liquidacion/actualizar/'.$opc_id,
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('configuracion_liquidacion/form',$data);
			$modulo=array('modulo'=>'configuracion_liquidacion');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		$cfa_codigo = $this->input->post('cfa_codigo');
		$cfa_descripcion = $this->input->post('cfa_descripcion');
		$emp_id = $this->input->post('emp_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('cfa_descripcion','Descripcion','required');
		
		if($this->form_validation->run()){
			//borra configuracion_liquidacion
			$this->configuracion_liquidacion_model->delete($cfa_codigo);

			$n=0;
			$v=0;
		    while($n<$count_det){
			    $n++;
			    if($this->input->post("cfa_debe$n")!='' || $this->input->post("cfa_haber$n")!=''){
			    	$cfa_debe = $this->input->post("cfa_debe$n");
			    	$cfa_haber = $this->input->post("cfa_haber$n");
					$data=array(
							 	'cfa_codigo'=>trim($cfa_codigo),
								'cfa_descripcion'=>trim($cfa_descripcion),
								'cfa_debe'=>$cfa_debe,
								'cfa_haber'=>$cfa_haber,
								'cfa_estado'=>'1',
								'emp_id'=>$emp_id,

					);
					if(!$this->configuracion_liquidacion_model->insert($data)){
						$v+=1;
					}
				}
			}	

			if($v==0){	
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CONFIGURACION LIQUIDACIONES',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$cfa_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'configuracion_liquidacion/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'configuracion_liquidacion/editar/'.$id.'/'.$opc_id);
			}
		}else{
			$this->editar($id,$opc_id);
		}	
	}

	

	public function excel($opc_id){

    	$titulo='Configuracion Liquidaciones';
    	$file="configuracion_liquidaciones".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }
    
    public function cambiar_estado($estado,$id,$opc_id){
			
			$data=array(
		    			'cfa_estado'=>$estado, 
		    );

			$data_audito=array(
		    			'cfa_id'=>$id, 
		    			'Estado'=>$estado, 

		    );

		    if($this->configuracion_liquidacion_model->update($id,$data)){
		    	
		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CONFIGURACION LIQUIDACIONES',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($data_audito),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$id." ".$estado,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo "1";
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				echo "0";
			}
		
	}
    
	public function traer_cuenta($id){
		$rst=$this->plan_cuentas_model->lista_un_plan_cuentas_codigo(trim($id));
		
		if(!empty($rst)){

			$data=array(
						'pln_id'=>$rst->pln_id,
						'pln_codigo'=>$rst->pln_codigo,
						'pln_descripcion'=>$rst->pln_descripcion,
						);
			echo json_encode($data);
		
		
		}else{
			echo "";
		}

	}

	

	public function show_frame($id,$opc_id){
		if($_POST){
			$text= trim($this->input->post('txt'));
			$estado= $this->input->post('estado');
		}else{
			$text='';
			$estado='';
		}
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
	
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Configuracion Liquidaciones ',
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"configuracion_liquidacion/reporte/$id",
					'fec1'=>'',
					'fec2'=>'',
					'txt'=>$text,
					'estado'=>$estado,
					'tipo'=>'',
					'vencer'=>'',
					'vencido'=>'',
					'pagado'=>'',
					'familia'=>'',
					'tip'=>'',
					'detalle'=>'',
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'configuracion_liquidacion');
			$this->load->view('layout/footer',$modulo);
		}
    }

	public function reporte($id){
		require_once APPPATH.'third_party/fpdf/fpdf.php';
		$pdf = new FPDF();
	    $pdf->AddPage('P','A4',0);
	    $pdf->AddFont('Calibril','');//$pdf->SetFont('Calibri-Light', '', 9);
        $pdf->AddFont('Calibri-bold','');//$pdf->SetFont('Calibri-bold', '', 9);
	    $pdf->AliasNbPages();
	    $dc=$this->configuracion_model->lista_una_configuracion('2');
	    $dec=$dc->con_valor;
	    $rst1=$this->configuracion_liquidacion_model->lista_una_configuracion($id);
	    $emisor=$this->empresa_model->lista_una_empresa($rst1->emp_id);
	    $pdf->SetX(50);
        $pdf->Ln();

        $pdf->SetFont('Calibril', '', 9);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(190, 5, utf8_decode($emisor->emp_nombre), 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(190, 5, $emisor->emp_identificacion, 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(190, 5, $emisor->emp_ciudad."-".$emisor->emp_pais, 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(190, 5, utf8_decode("TELÉFONO: " ). $emisor->emp_telefono, 0, 0, 'L');
        $pdf->SetX(0);
        $pdf->Cell(190, 5, $pdf->Image('./imagenes/'.$emisor->emp_logo, 175, 4, 25), 0, 0, 'R');
        $pdf->setY(30);


	    $pdf->SetFont('Calibri-bold', '', 14);
       
        $pdf->Cell(80, 5, "Configuracion liquidacion No.: ", 0, 0, 'L');
        $pdf->SetTextColor(255,0, 0);
        $pdf->Cell(85, 5,$rst1->cfa_codigo, 0, 0, 'L');
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetTextColor(0,0, 0);
        $pdf->SetFont('Calibri-bold', '', 11);
        $pdf->Cell(6, 5, "No", 'TB', 0, 'C');
        $pdf->Cell(25, 5, "Codigo Debe", 'TB', 0, 'C');
        $pdf->Cell(65, 5, "Concepto Debe", 'TB', 0, 'C');
        $pdf->Cell(25, 5, "Codigo Haber", 'TB', 0, 'C');
        $pdf->Cell(65, 5, "Concepto Haber", 'TB', 0, 'C');
        $pdf->Ln();
        
        $cns = $this->configuracion_liquidacion_model->lista_detalle_configuracion($id);	
        
        
        $pdf->SetFont('Calibril', '', 10);

        $n=0;
        foreach ($cns as $det) {
        	$n++;

            $cta_db=$this->plan_cuentas_model->lista_un_plan_cuentas($det->cfa_debe);
			if(!empty($cta_db->pln_descripcion)){
				$des_db=$cta_db->pln_descripcion;	
				$cod_db=$cta_db->pln_codigo;	
			}else{
				$des_db='';
				$cod_db='';
			}

			$cta_hb=$this->plan_cuentas_model->lista_un_plan_cuentas($det->cfa_haber);
			if(!empty($cta_hb->pln_descripcion)){
				$des_hb=$cta_hb->pln_descripcion;	
				$cod_hb=$cta_hb->pln_codigo;	
			}else{
				$des_hb='';
				$cod_hb='';
			}

            $pdf->Cell(6, 5, $n, 0, 0, 'L');
            $pdf->Cell(25, 5, $cod_db, 0, 0, 'L');
            $pdf->Cell(65, 5, substr(utf8_decode($des_db),0,38), 0, 0, 'L');
            $pdf->Cell(25, 5, $cod_hb, 0, 0, 'L');
            $pdf->Cell(65, 5, substr(utf8_decode($des_hb),0,38), 0, 0, 'L');
            $pdf->Ln();
        }

	    $pdf->Output('configuracion_liquidacion.pdf' , 'I' );
	}    

}
