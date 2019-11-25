<?php foreach ($voucher as $voch){} ?>

<section role="main" class="content-body">
	<header class="page-header">
		<h2>Add/Update Expense Details On Vehicles <span style='color:red;background-color:#ffffff'>(Only Credit Expenses)</span></h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Vehicle</span></li>				
				<li><span>Expense</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<div class="row">
		<div class="col-md-12">
			<!-- Section 1 -->
			<section class="panel">
				<header class="panel-heading hide">
					<div class="panel-actions">						
						<!-- <a href="#" class="fa fa-times"></a> -->						
						<a href="#" class="fa fa-caret-down"></a>
					</div>

					<h2 class="panel-title">Expense Details </h2>
				</header>

				<div class="panel-body">

					<?php
						$msg='';
						$alert_type='alert-info'; 
						if($this->session->flashdata('message')!='')
						{
							$msg=$this->session->flashdata('message');
							$alert_type=$this->session->flashdata('alert_type');
						}
					?>

					<div class="alert <?php echo $alert_type; if($msg==='')echo ' hide'; ?>">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<?php echo $this->session->flashdata('message') ; ?>
					</div>

					<form method="post" id="payment_form" enctype="multipart/form-data" class="form-horizontal" action="<?php echo base_url() ?>VEHICLE/saveVehicleExpense">
						<div class="modal-body">
							<h4>Enter Expense Details :</h4>					
							
							<div class="form-group row">
															
								<div class="col-lg-3">
	                                <label class="control-label">Payment Date<span class="required">*</span></label>			
	                                <input type="hidden" name="acc_id" id="acc_id" value="<?php if(isset($voch['acc_id']))echo $voch['acc_id']; ?>" readonly placeholder="Voucher Id" class="form-control" />	
	                                <input type="hidden" name="acc_redi" id="acc_redi" value="2" readonly placeholder="Voucher Id" class="form-control" />					
	                               
	                                <input type="text" name="acc_trandt" id="acc_trandt" class="form-control mydatepicker empopt" onkeydown="event.preventDefault();" placeholder="dd-mm-yyyy"  required autocomplete="off" value="<?php if(isset($voch['acc_trandt']))echo date('d-m-Y',strtotime($voch['acc_trandt'])); ?>">
	                            </div>

	                            <div class="col-lg-3">
		                            <label class="control-label">Amount</label><br/>                             
		                            <input type="text" name="acc_amt" id="acc_amt" class="form-control" placeholder="Enter Amount" autocomplete="off" required value="<?php if(isset($voch['acc_amt']))echo $voch['acc_amt']; ?>" />
		                        </div>	

	                            <div class="col-sm-3">
									<label class="control-label">Expense On<span class="required">*</span></label>
									<select data-plugin-selectTwo class="form-control " id="acc_vochfor" name="acc_vochfor" onchange="fetchSourceData()" required>
                                        <?php 
                                        	foreach ($this->config->item('tran_for') as $key => $value) 
                                        	{
                                        		if($key==3)
                                        		echo "<option value='".$key."' selected>".$value."</option>";
                                        	}
                                        ?>
                                    </select>									
								</div>

	                            <div class="col-sm-3 source">
									<label class="control-label" id="lbl_source">Select Option<span class="required">*</span></label>
									<select data-plugin-selectTwo class="form-control " id="acc_sourceid" name="acc_sourceid" >
                                        <option value="">Select Option</option>
                                    </select>									
								</div>
								
	                        </div>
							<div class="form-group row">
								
								<div class="col-sm-3">
									<label class="control-label">Member Type<span class="required">*</span></label>
									<select data-plugin-selectTwo class="form-control " id="member_type" name="member_type" onchange="fetchMembers()" required>
                                        <option value="">Select Type</option>
                                        <?php 
                                        	foreach ($this->config->item('member_type') as $key => $value) 
                                        	{
                                        		$sel='';
                                        		if(isset($voch['c_type']) && $voch['c_type']==$key)
                                        			$sel='selected'; 
                                        		echo "<option value='".$key."'".$sel.">".$value."</option>";
                                        	}
                                        ?>
                                    </select>
									
								</div>

								<div class="col-sm-3">
									<label class="control-label">Paid To<span class="required">*</span></label>
									<select data-plugin-selectTwo class="form-control" id="acc_cid" name="acc_cid"  required>
                                        <option value="">Select Paid To</option>                                        
                                    </select>								
								</div>

								<div class="col-sm-3">
									<label class="control-label">Ledger<span class="required">*</span></label>
									<select data-plugin-selectTwo class="form-control" id="acc_ldgid" name="acc_ldgid"  required>
                                        <option value="">Select Ledger</option>  
                                        <?php foreach ($ledger_data as $ldg) {
                                        	$sel="";
                                        	if(isset($voch['acc_ldgid']) && $voch['acc_ldgid']==$ldg['c_id'])
                                        		$sel='selected';

                                        	echo "<option value='".$ldg['c_id']."' ".$sel.">".ucwords(strtolower($ldg['c_name']))."</option>";
                                        } ?>                                      
                                    </select>								
								</div>
								
								<div class="col-lg-3">
		                            <label class="control-label">Upload File </label><br/>                             
		                            <input type="file" name="acc_docs" id="acc_docs" class="form-control" />
		                        </div>										

							</div>	

							<div class="form-group row">
		                        <div class="col-lg-12">
		                            <label class="control-label">Remark  </label><br/>                             
		                            <textarea name="acc_remark" id="acc_remark" class="form-control" placeholder="Enter Remark" rows="1"><?php if(isset($voch['acc_remark']))echo $voch['acc_remark']; ?></textarea>
		                        </div>
		                    </div>
						</div>
						
						 
						<div class="modal-footer">
							<button type="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;Submit</button>
							<button type="reset" class="btn btn-danger"><i class="fa fa-refresh "></i>&nbsp;Clear</button>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
	<!-- end: page -->
</section>

<script type="text/javascript">
	
	$(document).ready(function(){
		$('#acc_vochfor').trigger('change');
		<?php if(isset($voch['acc_cid']) && $voch['acc_cid']!=null) { ?> 
			fetchMembers('<?php echo $voch['acc_cid']; ?>');
		<?php } ?>

		<?php if(isset($voch['acc_vochfor']) && $voch['acc_vochfor']!=null) { ?> 
			fetchSourceData('<?php echo $voch['acc_sourceid']; ?>');
		<?php }else { ?> 
			$('#acc_vochfor').trigger('change');
		<?php } ?>
	});

</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/vehicle/add_vehicle_expense.js"></script>