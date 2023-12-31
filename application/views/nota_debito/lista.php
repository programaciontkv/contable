<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>nota_debito/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
      Notas de Debito emitidas 
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-2">
					<?php 
					if($permisos->rop_insertar){
					?>
						<a href="<?php echo base_url();?>nota_debito/nuevo/<?php echo $permisos->opc_id?>" class="btn btn-success btn-flat"><span class="fa fa-plus"></span> Crear Nota de Debito</a>
					<?php 
					}
					?>
				</div>
				<div class="col-md-8">
					<form action="<?php echo $buscar;?>" method="post" id="frm_buscar">
						
					<table style="margin-left:-5px">
						<tr>
							<td class="hidden-mobile"><label>Buscar:</label></td>
							<td class="hidden-mobile"><input type="text" placeholder="RUC/NOMBRE/NC" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td>
							</td>
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control"  value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control"  value='<?php echo $fec2?>' /></td>
							<!-- <td><button type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
								</td> -->
						</tr>
					</table>
					<!-- </form> -->
				</div>				
			</div>
			<br>
			<div class="row">
					<div class="col-sm-2">
					<button style="width:120px" type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
				</div>	
				</div>
	</form>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-list table-hover" style="margin-left: -22px">
						<thead>
						<!-- 	<th>No</th> -->
							<th>Fecha</th>
							<th>Nota debito No</th>
							<th class="hidden-mobile">Usuario</th>
							<th>Factura</th>
							<th class="hidden-mobile">Vendedor</th>
							<th class="hidden-mobile">Ruc</th>
							<th>Cliente</th>
							<th class="hidden-mobile">Total$</th>
							<th class="hidden-mobile">Estado</th>
							<th>Ajustes</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						$t_ndb=0;
							$t_f=0;
						if(!empty($notas)){
							foreach ($notas as $nota) {
								$n++;
								$t_ndb+=$nota->ndb_total_valor;
								$t_f+=$nota->fac_total_valor;
						?>
							<tr>
							<!-- 	<td><?php echo $n?></td> -->
								<td><?php echo $nota->ndb_fecha_emision?></td>
								<td><?php echo $nota->ndb_numero?></td>
								<td class="hidden-mobile" style="mso-number-format:'@'"><?php echo $nota->usuario?></td>
								<td ><?php echo $nota->ndb_num_comp_modifica?></td>
								<td class="hidden-mobile"  style="mso-number-format:'@'"><?php echo $nota->vendedor?></td>
								<td class="hidden-mobile" style="mso-number-format:'@'"><?php echo $nota->ndb_identificacion?></td>
								<td ><?php echo $nota->ndb_nombre?></td>
								<td class="hidden-mobile"><?php echo $nota->ndb_total_valor?></td>
								<td class="hidden-mobile"><?php echo $nota->est_descripcion?></td>
								<td align="center">
									<div class="btn-group">
										
										<?php 
										if($nota->ndb_estado==6 ){
										?>
											<a href="#" class="btn btn-success" onclick="envio_mail(<?php echo $nota->ndb_id?>,<?php echo $permisos->opc_id?>)" title="Envio Correo"> <span  class="fa  fa-envelope"></span></a>
										<?php 
										}
										if($nota->ndb_estado==6 ||  $nota->ndb_estado==4 ){
										?>
											<a href="<?php echo base_url();?>nota_debito/consulta_sri/<?php echo $nota->ndb_id?>/<?php echo $permisos->opc_id?>/1" class="btn btn-info"> <span title="XML">XML</span></a>
										<?php
										}
							        	if($permisos->rop_reporte){
										?>
											<a href="#" onclick="envio('<?php echo $nota->ndb_id?>',1)" class="btn btn-warning" title="RIDE"> <span class="fa fa-file-pdf-o" ></span></a>
										<?php 
										}
										if($permisos->rop_actualizar){
											if($nota->ndb_estado!=3 && $nota->ndb_estado!=6){
										?>
												<!-- <a href="<?php echo base_url();?>nota_debito/editar/<?php echo $nota->ndb_id?>/<?php echo $opc_id?>" class="btn btn-primary" title="Editar"> <span class="fa fa-edit"></span></a> -->
										<?php 
											}
										}
										if($permisos->rop_eliminar){
											if($nota->ndb_estado!=3){
										?>
												<a href="<?php echo base_url();?>nota_debito/anular/<?php echo $nota->ndb_id?>/<?php echo $nota->ndb_numero?>/<?php echo $permisos->opc_id?>" class="btn btn-danger btn-anular-comp" title="Anular"><span class="fa fa-times"></span></a>
										<?php 
											}
										}
										?>
									</div>
								</td>
							</tr>
						<?php
							}
						}
						?>
						<tr>
							<td class="hidden-mobile"></td>
							<td class="hidden-mobile"></td>
							<td class="hidden-mobile"></td>
							<td class="hidden-mobile"></td>
							<td class="hidden-mobile"></td>
							<td class="hidden-mobile"></td>
							<td class="hidden-mobile"> <h4>Total notas de debito </h4></td>
							<td class="hidden-mobile"><h4> <?php echo number_format($t_ndb,2) ;?> </h4></td>
						</tr>
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>


</section>

<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">nota</h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>

<script type="text/javascript">
	function enviar_sri(){
        $.ajax({
                url: base_url+"nota_debito/consulta_sri",
                type: 'JSON',
                dataType: 'JSON',
                success: function (dt) {
                },
                    
        });    
    }

    // setInterval('enviar_sri()',30000);

    function envio_mail(id,opc){
	var ruta=base_url+"nota_debito/consulta_sri/"+id+"/"+opc+"/0";
        $.ajax({
            url: ruta,
            type: 'GET',
            timeout: 15000,
            success: function(resp){
                alert(resp);
            },
            error: function (j, t, e) {
                        if (t == 'timeout') {
                            alert('Tiempo agotado No se pudo enviar correo');
                        }else{
                        	alert(e);
                        }
            }
        });
	}

	function envio(id,opc){
		if(opc==0){
			url='<?php echo $buscar?>';
		}else if(opc==1){
			url="<?php echo base_url();?>nota_debito/show_frame/"+id+"/<?php echo $permisos->opc_id?>";
		}
		
		$('#frm_buscar').attr('action',url);
		$('#frm_buscar').submit();
	}

	
</script>