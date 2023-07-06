<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<section class="content-header">
      <h1>
        Asiento de Liquidacion
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-12">
          <?php 
          $dec=$dec->con_valor;
          
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
                        <div class="panel panel-default col-sm-8">
                        <table class="table">
                          <tr>
                            <td><label>Fecha:</label></td>
                            <td>
                              <div class="form-group <?php if(form_error('con_fecha_emision')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="con_fecha_emision" id="con_fecha_emision" value="<?php if(validation_errors()!=''){ echo set_value('con_fecha_emision');}else{ echo $configuracion->con_fecha_emision;}?>" >
                                <?php echo form_error("con_fecha_emision","<span class='help-block'>","</span>");?>
                                </div>
                            </td>
                            
                          </tr>
                          <tr>
                            <td hidden><label>Cliente:</label></td>
                            <td hidden>
                              <div class="form-group <?php if(form_error('cli_nombre')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="cli_nombre" id="cli_nombre" value="<?php if(validation_errors()!=''){ echo set_value('cli_nombre');}else{ echo $configuracion->cli_raz_social;}?>" list="list_clientes" onchange="traer_cliente()" >
                                <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $configuracion->cli_id;}?>" >
                                <?php echo form_error("cli_nombre","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                            <td><label>Tipo:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('con_tipo')!=''){ echo 'has-error';}?> ">
                                <?php
                                    if(validation_errors()==''){
                                          $tip=$configuracion->con_pago_nombre;
                                        }else{
                                          $tip=set_value('con_tipo');
                                    }
                                 ?>  
                                <select class="form-control" name="con_tipo" id="con_tipo" onchange="traer_tipo()">
                                  <option value="0">SELECCIONE</option>
                                  <?php 
                                    if(!empty($cns_tipos)){
                                      foreach ($cns_tipos as $tipo) {
                                  ?>
                                      <option value="<?php echo $tipo->cfa_codigo?>"><?php echo $tipo->cfa_descripcion?></option>
                                  <?php
                                      }
                                    }
                                  ?>
                                </select>
                                <script type="text/javascript">
                                    var tip='<?php echo $tip?>';
                                    con_tipo.value=tip;
                                </script>
                                <?php echo form_error("con_tipo","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                            <td><label>Concepto:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('con_concepto')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="con_concepto" id="con_concepto" value="<?php if(validation_errors()!=''){ echo set_value('con_concepto');}else{ echo $configuracion->con_concepto;}?>" >
                                <?php echo form_error("con_concepto","<span class='help-block'>","</span>");?>
                                </div>
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
                          <div class="panel panel-default col-sm-12">
                          
                          <table class="table table-bordered table-striped" id="tbl_detalle">
                            <thead>
                              <tr>
                                <th>Item</th>
                                <th>Documento<input type="checkbox" id="todos" onclick="copiar()"></th>
                                <th>Cuenta Debe</th>
                                <th>Descripcion Debe</th>
                                <th>Cuenta Haber</th>
                                <th>Descripcion Haber</th>
                                <th>Valor Debe</th>
                                <th>Valor Haber</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>

                                 
                                <tbody id="lista">
                                  <?php
                                  if(!empty($cns_det)){
                                  $cnt_detalle=0;
                                  $n=0;
                                  $t_debe=0;
                                  $t_haber=0;
                                    foreach($cns_det as $rst_det) {
                                        $n++;
                                        if($rst_det->con_debe==''){
                                          $con_debe=0;
                                        }else{
                                          $con_debe=$rst_det->con_debe;
                                        }

                                        if($rst_det->con_haber==''){
                                          $con_haber=0;
                                        }else{
                                          $con_haber=$rst_det->con_haber;
                                        }

                                        $dis1="";
                                        if($con_debe==0){
                                          $dis1='readonly';
                                        }

                                        $dis2="";
                                        if($con_haber==0){
                                          $dis2='readonly';
                                        }
                                        ?>
                                      <tr>
                                        <td id="item<?php echo $n ?>" name="item<?php echo $n ?>" lang="<?php echo $n ?>" align="center" class="itm"><?php echo $n ?></td>
                                       <td>
                                            <input style="text-align:left " type ="text" class="refer form-control"  id="documento<?php echo $n ?>" name="documento<?php echo $n ?>"   value="<?php echo $rst_det->con_documento ?>" lang="<?php echo $n ?>" size="45"/>
                                        </td>
                                        <td>
                                            <input style="text-align:left " type ="text" class="refer form-control"  id="concepto_debe<?php echo $n ?>" name="concepto_debe<?php echo $n ?>"   value="<?php echo $rst_det->concepto_debe ?>" lang="<?php echo $n ?>" size="45" list="list_cuentas" onchange='traer_cuenta(this,0,1)' readonly/>
                                        </td>
                                        <td>
                                          <input type="hidden" id="con_debe<?php echo $n ?>" name="con_debe<?php echo $n ?>"  value="<?php echo $con_debe?>" lang="<?php echo $n ?>" class="form-control" size="30" readonly/>
                                          <input type="text" id="descripcion_debe<?php echo $n ?>" name="descripcion_debe<?php echo $n ?>"  value="<?php echo $rst_det->descripcion_debe?>" lang="<?php echo $n ?>" class="form-control" size="30" readonly/>
                                        </td>
                                        
                                        <td>
                                            <input style="text-align:left " type ="text" class="refer form-control"  id="concepto_haber<?php echo $n ?>" name="concepto_haber<?php echo $n ?>" value="<?php echo $rst_det->concepto_haber ?>" lang="<?php echo $n ?>" size="45" list="list_cuentas" onchange='traer_cuenta(this,1,1)' readonly/>
                                        </td>
                                        <td>
                                          <input type="hidden" id="con_haber<?php echo $n ?>" name="con_haber<?php echo $n ?>"  value="<?php echo $con_haber?>" lang="<?php echo $n ?>" class="form-control" size="30" readonly/>
                                          <input type="text" id="descripcion_haber<?php echo $n ?>" name="descripcion_haber<?php echo $n ?>" value="<?php echo $rst_det->descripcion_haber?>" lang="<?php echo $n ?>" class="form-control" size="30" readonly/>
                                        </td>
                                        <td>
                                          <input type="text" id="valor_debe<?php echo $n ?>" name="valor_debe<?php echo $n ?>" value="<?php echo str_replace(',','',number_format($rst_det->valor_debe,$dec))?>" lang="<?php echo $n ?>" class="form-control" style="text-align:right" size="10" onkeyup="validar_decimal(this)" onchange="calculo()" <?php echo $dis1?>/>
                                        </td>
                                        <td>
                                          <input type="text" id="valor_haber<?php echo $n ?>" name="valor_haber<?php echo $n ?>" value="<?php echo str_replace(',','',number_format($rst_det->valor_haber,$dec))?>" lang="<?php echo $n ?>" class="form-control" style="text-align:right" size="10" onkeyup="validar_decimal(this)" onchange="calculo()" <?php echo $dis2?>/>
                                        </td>
                                           
                                        <td onclick="elimina_fila_det(this)" align="center" ><span class="btn btn-danger fa fa-trash"></span></td>
                                      </tr>
                                        <?php
                                        $cnt_detalle++;
                                        $t_debe+=round($rst_det->valor_debe,$dec);
                                        $t_haber+=round($rst_det->valor_haber,$dec);
                                    }
                                  }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th style="text-align:right">Total:</th>
                                    <th colspan="5"></th>
                                    
                                    <th><input style="text-align:right;font-size:15px;color:red" size="10" type="text" class="form-control" id="total_debe" name="total_debe" value="<?php echo str_replace(',', '', number_format($t_debe, $dec)) ?>" readonly />
                                        
                                    </th>
                                    <th><input style="text-align:right;font-size:15px;color:red" size="10" type="text" class="form-control" id="total_haber" name="total_haber" value="<?php echo str_replace(',', '', number_format($t_haber, $dec)) ?>" readonly />
                                        
                                    </th>
                                    <th></th>
                                </tr>
                              </tfoot>
                          </table>
                          </div>
                          </div>
                      </td>
                    </tr> 
                    
                    
                </table>
              </div>
                                
              <input type="hidden" class="form-control" name="con_asiento" value="<?php echo $configuracion->con_asiento?>">
              <input type="hidden" class="form-control" name="emp_id" value="<?php echo $configuracion->emp_id?>">
              <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $cnt_detalle?>">
              <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <a href="<?php echo base_url().'liquidacion/';echo $opc_id ?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
        </div>
      </div>
      <!-- /.row -->
    </section>
    
    <datalist id="list_clientes">
      <?php 
        if(!empty($cns_clientes)){
          foreach ($cns_clientes as $cliente) {
      ?>
        <option value="<?php echo $cliente->cli_id?>"><?php echo $cliente->cli_ced_ruc .' '.$cliente->cli_raz_social?></option>
      <?php 
          }
        }
      ?>
  
    </datalist>

    <datalist id="list_cuentas">
      <?php 
        if(!empty($cuentas)){
          foreach ($cuentas as $cuenta) {
      ?>
        <option value="<?php echo $cuenta->pln_codigo?>"><?php echo $cuenta->pln_codigo .' '.$cuenta->pln_descripcion?></option>
      <?php 
          }
        }
      ?>
  
    </datalist>

  


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
      var dec='<?php echo $dec;?>';

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }  
      function copiar() {
                if ($('#todos').prop('checked') == true) {
                    var tr = $('#lista').find("tr:last");
                    var a = tr.find("input").attr("lang");
                    var i = a;
                    n = 0;
                    doc = $('#documento1').val();
                    while (n < i) {
                        n++;
                        $('#documento' + n).val(doc);
                    }
                }
            }
            
            function elimina_fila_det(obj) {
              if($('.itm').length>1){
                  var parent = $(obj).parents();
                  $(parent[0]).remove();
                  calculo();
              }else{
                swal('','No se pueden eliminar todas las filas','info');
              }
            }
 
            function traer_cliente(){
              $.ajax({
                    beforeSend: function () {
                      if ($('#cli_nombre').val().length == 0) {
                            swal("", "Ingrese dato", "info"); 
                            $('#cli_nombre').focus();
                            $('#cli_id').val('0');
                            $('#cli_nombre').val('');
                            return false;
                      }
                    },
                    url: base_url+"liquidacion/traer_cliente/"+cli_nombre.value,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                        if(dt!=""){
                          $('#cli_id').val(dt.cli_id);
                          $('#cli_nombre').val(dt.cli_raz_social);
                        }else{
                          swal("", "Cliente no existe", "info"); 
                            $('#cli_nombre').focus();
                            $('#cli_id').val('0');
                            $('#cli_nombre').val('');
                        } 
                        
                    },
                    error : function(xhr, status) {
                         swal("", "Cliente no existe", "info"); 
                          $('#cli_nombre').focus();
                            $('#cli_id').val('0');
                            $('#cli_nombre').val('');
                    }
                    });    
            }


            function traer_cuenta(obj, opc,tipo) {
              var uri = base_url+'liquidacion/traer_cuenta/'+ $(obj).val();
              j=obj.lang;
              $.ajax({
                  url: uri, //this is your uri
                  type: 'GET', //this is your method
                  dataType: 'json',
                  success: function (response) {
                    if(tipo==0){
                      if(opc==0){
                        $("#con_debe").val(response['pln_id']);
                        $("#descripcion_debe").val(response['pln_descripcion']);
                      }else{
                        $("#con_haber").val(response['pln_id']);
                        $("#descripcion_haber").val(response['pln_descripcion']);
                      }
                    }else{
                      if(opc==0){
                        $("#con_debe"+j).val(response['pln_id']);
                        $("#descripcion_debe"+j).val(response['pln_descripcion']);
                      }else{
                        $("#con_haber"+j).val(response['pln_id']);
                        $("#descripcion_haber"+j).val(response['pln_descripcion']);
                      }
                    }  
                  },
                  error : function(xhr, status) {
                      swal("", "No existe Cuenta", "info"); 
                      if(tipo==0){
                        if(opc==0){
                          $("#con_debe").val('0');
                          $("#concepto_debe").val('');
                          $("#descripcion_debe").val('');
                        }else{
                          $("#con_haber").val('0');
                          $("#concepto_haber").val('');
                          $("#descripcion_haber").val('');
                        }
                      }else{
                        if(opc==0){
                          $("#con_debe"+j).val('0');
                          $("#concepto_debe"+j).val('');
                          $("#descripcion_debe"+j).val('');
                        }else{
                          $("#con_haber"+j).val('0');
                          $("#concepto_haber"+j).val('');
                          $("#descripcion_haber"+j).val('');
                        }
                      }  
                  }
              });
          } 

          function traer_tipo(){
              $.ajax({
                    beforeSend: function () {
                      if ($('#con_tipo').val() == '0') {
                            swal("", "Seleccione un tipo", "info"); 
                            $('#con_tipo').focus();
                            limpiar();
                            return false;
                      }
                    },
                    url: base_url+"liquidacion/traer_tipo/"+con_tipo.value,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                        if(dt!=""){
                          $('#lista').html(dt.lista);
                          $('#count_detalle').val(dt.count);
                          $('#total_debe').val('0');
                          $('#total_haber').val('0');
                        }else{
                          swal("", "Tipo no existe", "info"); 
                            $('#con_tipo').focus();
                            $('#con_tipo').val('0');
                            $('#count_detalle').val('0');
                            limpiar();
                        } 
                        
                    },
                    error : function(xhr, status) {
                         swal("", "Tipo no existe", "info"); 
                            $('#con_tipo').focus();
                            $('#con_tipo').val('0');
                            limpiar();
                    }
                    });    
            }

            function limpiar(){

              $('#lista').html('');
              $('#count_detalle').val('0');
              $('#total_debe').val('0');
              $('#total_haber').val('0');
            }

            function round(value, decimals) {
                  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
            }


            function calculo(obj) {
                var tr = $('#lista').find("tr:last");
                var a = tr.find("input").attr("lang");
                i = parseInt(a);
                n = 0;
                var tot = 0;
                tdebe = 0;
                thaber = 0;
                while (n < i) {
                    n++;
                    if ($('#item' + n).val() != null) {
                      tdebe+= round($('#valor_debe'+n).val().replace(',', ''),dec);
                      thaber+= round($('#valor_haber'+n).val().replace(',', ''),dec);
                    }
                }
                
                $('#total_debe').val(parseFloat(tdebe).toFixed(dec));
                $('#total_haber').val(parseFloat(thaber).toFixed(dec));
            }     


            function save() {
                
                        if (con_fecha_emision.value.length == 0) {
                            $("#con_fecha_emision").css({borderColor: "red"});
                            $("#con_fecha_emision").focus();
                            return false;
                        } else if (con_tipo.value == '0') {
                            $("#con_tipo").css({borderColor: "red"});
                            $("#con_tipo").focus();
                            return false;
                        }else if (cli_id.value == '0' || cli_nombre.value.length==0) {
                            $("#cli_nombre").css({borderColor: "red"});
                            $("#cli_nombre").focus();
                            return false;
                        }else if (con_concepto.value.length == 0) {
                            $("#con_concepto").css({borderColor: "red"});
                            $("#con_concepto").focus();
                            return false;
                        } 
                        var tr = $('#lista').find("tr:last");
                        a = tr.find("input").attr("lang");
                        i = parseInt(a);
                        n = 0;
                        j = 0;
                        k = 0;
                        if(a==null){
                          swal("", "Ingrese Detalle", "info"); 
                          return false;
                        }
                        if (i != 0) {
                            while (n < i) {
                                n++;
                                if ($('#documento' + n).val() != null) {
                                  k++;
                                    if ($('#documento' + n).val().length == 0) {
                                        $('#documento' + n).css({borderColor: "red"});
                                        $('#documento' + n).focus();
                                        return false;
                                    } else if ($('#concepto_debe' + n).val().length == 0 && $('#concepto_haber' + n).val().length == 0) {
                                        swal("", "Ingrese una cuenta en el debe o haber", "info"); 
                                        return false;
                                    }  else if ($('#valor_debe' + n).val().length == 0) {
                                        $('#valor_debe' + n).css({borderColor: "red"});
                                        $('#valor_debe' + n).focus();
                                        return false;
                                    }else if ($('#valor_haber' + n).val().length == 0) {
                                        $('#valor_haber' + n).css({borderColor: "red"});
                                        $('#valor_haber' + n).focus();
                                        return false;
                                    }

                                }
                            }
                        }

                        if(parseFloat($('#total_debe').val())!=parseFloat($('#total_haber').val())){
                          swal("", "Los Totales del Debe y Haber tienen que ser iguales", "info"); 
                          return false;
                        }

                        if(k==0){
                           swal("", "No se puede Guardar asiento sin detalle", "info"); 
                          return false;
                        }
                        
                     $('#frm_save').submit();   
               }   
    </script>

