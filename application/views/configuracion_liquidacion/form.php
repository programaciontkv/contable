<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<section class="content-header">
      <h1>
        Configuracion Liquidacion
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
                            <td><label>Descripcion:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('cfa_descripcion')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="cfa_descripcion" id="cfa_descripcion" value="<?php if(validation_errors()!=''){ echo set_value('cfa_descripcion');}else{ echo $configuracion->cfa_descripcion;}?>" >
                                <?php echo form_error("cfa_descripcion","<span class='help-block'>","</span>");?>
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
                                <th>Cuenta Debe</th>
                                <th>Descripcion Debe</th>
                                <th>Cuenta Haber</th>
                                <th>Descripcion Haber</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>

                            <tbody id="lista_encabezado">
                            
                              <?php
                                $cnt_detalle=0;
                                $t_debe=0;
                                $t_haber=0;
                                  ?>
                                    <tr>
                                        <td colspan="2">
                                            <input style="text-align:left " type ="text" class="refer form-control"  id="concepto_debe" name="concepto_debe"  value="" lang="1" size="45"  list="list_cuentas"  onchange='traer_cuenta(this,0,0)'/>
                                        </td>
                                        <td>
                                          <input type="hidden" id="cfa_debe" name="cfa_debe"  value="0" lang="1" class="form-control" size="30" readonly/>
                                          <input type="text" id="descripcion_debe" name="descripcion_debe"  value="" lang="1" class="form-control" size="30" readonly/>
                                        </td>
                                       
                                        <td>
                                            <input style="text-align:left " type ="text" class="refer form-control"  id="concepto_haber" name="concepto_haber"   value="" lang="1" size="45" list="list_cuentas"  onchange='traer_cuenta(this,1,0)'/>
                                        </td>
                                        <td>
                                          <input type="hidden" id="cfa_haber" name="cfa_haber"  value="0" lang="1" class="form-control" size="30" readonly/>
                                          <input type="text" id="descripcion_haber" name="descripcion_haber"  value="" lang="1" class="form-control" size="30" readonly/>
                                        </td>
                                        
                                        <td align="center" ><input  type="button" name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_detalle','0')" lang="1" value='+'/> </td>
                                    </tr>
                                </tbody>        
                                <tbody id="lista">
                                  <?php
                                  if(!empty($cns_det)){
                                  $cnt_detalle=0;
                                  $n=0;
                                    foreach($cns_det as $rst_det) {
                                        $n++;
                                        if($rst_det->cfa_debe==''){
                                          $cfa_debe=0;
                                        }else{
                                          $cfa_debe=$rst_det->cfa_debe;
                                        }

                                        if($rst_det->cfa_haber==''){
                                          $cfa_haber=0;
                                        }else{
                                          $cfa_haber=$rst_det->cfa_haber;
                                        }
                                        ?>
                                      <tr>
                                        <td id="item<?php echo $n ?>" name="item<?php echo $n ?>" lang="<?php echo $n ?>" align="center"><?php echo $n ?></td>
                                       
                                        <td>
                                            <input style="text-align:left " type ="text" class="refer form-control"  id="concepto_debe<?php echo $n ?>" name="concepto_debe<?php echo $n ?>"   value="<?php echo $rst_det->concepto_debe ?>" lang="<?php echo $n ?>" size="45" list="list_cuentas" onchange='traer_cuenta(this,0,1)'/>
                                        </td>
                                        <td>
                                          <input type="hidden" id="cfa_debe<?php echo $n ?>" name="cfa_debe<?php echo $n ?>"  value="<?php echo $cfa_debe?>" lang="<?php echo $n ?>" class="form-control" size="30" readonly/>
                                          <input type="text" id="descripcion_debe<?php echo $n ?>" name="descripcion_debe<?php echo $n ?>"  value="<?php echo $rst_det->descripcion_debe?>" lang="<?php echo $n ?>" class="form-control" size="30" readonly/>
                                        </td>
                                        
                                        <td>
                                            <input style="text-align:left " type ="text" class="refer form-control"  id="concepto_haber<?php echo $n ?>" name="concepto_haber<?php echo $n ?>" value="<?php echo $rst_det->concepto_haber ?>" lang="<?php echo $n ?>" size="45" list="list_cuentas" onchange='traer_cuenta(this,1,1)'/>
                                        </td>
                                        <td>
                                          <input type="hidden" id="cfa_haber<?php echo $n ?>" name="cfa_haber<?php echo $n ?>"  value="<?php echo $cfa_haber?>" lang="<?php echo $n ?>" class="form-control" size="30" readonly/>
                                          <input type="text" id="descripcion_haber<?php echo $n ?>" name="descripcion_haber<?php echo $n ?>" value="<?php echo $rst_det->descripcion_haber?>" lang="<?php echo $n ?>" class="form-control" size="30" readonly/>
                                        </td>
                                        
                                           
                                        <td onclick="elimina_fila_det(this)" align="center" ><span class="btn btn-danger fa fa-trash"></span></td>
                                      </tr>
                                        <?php
                                        $cnt_detalle++;
                                        
                                    }
                                  }
                                ?>
                                </tbody>
                            
                          </table>
                          </div>
                          </div>
                          </td>
                    </tr> 
                    
                    
                </table>
              </div>
                                
              <input type="hidden" class="form-control" name="cfa_codigo" value="<?php echo $configuracion->cfa_codigo?>">
              <input type="hidden" class="form-control" name="emp_id" value="<?php echo $configuracion->emp_id?>">
              <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $cnt_detalle?>">
              <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <a href="<?php echo base_url().'configuracion_liquidacion/';echo $opc_id ?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
    
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

    
            function validar(table, opc){
              
                if($('#concepto_debe').val().length!=0 ||  $('#concepto_haber').val().length!=0){
                  clona_detalle(table);
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
                                    
                if (d == 0) {
                    i = j + 1;
                    var fila = "<tr>"+
                                        "<td id='item"+i+"' name='item"+i+"' lang='"+i+"' align='center'>"+i+"</td>"+
                                        "<td>"+
                                          "<input style='text-align:left ' type ='text' class='refer form-control'  id='concepto_debe"+i+"' name='concepto_debe"+i+"'   value='"+concepto_debe.value+"' lang='"+i+"' size='45' list='list_cuentas' onchange='traer_cuenta(this,0,1)'/>"+
                                        "</td>"+
                                        "<td>"+
                                        "<input type='hidden' id='cfa_debe"+i+"' name='cfa_debe"+i+"'  value='"+cfa_debe.value+"' lang='"+i+"' class='form-control' size='30' readonly/>"+
                                        "<input type='text' id='descripcion_debe"+i+"' name='descripcion_debe"+i+"'  value='"+descripcion_debe.value+"' lang='"+i+"' class='form-control' size='30' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input style='text-align:left ' type ='text' class='refer form-control'  id='concepto_haber"+i+"' name='concepto_haber"+i+"' value='"+concepto_haber.value+"' lang='1' size='45' list='list_cuentas'  onchange='traer_cuenta(this,1,1)'/>"+
                                        "</td>"+
                                        "<td>"+
                                        "<input type='hidden' id='cfa_haber"+i+"' name='cfa_haber"+i+"'  value='"+cfa_haber.value+"' lang='"+i+"' class='form-control' size='30' readonly/>"+
                                          "<input type='text' id='descripcion_haber"+i+"' name='descripcion_haber"+i+"' value='"+descripcion_haber.value+"' lang='"+i+"' class='form-control' size='30' readonly/>"+
                                        "</td>"+
                                        "<td onclick='elimina_fila_det(this)' align='center' >"+"<span class='btn btn-danger fa fa-trash'>"+"</span>"+"</td>"+
                                    "</tr>";
                    $('#lista').append(fila);
                    $('#count_detalle').val(i);
                }
                cfa_debe.value='0';
                cfa_haber.value='0';
                concepto_debe.value = '';
                descripcion_debe.value = '';
                concepto_haber.value = '';
                descripcion_haber.value = '';
                $('#concepto_debe').focus();
                
            }

            function elimina_fila_det(obj) {
                  var parent = $(obj).parents();
                  $(parent[0]).remove();
                  calculo();
            }
 
            

            function traer_cuenta(obj, opc,tipo) {
              var uri = base_url+'configuracion_liquidacion/traer_cuenta/'+ $(obj).val();
              j=obj.lang;
              $.ajax({
                  url: uri, //this is your uri
                  type: 'GET', //this is your method
                  dataType: 'json',
                  success: function (response) {
                    if(tipo==0){
                      if(opc==0){
                        $("#cfa_debe").val(response['pln_id']);
                        $("#descripcion_debe").val(response['pln_descripcion']);
                      }else{
                        $("#cfa_haber").val(response['pln_id']);
                        $("#descripcion_haber").val(response['pln_descripcion']);
                      }
                    }else{
                      if(opc==0){
                        $("#cfa_debe"+j).val(response['pln_id']);
                        $("#descripcion_debe"+j).val(response['pln_descripcion']);
                      }else{
                        $("#cfa_haber"+j).val(response['pln_id']);
                        $("#descripcion_haber"+j).val(response['pln_descripcion']);
                      }
                    }  
                  },
                  error : function(xhr, status) {
                      swal("", "No existe Cuenta", "info"); 
                      if(tipo==0){
                        if(opc==0){
                          $("#cfa_debe").val('0');
                          $("#concepto_debe").val('');
                          $("#descripcion_debe").val('');
                        }else{
                          $("#cfa_haber").val('0');
                          $("#concepto_haber").val('');
                          $("#descripcion_haber").val('');
                        }
                      }else{
                        if(opc==0){
                          $("#cfa_debe"+j).val('0');
                          $("#concepto_debe"+j).val('');
                          $("#descripcion_debe"+j).val('');
                        }else{
                          $("#cfa_haber"+j).val('0');
                          $("#concepto_haber"+j).val('');
                          $("#descripcion_haber"+j).val('');
                        }
                      }  
                  }
              });
          } 

            function save() {
                        if (cfa_descripcion.value.length == 0) {
                            $("#cfa_descripcion").css({borderColor: "red"});
                            $("#cfa_descripcion").focus();
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
                                if ($('#concepto_debe' + n).html() != null) {
                                  k++;
                                    if ($('#concepto_debe' + n).val().length == 0 && $('#concepto_haber' + n).val().length == 0) {
                                        swal("", "Ingrese una cuenta en el debe o haber", "info"); 
                                        return false;
                                    }  

                                }
                            }
                        }

                        
                        if(k==0){
                           swal("", "No se puede Guardar configuracion sin detalle", "info"); 
                          return false;
                        }
                        
                     $('#frm_save').submit();   
               }   
    </script>

