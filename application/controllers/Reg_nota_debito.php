<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_nota_debito extends CI_Controller {

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
		$this->load->model('empresa_model');
		$this->load->model('emisor_model');
		$this->load->model('reg_factura_model');
		$this->load->model('reg_nota_debito_model');
		$this->load->model('cliente_model');
		$this->load->model('vendedor_model');
		$this->load->model('producto_comercial_model');
		$this->load->model('forma_pago_model');
		$this->load->model('bancos_tarjetas_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('forma_pago_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->model('cheque_model');
		$this->load->model('configuracion_cuentas_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('asiento_model');
		$this->load->library('html2pdf');
		$this->load->library('Zend');
		$this->load->library('export_excel');
		$this->load->library("nusoap_lib");
		$this->load->library('email');
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
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$cns_notas=$this->reg_nota_debito_model->lista_nota_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_notas=$this->reg_nota_debito_model->lista_nota_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}

		$data=array(
					'permisos'=>$this->permisos,
					'notas'=>$cns_notas,
					'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('reg_nota_debito/lista',$data);
		$modulo=array('modulo'=>'reg_nota_debito');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_insertar){
			//valida cuentas asientos completos
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$valida_asiento=0;
			if($conf_as->con_valor==0){
				$cuentas=$this->configuracion_cuentas_model->lista_configuracion_cuenta_completa($rst_cja->emi_id);
				if(!empty($cuentas)){
					$valida_asiento=1;
				}
			}
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$mensaje='Para una mejor experiencia gire la pantalla de su celular';
			$data=array(
						'ctrl_inv'=>$this->configuracion_model->lista_una_configuracion('6'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'inven'=>$this->configuracion_model->lista_una_configuracion('3'),
						'cprec'=>$this->configuracion_model->lista_una_configuracion('20'),
						'cdesc'=>$this->configuracion_model->lista_una_configuracion('21'),
						'm_pag'=>$this->configuracion_model->lista_una_configuracion('22'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'mensaje'=> $mensaje,
						'nota'=> (object) array(
											'rnd_fecha_emision'=>date('Y-m-d'),
											'rnd_numero'=>'',
											'rnd_fec_registro'=>date('Y-m-d'),
											'rnd_fec_autorizacion'=>date('Y-m-d'),
											'rnd_fec_caducidad'=>date('Y-m-d'),
											'rnd_autorizacion'=>'',
											'rnd_num_comp_modifica'=>'',
											'rnd_fecha_emi_comp'=>'',
											'reg_id'=>'0',
					                        'cli_id'=>'',
					                        'rnd_identificacion'=>'',
					                        'rnd_nombre'=>'',
					                        'rnd_subtotal12'=>'0',
					                        'rnd_subtotal0'=>'0',
					                        'rnd_subtotal12'=>'0',
					                        'rnd_subtotal0'=>'0',
					                        'rnd_subtotal12'=>'0',
					                        'rnd_subtotal0'=>'0',
					                        'rnd_subtotal_ex_iva'=>'0',
					                        'rnd_subtotal_no_iva'=>'0',
					                        'rnd_subtotal'=>'0',
					                        'rnd_total_iva'=>'0',
					                        'rnd_total_valor'=>'0',
					                        'emp_id'=>$rst_cja->emp_id,
					                        'emi_id'=>$rst_cja->emi_id,
					                        'rnd_id'=>'',
										),
						'cns_det'=>'',
						'action'=>base_url().'reg_nota_debito/guardar/'.$opc_id,
						'valida_asiento'=>$valida_asiento,
						);
			
			$this->load->view('reg_nota_debito/form',$data);
			$modulo=array('modulo'=>'reg_nota_debito');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$conf_as=$this->configuracion_model->lista_una_configuracion('4');

		$rnd_numero = $this->input->post('rnd_numero');
		$rnd_fecha_emision = $this->input->post('rnd_fecha_emision');
		$rnd_fec_registro= $this->input->post('rnd_fec_registro');
		$rnd_fec_autorizacion= $this->input->post('rnd_fec_autorizacion');
		$rnd_fec_caducidad= $this->input->post('rnd_fec_caducidad');
		$rnd_autorizacion= $this->input->post('rnd_autorizacion');
		$fac_id= $this->input->post('fac_id');
		$rnd_num_comp_modifica= $this->input->post('rnd_num_comp_modifica');
		$rnd_fecha_emi_comp= $this->input->post('rnd_fecha_emi_comp');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$subtotal12 = $this->input->post('subtotal12');
		$subtotal0 = $this->input->post('subtotal0');
		$subtotalex = $this->input->post('subtotalex');
		$subtotalno = $this->input->post('subtotalno');
		$subtotal = $this->input->post('subtotal');
		$total_iva = $this->input->post('total_iva');
		$total_valor = $this->input->post('total_valor');
		$emp_id = $this->input->post('emp_id');
		$emi_id = $this->input->post('emi_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('rnd_fecha_emision','Fecha de Emision','required');
		$this->form_validation->set_rules('rnd_fec_autorizacion','Fecha de Autorizacion','required');
		$this->form_validation->set_rules('rnd_fec_caducidad','Fecha de Caducidad','required');
		$this->form_validation->set_rules('rnd_num_comp_modifica','Factura No','required');
		$this->form_validation->set_rules('rnd_fecha_emi_comp','Fecha Factura','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');
		if($this->form_validation->run()){
			
			

		    $data=array(	
		    				'emp_id'=>$emp_id,
		    				'emi_id'=>$emi_id,
							'reg_id'=>$fac_id,
							'cli_id'=>$cli_id,
							'rnd_denominacion_comprobante'=>'1',
							'rnd_fecha_emision'=>$rnd_fecha_emision,
							'rnd_fec_registro'=>$rnd_fec_registro,
							'rnd_fec_autorizacion'=>$rnd_fec_autorizacion,
							'rnd_fec_caducidad'=>$rnd_fec_caducidad,
							'rnd_numero'=>$rnd_numero, 
							'rnd_autorizacion'=>$rnd_autorizacion,
							'rnd_nombre'=>$nombre, 
							'rnd_identificacion'=>$identificacion, 
							'rnd_num_comp_modifica'=>$rnd_num_comp_modifica, 
							'rnd_fecha_emi_comp'=>$rnd_fecha_emi_comp, 
							'rnd_subtotal12'=>$subtotal12, 
							'rnd_subtotal0'=>$subtotal0, 
							'rnd_subtotal_ex_iva'=>$subtotalex, 
							'rnd_subtotal_no_iva'=>$subtotalno, 
							'rnd_total_iva'=>$total_iva, 
							'rnd_total_valor'=>$total_valor,
							'rnd_subtotal'=>$subtotal,
							'rnd_estado'=>'4'
		    );


		    $rnd_id=$this->reg_nota_debito_model->insert($data);
		    if(!empty($rnd_id)){
		    	$n=0;
		    	while($n<$count_det){
		    		$n++;
		    		if($this->input->post("descripcion$n")!='' && $this->input->post("cantidad$n")>0){
		    			$descripcion = $this->input->post("descripcion$n");
		    			$cantidad = $this->input->post("cantidad$n");
		    			$dt_det=array(	
		    							'rnd_id'=>$rnd_id,
	                                    'rdd_descripcion'=>$descripcion,
	                                    'rdd_precio_total'=>$cantidad,
		    						);
		    			$this->reg_nota_debito_model->insert_detalle($dt_det);
		    		}
		    	}
		    	
		    	//pagos_factura
		    	$dt_det=array(
		    							'reg_id'=>$fac_id,
                                        'pag_fecha_v'=>$rnd_fecha_emision,
                                        'pag_tipo'=>9,
                                        'pag_porcentage'=>'100',
                                        'pag_dias'=>1,
                                        'pag_valor'=>$total_valor,
                                        'chq_numero'=>$rnd_numero,
                                        'pag_id_chq'=>$rnd_id,
                                        'pag_estado'=>'1',
                                        'pag_nd'=>'1',
		    						);
		    			
		    	$pag_id=$this->reg_factura_model->insert_pagos($dt_det);

		    	
		    	//genera asientos
		    	
		        if($conf_as->con_valor==0){
		        	$this->asientos($rnd_id);
		        }

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO NOTA DE DEBITO',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rnd_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				// redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				redirect(base_url().'reg_nota_debito/show_frame/'. $rnd_id.'/'.$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'reg_nota_debito/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	

	}

	public function editar($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_actualizar){
			//valida cuentas asientos completos
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$valida_asiento=0;
			if($conf_as->con_valor==0){
				$cuentas=$this->configuracion_cuentas_model->lista_configuracion_cuenta_completa($rst_cja->emi_id);
				if(!empty($cuentas)){
					$valida_asiento=1;
				}
			}
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$mensaje='Para una mejor experiencia gire la pantalla de su celular';
			$data=array(
						'ctrl_inv'=>$this->configuracion_model->lista_una_configuracion('6'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'inven'=>$this->configuracion_model->lista_una_configuracion('3'),
						'cprec'=>$this->configuracion_model->lista_una_configuracion('20'),
						'cdesc'=>$this->configuracion_model->lista_una_configuracion('21'),
						'm_pag'=>$this->configuracion_model->lista_una_configuracion('22'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'mensaje'=> $mensaje,
						'nota'=> $this->reg_nota_debito_model->lista_una_nota($id),
						'cns_det'=>$this->reg_nota_debito_model->lista_detalle_nota($id),
						'action'=>base_url().'reg_nota_debito/actualizar/'.$opc_id,
						'valida_asiento'=>$valida_asiento,
						);
			
			$this->load->view('reg_nota_debito/form',$data);
			$modulo=array('modulo'=>'reg_nota_debito');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}


	public function actualizar($opc_id){
		$conf_as=$this->configuracion_model->lista_una_configuracion('4');

		$rnd_id = $this->input->post('rnd_id');
		$rnd_numero = $this->input->post('rnd_numero');
		$rnd_fecha_emision = $this->input->post('rnd_fecha_emision');
		$rnd_fec_registro= $this->input->post('rnd_fec_registro');
		$rnd_fec_autorizacion= $this->input->post('rnd_fec_autorizacion');
		$rnd_fec_caducidad= $this->input->post('rnd_fec_caducidad');
		$rnd_autorizacion= $this->input->post('rnd_autorizacion');
		$fac_id= $this->input->post('fac_id');
		$rnd_num_comp_modifica= $this->input->post('rnd_num_comp_modifica');
		$rnd_fecha_emi_comp= $this->input->post('rnd_fecha_emi_comp');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$subtotal12 = $this->input->post('subtotal12');
		$subtotal0 = $this->input->post('subtotal0');
		$subtotalex = $this->input->post('subtotalex');
		$subtotalno = $this->input->post('subtotalno');
		$subtotal = $this->input->post('subtotal');
		$total_iva = $this->input->post('total_iva');
		$total_valor = $this->input->post('total_valor');
		$emp_id = $this->input->post('emp_id');
		$emi_id = $this->input->post('emi_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('rnd_fecha_emision','Fecha de Emision','required');
		$this->form_validation->set_rules('rnd_fec_autorizacion','Fecha de Autorizacion','required');
		$this->form_validation->set_rules('rnd_fec_caducidad','Fecha de Caducidad','required');
		$this->form_validation->set_rules('rnd_num_comp_modifica','Factura No','required');
		$this->form_validation->set_rules('rnd_fecha_emi_comp','Fecha Factura','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');
		if($this->form_validation->run()){
			
			

		    $data=array(	
		    				'emp_id'=>$emp_id,
		    				'emi_id'=>$emi_id,
							'reg_id'=>$fac_id,
							'cli_id'=>$cli_id,
							'rnd_denominacion_comprobante'=>'1',
							'rnd_fecha_emision'=>$rnd_fecha_emision,
							'rnd_fec_registro'=>$rnd_fec_registro,
							'rnd_fec_autorizacion'=>$rnd_fec_autorizacion,
							'rnd_fec_caducidad'=>$rnd_fec_caducidad,
							'rnd_numero'=>$rnd_numero, 
							'rnd_autorizacion'=>$rnd_autorizacion,
							'rnd_nombre'=>$nombre, 
							'rnd_identificacion'=>$identificacion, 
							'rnd_num_comp_modifica'=>$rnd_num_comp_modifica, 
							'rnd_fecha_emi_comp'=>$rnd_fecha_emi_comp, 
							'rnd_subtotal12'=>$subtotal12, 
							'rnd_subtotal0'=>$subtotal0, 
							'rnd_subtotal_ex_iva'=>$subtotalex, 
							'rnd_subtotal_no_iva'=>$subtotalno, 
							'rnd_total_iva'=>$total_iva, 
							'rnd_total_valor'=>$total_valor,
							'rnd_subtotal'=>$subtotal,
							'rnd_estado'=>'4'
		    );


		    $this->reg_nota_debito_model->update($rnd_id,$data);
		    if(!empty($rnd_id)){
		    	
		    	$this->reg_nota_debito_model->delete_detalle($rnd_id);

		    	$n=0;
		    	while($n<$count_det){
		    		$n++;
		    		if($this->input->post("descripcion$n")!='' && $this->input->post("cantidad$n")>0){
		    			$descripcion = $this->input->post("descripcion$n");
		    			$cantidad = $this->input->post("cantidad$n");
		    			$dt_det=array(	
		    							'rnd_id'=>$rnd_id,
	                                    'rdd_descripcion'=>$descripcion,
	                                    'rdd_precio_total'=>$cantidad,
		    						);
		    			$this->reg_nota_debito_model->insert_detalle($dt_det);
		    		}
		    	}
		    	
		    	//pagos_factura
		    	$dt_det=array(
		    							'reg_id'=>$fac_id,
                                        'pag_fecha_v'=>$rnd_fecha_emision,
                                        'pag_tipo'=>9,
                                        'pag_porcentage'=>'100',
                                        'pag_dias'=>1,
                                        'pag_valor'=>$total_valor,
                                        'chq_numero'=>$rnd_numero,
                                        'pag_id_chq'=>$rnd_id,
                                        'pag_estado'=>'1',
                                        'pag_nd'=>'1',
		    						);
		    			
		    	$pag_id=$this->reg_nota_debito_model->update_pagos($rnd_id,$dt_det);

		    	
		    	//genera asientos
		    	
		        if($conf_as->con_valor==0){
		        	$this->asientos($rnd_id);
		        }

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO NOTA DE DEBITO',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rnd_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				// redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				redirect(base_url().'reg_nota_debito/show_frame/'. $rnd_id.'/'.$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'reg_nota_debito/editar/'.$id.'/'.$opc_id);
			}
		}else{
			$this->editar($id,$opc_id);
		}	

	}

	
	public function anular($id,$num,$opc_id){
		if($this->permisos->rop_eliminar){
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$cnf_as=$conf_as->con_valor;
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_ndb=$this->reg_nota_debito_model->lista_una_nota($id);
			$saldo=0;
			$rst_sal=$this->reg_nota_debito_model->lista_suma_pagos($rst_ndb->reg_id);

			if(!empty($rst_sal)){
				$total=$rst_sal->reg_total;
				$pagado=$rst_sal->pago;
				$saldo=$total-$pagado;
			}

			if($saldo!=0 && $saldo>=$rst_ndb->rnd_total_valor){
			///anula el pago si existe saldo y si el saldo es mayor al valor de la nota
				// anulacion pagos
				$up_pag=array('pag_estado'=>3);
				$this->reg_nota_debito_model->update_pagos($id,$up_pag);

				
			    $up_dtf=array('rnd_estado'=>3);
				if($this->reg_nota_debito_model->update($id,$up_dtf)){
					
					//asiento anulacion nota
					if($cnf_as==0){
						$this->asiento_anulacion($id,'7');
					}

					$data_aud=array(
									'usu_id'=>$this->session->userdata('s_idusuario'),
									'adt_date'=>date('Y-m-d'),
									'adt_hour'=>date('H:i'),
									'adt_modulo'=>'REGISTRO NOTA DE DEBITO',
									'adt_accion'=>'ANULAR',
									'adt_ip'=>$_SERVER['REMOTE_ADDR'],
									'adt_documento'=>$num,
									'usu_login'=>$this->session->userdata('s_usuario'),
									);
					$this->auditoria_model->insert($data_aud);
					$data=array(
									'estado'=>0,
									'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
								);

				}else{
					$data=array(
							'estado'=>1,
							'sms'=>'No se anulo el Registro de Nota de Debito',
							'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
					);
				}
			
			
			}else{
				$data=array(
							'estado'=>1,
							'sms'=>"No se puede anular el Registro de Nota de Debito \nPrimero anule pago en cuentas por pagar",
							'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
					);
			}
			echo json_encode($data);
		}else{
			redirect(base_url().'inicio');
		}	
	}

    	
	
	public function show_frame($id,$opc_id){
		if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
		}
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$etiqueta='reg_nota_debito.pdf';
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Registro Notas de Debito '.ucfirst(strtolower($rst_cja->emp_nombre)),
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"reg_nota_debito/show_pdf/$id/$opc_id/$etiqueta",
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
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
			$modulo=array('modulo'=>'reg_nota_debito');
			$this->load->view('layout/footer',$modulo);
		}
    	
    }

    
    public function show_pdf($id,$opc_id,$etiqueta){
    		$rst=$this->reg_nota_debito_model->lista_una_nota($id);
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

			///recupera detalle
			$cns_dt=$this->reg_nota_debito_model->lista_detalle_nota($id);
			$cns_det=array();
			foreach ($cns_dt as $rst_dt) {
	        
			$dt_det=(object) array(
						'rdd_descripcion'=>$rst_dt->rdd_descripcion,
						'rdd_precio_total'=>$rst_dt->rdd_precio_total,
						);	
				
				array_push($cns_det, $dt_det);
			}

			$data=array(
						'ambiente'=>$this->configuracion_model->lista_una_configuracion('5'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'nota'=>$this->reg_nota_debito_model->lista_una_nota($id),
						'asientos'=>$this->asiento_model->lista_un_asiento_modulo($id,'7'),
						'cns_det'=>$cns_det,
						);
			$this->html2pdf->filename('reg_nota_debito.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_reg_nota_debito', $data, true)));
    		$this->html2pdf->folder('./pdfs/');
            $this->html2pdf->filename($rst->rnd_numero.'.pdf');
            $this->html2pdf->create('save');
            
			$this->html2pdf->output(array("Attachment" => 0));	
		
    }

   

	public function traer_facturas($num,$emp){
		$rst=$this->reg_factura_model->lista_factura_numero($num,$emp);
		echo json_encode($rst);
	}

	public function load_factura($id){
		$rst=$this->reg_factura_model->lista_una_factura($id);
		$n=0;
		
			$data= array(
						'fac_id'=>$rst->reg_id,
						'cli_id'=>$rst->cli_id,
						'cli_raz_social'=>$rst->cli_raz_social,
						'cli_ced_ruc'=>$rst->cli_ced_ruc,
						'cli_calle_prin'=>$rst->cli_calle_prin,
						'cli_telefono'=>$rst->cli_telefono,
						'cli_email'=>$rst->cli_email,
						'fac_fecha_emision'=>$rst->reg_femision,
						'fac_numero'=>$rst->reg_num_documento,
						);	

		echo json_encode($data);
	} 

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='REGISTRO NOTA DE DEBITO '.ucfirst(strtolower($rst_cja->emp_nombre));
    	$file="reg_nota_debito".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }


    public function asientos($id){
        $conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;

        $rst=$this->reg_nota_debito_model->lista_una_nota($id);
        $cli=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('56',$rst->emi_id);
        $cex=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('2',$rst->emi_id);
        $vta=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('58',$rst->emi_id);
        $iva=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('59',$rst->emi_id);
        
        $rst_as=$this->asiento_model->lista_asientos_modulo($rst->rnd_id,'7');
        if(empty($rst_as[0]->con_asiento)){
        	$asiento =$this->asiento_model->siguiente_asiento();
    	}else{
    		///elimina asiento
    		$asiento=$rst_as[0]->con_asiento;
    		$this->asiento_model->delete($rst_as[0]->con_asiento);
    	}


        $dat0 = array();
        $dat1 = array();
        $dat2 = array();
        $dat3 = array();

        $sub=round($rst->rnd_subtotal, $dec);

        if($rst->cli_tipo_cliente==0){
        	$ccli=$cli;
        }else{
        	$ccli=$cex;
        }
        $dat0 = Array(
                    'con_asiento'=>$asiento,
                    'con_concepto'=>'REGISTRO NOTA DE DEBITO',
                    'con_documento'=>$rst->rnd_numero,
                    'con_fecha_emision'=>$rst->rnd_fecha_emision,
                    'con_concepto_debe'=>$vta->pln_id,
                    'con_concepto_haber'=>$ccli->pln_id,
                    'con_valor_debe'=>round($sub, $dec),
                    'con_valor_haber'=>round($rst->rnd_total_valor, $dec),
                    'mod_id'=>'7',
                    'doc_id'=>$rst->rnd_id,
                    'cli_id'=>$rst->cli_id,
                    'con_estado'=>'1',
                    'emp_id'=>$rst->emp_id,
                );

        if ($rst->rnd_subtotal12 != 0) {
            $dat1 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'REGISTRO NOTA DE DEBITO',
                        'con_documento'=>$rst->rnd_numero,
                        'con_fecha_emision'=>$rst->rnd_fecha_emision,
                        'con_concepto_debe'=>$iva->pln_id,
                        'con_concepto_haber'=>'0',
                        'con_valor_debe'=>round($rst->rnd_total_iva, $dec),
                        'con_valor_haber'=>'0.00',
                        'mod_id'=>'7',
                        'doc_id'=>$rst->rnd_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
        }

        

        $array = array($dat0, $dat1, $dat2, $dat3);
        $j = 0;
        while ($j <= count($array)) {
            if (!empty($array[$j])) {
                $this->asiento_model->insert($array[$j]);
            }
            $j++;
        }
    }

    public function asiento_anulacion($id,$mod){
    	$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
        
        $cns=$this->asiento_model->lista_asientos_modulo($id,$mod);
        $asiento = $asiento =$this->asiento_model->siguiente_asiento();

        foreach ($cns as $rst) {
            
            $data = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'ANULACION '.$rst->con_concepto,
                        'con_documento'=>$rst->con_documento,
                        'con_fecha_emision'=>date('Y-m-d'),
                        'con_concepto_debe'=>$rst->con_concepto_haber,
                        'con_concepto_haber'=>$rst->con_concepto_debe,
                        'con_valor_debe'=>round($rst->con_valor_haber, $dec),
                        'con_valor_haber'=>round($rst->con_valor_debe, $dec),
                        'mod_id'=>$rst->mod_id,
                        'doc_id'=>$rst->doc_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
                    );

            $this->asiento_model->insert($data);
                   
        }

    }

    public function doc_duplicado($id,$num){
		$rst=$this->reg_nota_debito_model->lista_doc_duplicado($id,$num);
		if(!empty($rst)){
			echo $rst->rnd_id;
		}else{
			echo "";
		}
	}
	
}
