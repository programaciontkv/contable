<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>reg_nota_debito/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
      Registro Notas de Debito <?php echo $titulo?> 
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-1">
					<?php 
					if($permisos->rop_insertar){
					?>
						<a href="<?php echo base_url();?>reg_nota_debito/nuevo/<?php echo $permisos->opc_id?>" class="btn btn-success btn-flat"><span class="fa fa-plus"></span> Nuevo</a>
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
							<td>
							<button style="width:120px" type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
						</td>
						</tr>
					</table>
					<!-- </form> -->
				</div>				
			</div>
			<br>
					
	</form>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-list table-hover" style="margin-left: -22px">
						<thead>
						<!-- 	<th>No</th> -->
							<th>Fecha</th>
							<th>Nota debito No</th>
							<th>Factura</th>
							<th class="hidden-mobile">Ruc</th>
							<th>Cliente</th>
							<th class="hidden-mobile">Total$</th>
							<th class="hidden-mobile">Estado</th>
							<th>Ajustes</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($notas)){
							foreach ($notas as $nota) {
								$n++;
						?>
							<tr>
							<!-- 	<td><?php echo $n?></td> -->
								<td><?php echo $nota->rnd_fecha_emision?></td>
								<td><?php echo $nota->rnd_numero?></td>
								<td ><?php echo $nota->rnd_num_comp_modifica?></td>
								<td class="hidden-mobile" style="mso-number-format:'@'"><?php echo $nota->rnd_identificacion?></td>
								<td ><?php echo $nota->rnd_nombre?></td>
								<td class="hidden-mobile"><?php echo $nota->rnd_total_valor?></td>
								<td class="hidden-mobile"><?php echo $nota->est_descripcion?></td>
								<td align="center">
									<div class="btn-group">
										
										<?php 
										
							        	if($permisos->rop_reporte){
										?>
											<a href="#" onclick="envio('<?php echo $nota->rnd_id?>',1)" class="btn btn-warning" title="RIDE"> <span class="fa fa-file-pdf-o" ></span></a>
										<?php 
										}
										if($permisos->rop_actualizar){
											if($nota->rnd_estado!=3 && $nota->rnd_estado!=6){
										?>
												<a href="<?php echo base_url();?>reg_nota_debito/editar/<?php echo $nota->rnd_id?>/<?php echo $opc_id?>" class="btn btn-primary" title="Editar"> <span class="fa fa-edit"></span></a>
										<?php 
											}
										}
										if($permisos->rop_eliminar){
											if($nota->rnd_estado!=3){
										?>
												<a href="<?php echo base_url();?>reg_nota_debito/anular/<?php echo $nota->rnd_id?>/<?php echo $nota->rnd_numero?>/<?php echo $permisos->opc_id?>" class="btn btn-danger btn-anular-comp" title="Anular"><span class="fa fa-times"></span></a>
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
						
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>


</section>



<script type="text/javascript">

	function envio(id,opc){
		if(opc==0){
			url='<?php echo $buscar?>';
		}else if(opc==1){
			url="<?php echo base_url();?>reg_nota_debito/show_frame/"+id+"/<?php echo $permisos->opc_id?>";
		}
		
		$('#frm_buscar').attr('action',url);
		$('#frm_buscar').submit();
	}

	
</script>