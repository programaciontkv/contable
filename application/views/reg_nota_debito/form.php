<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<section class="content-header">
      <h1>
        Registro Nota de Debito <?php echo $titulo?>
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-12">
          <?php 
          $dec=$dec->con_valor;
          $dcc=$dcc->con_valor;
          $ctrl_inv=$ctrl_inv->con_valor;
          $inven=$inven->con_valor;
          $cprec=$cprec->con_valor;
          $cdesc=$cdesc->con_valor;
          
          if($inven==0){
            $hid_inv='';
            $col_obs='8';
          }else{
            $hid_inv='hidden';
            $col_obs='8';
          }
          if($this->session->flashdata('error')){
            ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <p><i class="icon fa fa-ban"></i> <?php echo $this->session->flashdata('error')?></p>
            </div>
            <?php
          }
          ?>
          <div class="box box-primary">
            <form id="frm_save" role="form" action="<?php echo $action?>" method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="box-body" >
                 <table class="table col-sm-12" border="0">
                    <tr>
                      <td class="col-sm-12">
                        <div class="box-body">
                        <div class="panel panel-default col-sm-12">
                        <div class="panel panel-heading"><label>Datos Generales</label></div>
                        <table class="table">
                          <tr>
                              
                             <td><label>Fecha Registro:</label></td>
                            <td>
                              <div class="form-group <?php if(form_error('rnd_fec_registro')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="rnd_fec_registro" id="rnd_fec_registro" value="<?php if(validation_errors()!=''){ echo set_value('rnd_fec_registro');}else{ echo  $nota->rnd_fec_registro;}?>">
                                  <?php echo form_error("rnd_fec_registro","<span class='help-block'>","</span>");?>
                            </td>
                            <td><label>Fecha Emision:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('rnd_fecha_emision')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="rnd_fecha_emision" id="rnd_fecha_emision" value="<?php if(validation_errors()!=''){ echo set_value('rnd_fecha_emision');}else{ echo  $nota->rnd_fecha_emision;}?>">
                                  <?php echo form_error("rnd_fecha_emision","<span class='help-block'>","</span>");?>
                                </div>
                                <input type="hidden" class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('emp_id');}else{ echo  $nota->emp_id;}?>">
                                <input type="hidden" class="form-control" name="emi_id" id="emi_id" value="<?php if(validation_errors()!=''){ echo set_value('emi_id');}else{ echo  $nota->emi_id;}?>">
                                
                                <input type="hidden" class="form-control" name="fac_id" id="fac_id" value="<?php if(validation_errors()!=''){ echo set_value('fac_id');}else{ echo  $nota->reg_id;}?>">
                                </div>
                             </td>
                            
                          </tr>
                          <tr>
                              
                             <td><label>Fecha Autorizacion:</label></td>
                            <td>
                              <div class="form-group <?php if(form_error('rnd_fec_autorizacion')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="rnd_fec_autorizacion" id="rnd_fec_autorizacion" value="<?php if(validation_errors()!=''){ echo set_value('rnd_fec_autorizacion');}else{ echo  $nota->rnd_fec_autorizacion;}?>">
                                  <?php echo form_error("rnd_fec_autorizacion","<span class='help-block'>","</span>");?>
                            </td>
                            <td><label>Fecha Caducidad:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('rnd_fec_caducidad')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="rnd_fec_caducidad" id="rnd_fec_caducidad" value="<?php if(validation_errors()!=''){ echo set_value('rnd_fec_caducidad');}else{ echo  $nota->rnd_fec_caducidad;}?>">
                                  <?php echo form_error("rnd_fec_caducidad","<span class='help-block'>","</span>");?>
                                </div>
                               
                             </td>
                            
                          </tr>
                          <tr>
                              <td><label>Nota Debito No:</label></td>
                              <td class="col-md-5">
                                <div class="form-group <?php if(form_error('rnd_numero')!=''){ echo 'has-error';}?> ">
                                  <input type="hidden" class="form-control documento" name="rnd_numero" id="rnd_numero" value="<?php if(validation_errors()!=''){ echo set_value('rnd_numero');}else{ echo $nota->rnd_numero;}?>"  maxlength="17" onchange ="num_factura(this,1)">
                                  <?php echo form_error("rnd_numero","<span class='help-block'>","</span>");?>

                            <?php
                            if ($nota->rnd_numero!='' ) {
                            $dn=explode("-",$nota->rnd_numero);
                            }else{
                            $dn[0]='';
                            $dn[1]='';
                            $dn[2]='';
                            }


                            ?>  
                             
                              <div class="col-xs-3">
                              <input type="text" class="form-control" id="rnd_numero0" size="3" maxlength="3" value="<?php echo $dn[0] ?>" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros(this, 0)"/>
                              </div>
                              <div class="col-xs-1" style="width: 0.5%;"> <p>-</p> </div> 
                              <div class="col-xs-3">
                              <input type="text"  class="form-control" id="rnd_numero1"  maxlength="3" value="<?php echo $dn[1] ?>" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros(this, 0)" />
                              </div>
                              <div class="col-xs-1" style="width: 1%;"> <p>-</p> </div> 
                              <div class="col-xs-4">
                              <input type="text"  class="form-control" id="rnd_numero2"  maxlength="9" value="<?php echo $dn[2] ?>" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros(this, 1)" />
                              </div>
                             
  </div>
                              </td>
                              <td><label>Autorizacion No:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('rnd_autorizacion')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control numerico" name="rnd_autorizacion" id="rnd_autorizacion" value="<?php if(validation_errors()!=''){ echo set_value('rnd_autorizacion');}else{ echo $nota->rnd_autorizacion;}?>" onchange="validar_autorizacion()">
                                  <?php echo form_error("rnd_autorizacion","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                          </tr>    
                          <tr>
                              <td><label>Factura No:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('rnd_num_comp_modifica')!=''){ echo 'has-error';}?> ">
                                  <input type="hidden" class="form-control documento" name="rnd_num_comp_modifica" id="rnd_num_comp_modifica" value="<?php if(validation_errors()!=''){ echo set_value('rnd_num_comp_modifica');}else{ echo $nota->rnd_num_comp_modifica;}?>"  maxlength="17">
                                  <?php echo form_error("rnd_num_comp_modifica","<span class='help-block'>","</span>");?>

                                 
                              <div class="col-xs-3">
                              <input type="text" class="form-control" id="rnd_num_comp_modifica0"  maxlength="3" value="<?php echo $dn[0] ?>" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros_2(this, 0)"/>
                              </div>
                              <div class="col-xs-1" style="width: 0.5%;"> <p>-</p> </div> 
                              <div class="col-xs-3">
                              <input type="text"  class="form-control" id="rnd_num_comp_modifica1"  maxlength="3" value="<?php echo $dn[1] ?>" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros_2(this, 0)" />
                              </div>
                              <div class="col-xs-1" style="width: 1%;"> <p>-</p> </div> 
                              <div class="col-xs-4">
                              <input type="text"  class="form-control" id="rnd_num_comp_modifica2"  maxlength="9" value="<?php echo $dn[2] ?>" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros_2(this, 1)" />
                              </div>
                              
                                </div>
                              </td>
                              <td><label>Fecha Factura:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('rnd_fecha_emi_comp')!=''){ echo 'has-error';}?> ">
                                  <input type="date" class="form-control" name="rnd_fecha_emi_comp" id="rnd_fecha_emi_comp" value="<?php if(validation_errors()!=''){ echo set_value('rnd_fecha_emi_comp');}else{ echo $nota->rnd_fecha_emi_comp;}?>" readonly>
                                  <?php echo form_error("rnd_fecha_emi_comp","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                          </tr>
                          <tr>    
                              <td><label>RUC/CI:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('identificacion')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="identificacion" id="identificacion" value="<?php if(validation_errors()!=''){ echo set_value('identificacion');}else{ echo $nota->rnd_identificacion;}?>" list="list_clientes" onchange="traer_cliente(this)" readonly>
                                  <?php echo form_error("identificacion","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                            <td><label>Nombre:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('nombre')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php if(validation_errors()!=''){ echo set_value('nombre');}else{ echo $nota->rnd_nombre;}?>" readonly>
                                    <?php echo form_error("nombre","<span class='help-block'>","</span>");?>
                                
                                </div>
                                <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $nota->cli_id;}?>" >
                              </td>
                          </tr>
                         
                           
                          </table>
                          </div>
                          </div>
                        </td>
                    </tr>
                    <tr>
                       <td class="col-sm-12" colspan="2">
                          <div class="box-body">
                          <div class="panel panel-default col-sm-8">
                          
                          <table class="table table-bordered table-striped" id="tbl_detalle">
                            <thead>
                              <tr>
                                <th>Item</th>
                                <th>Razon de la Modificacion</th>
                                <th>Valor Modificacion</th>
                                <th></th>
                              </tr>
                            </thead>

                            <tbody id="lista_encabezado">
                             <tr>
                                        <td colspan="2" class="td1">
                                            <input style="text-align:left " type="text" style="width:  150px;" class="form-control" id="descripcion" name="descripcion"  value="" lang="1" />
                                        </td>
                                        
                                        <td style="text-align:center; width:  100px;">
                                          <input type ="text" size="7" style="text-align:right; width:  100px;" id="cantidad" name="cantidad" value="0" lang="1" class="form-control decimal"/>
                                        </td>

                                        <td style="width:  100px; text-align: center" ><input  type="button" name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_detalle','0')" lang="1" value='+'/> </td>
                                        
                                    </tr>
                              
                                </tbody>        
                                <tbody id="lista">
                                  <?php
                                $n=0;
                                $cnt_detalle=0;
                                if(!empty($cns_det)){
                                  foreach ($cns_det as $det) {
                                    $n++;
                              ?>
                                <tr>
                                  <td id='item<?php echo $n?>' lang='<?php echo $n?>' align='center'><?php echo $n?></td>
                                        <td class="td1">
                                            <input style="text-align:left " type="text" style="width:  150px;" class="form-control" id="descripcion<?php echo $n?>" name="descripcion<?php echo $n?>"  value="<?php echo $det->rdd_descripcion?>" lang="<?php echo $n?>" />
                                        </td>
                                        
                                        <td style="text-align:center; width:  100px;">
                                          <input type ="text" size="7" style="text-align:right; width:  100px;" id="cantidad<?php echo $n?>" name="cantidad<?php echo $n?>" value="<?php echo $det->rdd_precio_total?>" lang="<?php echo $n?>" class="form-control decimal" onchange="calculo()"/>
                                        </td>

                                        <td onclick='elimina_fila_det(this)' align='center' >
                                          <span class='btn btn-danger fa fa-trash'></span>
                                        </td>
                                        
                                    </tr>
                              <?php
                                  $cnt_detalle++;      
                                  }
                                }   
                                    
                                  ?>
                                </tbody>
                            <tfoot>
                                <tr>

                                    <td colspan="2" align="right">Subtotal 12%:</td>
                                    <td>
                                        <input style="text-align:right; width:  100px;" type="text" class="form-control" id="subtotal12" name="subtotal12" value="<?php echo str_replace(',', '', number_format($nota->rnd_subtotal12, $dec)) ?>" readonly/>
                                    </td>
                                    <td><input type="radio" id="st1" name="st" onclick="calculo()" checked/></td></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal 0%:</td>
                                    <td>
                                        <input style="text-align:right; width:  100px;" type="text" class="form-control" id="subtotal0" name="subtotal0" value="<?php echo str_replace(',', '', number_format($nota->rnd_subtotal0, $dec)) ?>" readonly/>
                                    </td>
                                    <td><input type="radio" id="st2" name="st" onclick="calculo()"/></td></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal Excento de Iva:</td>
                                    <td><input style="text-align:right; width:  100px;" type="text" class="form-control" id="subtotalex" name="subtotalex" value="<?php echo str_replace(',', '', number_format($nota->rnd_subtotal_ex_iva, $dec)) ?>" readonly/>
                                    </td>
                                    <td><input type="radio" id="st3" name="st" onclick="calculo()"/></td></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal no objeto de Iva:</td>
                                    <td><input style="text-align:right; width:  100px;" type="text" class="form-control" id="subtotalno" name="subtotalno" value="<?php echo str_replace(',', '', number_format($nota->rnd_subtotal_no_iva, $dec)) ?>" readonly/>
                                    </td>
                                    <td><input type="radio" id="st4" name="st" onclick="calculo()"/></td></td>
                                </tr>


                                <tr>
                                    <td colspan="2" align="right">Subtotal sin Impuestos:</td>
                                    <td><input style="text-align:right; width:  100px;" type="text" class="form-control" id="subtotal" name="subtotal" value="<?php echo str_replace(',', '', number_format($nota->rnd_subtotal, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                               
                                <tr>
                                    <td colspan="2" align="right">Total IVA:</td>
                                    <td><input style="text-align:right; width:  100px;" type="text" class="form-control" id="total_iva" name="total_iva" value="<?php echo str_replace(',', '', number_format($nota->rnd_total_iva, $dec)) ?>" readonly />
                                    </td>
                                </tr> 
                                <tr>
                                    <td colspan="2" align="right">Total Valor:</td>
                                    <td><input style="text-align:right; width:  100px;;font-size:15px;color:red  " type="text" class="form-control" id="total_valor" name="total_valor" value="<?php echo str_replace(',', '', number_format($nota->rnd_total_valor, $dec)) ?>" readonly />
                                        
                                    </td>
                                </tr>
                              </tfoot>
                          </table>
                          </div>
                          </div>
                          </td>
                    </tr> 
                    
                    
                  </table>
              </div>
                                
                <input type="hidden" class="form-control" name="rnd_id" value="<?php echo $nota->rnd_id?>">
                <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $cnt_detalle?>">
                <input type="hidden" class="form-control" id="saldo" name="saldo" value="0">
              <div class="box-footer">
                <?php
                if($valida_asiento==0){ 
                ?>
                <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <?php
                }
                ?>
                <a href="<?php echo $cancelar;?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         </div>
      <!-- /.row -->
    </section>
   
    
    <!-- ////modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Facturas</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped">
              <thead>
                  <th>Seleccione</th>
                  <th>Fecha</th>
                  <th>Tipo</th>
                  <th>Numero</th>
                  <th>CI/RUC</th>
                  <th>Cliente</th>
              </thead>
              <tbody id="det_ventas"></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
      
    </div>
  </div>
  


    <style type="text/css">
      .panel{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
      }

      div{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
      }
      div .panel-heading{
        margin-bottom: 4px !important;
        margin-top: 4px !important;
        padding-bottom: 4px !important;
        padding-top: 4px !important;
      }
      
      .form-control{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
        height:28px !important;
      }

      td{
        margin-bottom: 1px !important;
        margin-top: 1px !important;
        padding-bottom: 1px !important;
        padding-top: 1px !important;
      }
    </style>
    

<script >

      var base_url='<?php echo base_url();?>';
      var inven='<?php echo $inven;?>';
      var ctr_inv='<?php echo $ctrl_inv;?>';
      var dec='<?php echo $dec;?>';
      var dcc='<?php echo $dcc;?>';
      var valida_asiento='<?php echo $valida_asiento;?>';
      window.onload = function () {
        if(valida_asiento==1){
          swal("", "No se puede crear Documento \nRevise Configuracion de cuentas", "info");          
        }
      }

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }
      
      function num_factura(obj) {
        nfac = obj.value;
        dt = nfac.split('-');
        if (nfac.length != 17 || dt[0].length != 3 || dt[1].length != 3 || dt[2].length != 9) {
          $(obj).val('');
          $('fac_id').val('0');
          $(obj).focus();
          $(obj).css({borderColor: "red"});
          swal("Error!", "No cumple con la estructura ejem: 000-000-000000000.!", "error");
          limpiar_nota();                    
        } else {
          traer_facturas(obj);
        }
      }

      function completar_ceros(obj, v) {
                o = obj.value;
                val = parseFloat(o);
                if (v == 0) {
                    if (val == 0) {
                        //alert("Numero incorrecto");
                        swal("", "Nùmero incorrecto", "info"); 
                        $(obj).val('');
                    } else if (val > 0 && val < 10) {
                        txt = '00';
                    } else if (val >= 10 && val < 100) {
                        txt = '0';
                    } else if (val >= 100 && val < 1000) {
                        txt = '';
                    }
                    $(obj).val(txt + val);
                } else {
                    if (val > 0 && val < 10) {
                        txt = '00000000';
                    } else if (val >= 10 && val < 100) {
                        txt = '0000000';
                    } else if (val >= 100 && val < 1000) {
                        txt = '000000';
                    } else if (val >= 1000 && val < 10000) {
                        txt = '00000';
                    } else if (val >= 10000 && val < 100000) {
                        txt = '0000';
                    } else if (val >= 100000 && val < 1000000) {
                        txt = '000';
                    } else if (val >= 1000000 && val < 10000000) {
                        txt = '00';
                    } else if (val >= 10000000 && val < 100000000) {
                        txt = '0';
                    } else if (val >= 100000000 && val < 1000000000) {
                        txt = '';
                    }
                    $(obj).val(txt + val);

                    if (val == 0 || o.length == 0) {
                        //alert("Numero incorrecto");
                        swal("", "Nùmero incorrecto", "info"); 
                        $(obj).val('');
                        return false;
                    }else{
                      num_doc = $('#rnd_numero0').val()+'-'+$('#rnd_numero1').val()+'-'+$('#rnd_numero2').val();
                      document.getElementById('rnd_numero').value=num_doc;
                      num_factura(num_doc,1);
                      //$('#rnd_numero').val(num_doc);
                      
                   

                    }

                }
                doc_duplicado()
            }

          function doc_duplicado(){
              num_doc = $('#rnd_numero').val();
              if (num_doc.length = 17 && cli_id.value.length > 0) {
                $.ajax({
                      beforeSend: function () {
                      },
                      url: base_url+"reg_nota_debito/doc_duplicado/"+cli_id.value+"/"+num_doc,
                      type: 'JSON',
                      dataType: 'JSON',
                      success: function (dt) {
                          if(dt!=""){
                            swal('','EL numero de Documento y el RUC/CI del Proveedor \n Ya existen en el Registro de Nota de Debito','info');   
                            $('#rnd_numero').val('');
                            $('#rnd_numero0').val('');
                            $('#rnd_numero1').val('');
                            $('#rnd_numero2').val('');
                          }
                          calculo(); 
                      }
                    });
              }          
            }
              
          function completar_ceros_2(obj, v) {
                o = obj.value;
                val = parseFloat(o);
                if (v == 0) {
                    if (val == 0) {
                        //alert("Numero incorrecto");
                        swal("", "Nùmero incorrecto", "info"); 
                        $(obj).val('');
                    } else if (val > 0 && val < 10) {
                        txt = '00';
                    } else if (val >= 10 && val < 100) {
                        txt = '0';
                    } else if (val >= 100 && val < 1000) {
                        txt = '';
                    }
                    $(obj).val(txt + val);
                } else {
                    if (val > 0 && val < 10) {
                        txt = '00000000';
                    } else if (val >= 10 && val < 100) {
                        txt = '0000000';
                    } else if (val >= 100 && val < 1000) {
                        txt = '000000';
                    } else if (val >= 1000 && val < 10000) {
                        txt = '00000';
                    } else if (val >= 10000 && val < 100000) {
                        txt = '0000';
                    } else if (val >= 100000 && val < 1000000) {
                        txt = '000';
                    } else if (val >= 1000000 && val < 10000000) {
                        txt = '00';
                    } else if (val >= 10000000 && val < 100000000) {
                        txt = '0';
                    } else if (val >= 100000000 && val < 1000000000) {
                        txt = '';
                    }
                    $(obj).val(txt + val);

                    if (val == 0 || o.length == 0) {
                        //alert("Numero incorrecto");
                        swal("", "Nùmero incorrecto", "info"); 
                        $(obj).val('');
                        return false;
                    }else{
                      
                      num_doc2 = $('#rnd_num_comp_modifica0').val()+'-'+$('#rnd_num_comp_modifica1').val()+'-'+$('#rnd_num_comp_modifica2').val();
                      $('#rnd_num_comp_modifica').val(num_doc2);
                      
                       num_factura_2(num_doc2,0);
                    }

                }
                doc_duplicado()
            }

      function num_factura(obj,op) {

                nfac = obj;
                dt = nfac.split('-');
               
                if (nfac.length != 17 || dt[0].length != 3 || dt[1].length != 3 || dt[2].length != 9) {
                    $(obj).val('');
                    $('#reg_id').val('0');
                    $('#rnd_numero').val('');
                    $('#rnd_numero0').val('');
                    $('#rnd_numero1').val('');
                    $('#rnd_numero2').val('');
                    $(obj).focus();
                    $(obj).css({borderColor: "red"});
                    swal('','No cumple con la estructura ejem: 000-000-000000000','info');
                    limpiar_nota();                    
                } else {
                  if(op==0){
                    traer_facturas(obj);
                  }else{
                    doc_duplicado();
                  }
                }
            }
            function num_factura_2(obj,op) {

                nfac = obj;
                dt = nfac.split('-');
                
                if (nfac.length != 17 || dt[0].length != 3 || dt[1].length != 3 || dt[2].length != 9) {
                    $(obj).val('');
                    $('#reg_id').val('0');
                    $('#rnd_num_comp_modifica').val('');
                    $('#rnd_num_comp_modifica0').val('');
                    $('#rnd_num_comp_modifica1').val('');
                    $('#rnd_num_comp_modifica2').val('');
                    
                    $(obj).focus();
                    $(obj).css({borderColor: "red"});
                    swal('','No cumple con la estructura ejem: 000-000-000000000','info');
                    limpiar_nota();                    
                } else {
                  if(op==0){
                    traer_facturas(obj);
                  }else{
                    doc_duplicado();
                  }
                }
            }
                  
      function traer_facturas(obj) {
              $.ajax({
                  beforeSend: function () {
                      if ($('#rnd_num_comp_modifica').val().length == 0) {
                             swal('','Ingrese una factura','info');
                            return false;
                      }
                    },
                  url: base_url+"reg_nota_debito/traer_facturas/"+obj+"/"+emp_id.value,
                  type: 'JSON',
                  dataType: 'JSON',
                  success: function (dt) { 
                    i=dt.length;
                    if(i>0){
                        n=0;
                        var tr="";
                        while(n<i){
                            tr+="<tr>"+
                                "<td><input type='checkbox' onclick='load_factura("+dt[n]['reg_id']+")'></td>"+
                                "<td>"+dt[n]['reg_femision']+"</td>"+
                                "<td>"+dt[n]['tdc_descripcion']+"</td>"+
                                "<td>"+dt[n]['reg_num_documento']+"</td>"+
                                "<td>"+dt[n]['cli_ced_ruc']+"</td>"+
                                "<td>"+dt[n]['cli_raz_social']+"</td>"+
                                "</tr>";
                                n++;
                        }
                        $('#det_ventas').html(tr);
                        $("#myModal").modal();
                    }else{
                         swal('','Numero no existe en Registro de Facturas','info');
                        limpiar_nota();
                    }
                  }
                })

            }        

        function load_factura(vl) {
              $.ajax({
                  beforeSend: function () {
                      
                    },
                    url: base_url+"reg_nota_debito/load_factura/"+vl,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                            if (dt.length != '0') {

                                $('#fac_id').val(dt.fac_id);
                                $('#rnd_fecha_emi_comp').val(dt.fac_fecha_emision);
                                $('#identificacion').val(dt.cli_ced_ruc);
                                $('#nombre').val(dt.cli_raz_social);
                                $('#cli_id').val(dt.cli_id);
                                $('#identificacion').attr('readonly', true);
                                $('#nombre').attr('readonly', true);
                                $("#myModal").modal('hide');
                                doc_duplicado();
                                calculo();
                            } else {
                                limpiar_nota();
                            }
                    }
                })
        }

        function validar(table, opc){
              var tr1 = $(table).find("tbody tr:last");
              var a1 = tr1.find("input").attr("lang");
              
                if($('#cantidad').val().length!=0 &&  parseFloat($('#cantidad').val())>0  && $('#descripcion').val().length!=0){
                  clona_detalle(table);
                }else{
                  if($('#cantidad').val().length!=0 ||  parseFloat($('#cantidad').val())==0){
                    
                    swal("","La cantidad debe ser mayor a 0.!",'info');
                    $('#cantidad').css({borderColor: "red"});
                    $('#cantidad').focus();
                  }
                }
        }
            
        function clona_detalle(table,opc) {
                d = 0;
                n = 0;
                ap = '"';
                var tr = $('#lista').find("tr:last");
                var a = tr.find("input").attr("lang");
                if(a==null){
                    j=0;
                }else{
                    j=parseInt(a);
                }
               
                    i = j + 1;
                    var fila = "<tr>"+
                                        "<td id='item"+i+"' lang='"+i+"' align='center'>"+
                                          i+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control' size='10' id='descripcion"+i+"' name='descripcion"+i+"' lang='"+i+"' value='"+descripcion.value +"' readonly/>"+"</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' style='text-align:right' id='cantidad"+i+"' name='cantidad"+i+"' onchange='calculo()' value='"+cantidad.value+"' lang='"+i+"' class='form-control decimal' onkeyup='validar_decimal(this)' />"+
                                        "</td>"+
                                        "<td onclick='elimina_fila_det(this)' align='center' >"+"<span class='btn btn-danger fa fa-trash'>"+"</span>"+"</td>"+
                                    "</tr>";
                         
                $('#lista').append(fila);
                $('#count_detalle').val(i);
                descripcion.value = '';
                cantidad.value = '';
                $('#cantidad').css({borderColor: ""});
                $('#descripcion').focus();
                calculo();
                
        } 

        function elimina_fila_det(obj) {
            var parent = $(obj).parents();
            $(parent[0]).remove();
            calculo();
        }
 
        function round(value, decimals) {
            return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
        }

        function calculo() {
                var tr = $('#lista').find("tr:last");
                var a = tr.find("input").attr("lang");
                i = parseInt(a);

                n = 0;
                var t12 = 0;
                var t0 = 0;
                var tex = 0;
                var tno = 0;
                var tdsc = 0;
                var tiva = 0;
                var gtot = 0;
                var tice = 0;
                var tib = 0;
                var sub = 0;
                var prop=0;

                if($('#st1').prop('checked')==true){
                  ob='12';
                }else if($('#st2').prop('checked')==true){
                  ob='0';
                }else if($('#st3').prop('checked')==true){
                  ob='EX';
                }else if($('#st4').prop('checked')==true){
                  ob='NO';
                }

                while (n < i) {
                    n++;
                    if ($('#item' + n).val() == null) {
                        vt = 0;
                    } else {
                        vt = $('#cantidad' + n).val().replace(',', '');
                        if(vt==''){
                          vt=0;
                        }
                        
                    }


                    if (ob == '12') {
                        t12 = (round(t12,dec) * 1 + round(vt,dec) * 1);
                        tiva = ((round(tice,dec) + round(t12,dec)) * 12 / 100);
                    }
                    if (ob == '0') {
                        t0 = (round(t0,dec) * 1 + round(vt,dec) * 1);
                    }
                    if (ob == 'EX') {
                        tex = (round(tex,dec) * 1 + round(vt,dec) * 1);
                    }
                    if (ob == 'NO') {
                        tno = (round(tno,dec) * 1 + round(vt,dec) * 1);
                    }

                }

                sub = round(t12,dec) + round(t0,dec) + round(tex,dec) + round(tno,dec);
                sub1 = round(t0,dec) + round(tex,dec) + round(tno,dec);
                gtot = round(sub,dec) * 1 + round(tiva,dec) * 1;
                 
                $('#subtotal12').val(t12.toFixed(dec));
                $('#subtotal0').val(t0.toFixed(dec));
                $('#subtotalex').val(tex.toFixed(dec));
                $('#subtotalno').val(tno.toFixed(dec));
                $('#subtotal').val(sub.toFixed(dec));
                $('#total_iva').val(tiva.toFixed(dec));
                $('#total_valor').val(gtot.toFixed(dec));
            } 
      function save() {
                        if (rnd_num_comp_modifica.value.length == 0) {
                            $("#rnd_num_comp_modifica").css({borderColor: "red"});
                            $("#rnd_num_comp_modifica").focus();
                            return false;
                        } else if (rnd_fecha_emision.value.length == 0) {
                            $("#rnd_fecha_emision").css({borderColor: "red"});
                            $("#rnd_fecha_emision").focus();
                            return false;
                        } else if (rnd_fec_autorizacion.value.length == 0) {
                            $("#rnd_fec_autorizacion").css({borderColor: "red"});
                            $("#rnd_fec_autorizacion").focus();
                            return false;
                        } else if (rnd_fec_caducidad.value.length == 0) {
                            $("#rnd_fec_caducidad").css({borderColor: "red"});
                            $("#rnd_fec_caducidad").focus();
                            return false;
                        } else if (rnd_num_comp_modifica.value.length == 0) {
                            $("#rnd_num_comp_modifica").css({borderColor: "red"});
                            $("#rnd_num_comp_modifica").focus();
                            return false;
                        } else if (rnd_fecha_emi_comp.value.length == 0) {
                            $("#rnd_fecha_emi_comp").css({borderColor: "red"});
                            $("#rnd_fecha_emi_comp").focus();
                            return false;
                        } else if (rnd_autorizacion.value.length == 0) {
                            $("#rnd_autorizacion").css({borderColor: "red"});
                            $("#rnd_autorizacion").focus();
                            return false;
                        } 

                        var tr = $('#lista').find("tr:last");
                        a = tr.find("input").attr("lang");
                        i = parseInt(a);
                        n = 0;
                        j = 0;
                        k = 0;
                        if(a==null){
                          swal("Error!", "Ingrese Detalle.!", "error");
                          return false;
                        }
                        if (i != 0) {
                            while (n < i) {
                                n++;

                                if ($('#descripcion' + n).val() != null) {
                                    k++;
                                    if ($('#descripcion' + n).val().length == 0) {
                                        $('#descripcion' + n).css({borderColor: "red"});
                                        $('#descripcion' + n).focus();
                                        return false;
                                    } else if ($('#cantidad' + n).val().length == 0) {
                                        $('#cantidad' + n).css({borderColor: "red"});
                                        $('#cantidad' + n).focus();
                                        return false;
                                    } 

                                }
                            }
                        }

                        if(k==0){
                           swal("Error!", "No se puede Guardar Nota de debito con cantidades en 0.!", "error");
                          return false;
                        }
                        
                        if ($('#vnd_id').val() == 0 || $('#vnd_id').val() == '') {
                            $('#vnd_id').css({borderColor: "red"});
                            $('#vnd_id').focus();
                            return false;
                        }

                       
                        
                     $('#frm_save').submit();   
               }   

      
</script>