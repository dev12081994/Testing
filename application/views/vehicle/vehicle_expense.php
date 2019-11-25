<section role="main" class="content-body">
	<header class="page-header">
		<h2>Payment Details</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Accounts</span></li>				
				<li><span>Payment</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<!-- Starts of modals-->
	
	<div class="modal zoom-anim-dialog" data-backdrop="static" id="addpayment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Product Details</h4>
				</div>
				
				<div class="modal-body">
					
					<div class="row">	
						<div class="col-sm-12">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-condensed mb-none">
									<thead>
										<tr>
											<th>S.N.</th>
			                                <th>Product</th> 
			                                <th>Unit</th> 
			                                <th>Qty.</th> 
			                                <th>Rate</th>
			                                <th>Disc.</th>
			                                <th>GST</th>
			                                <th>Net Amt</th> 
										</tr>
									</thead>
									<tbody id="invinfo_table">
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
				</div>				
			</div>
		</div>
	</div>
	
	<div id="delConfirm" class="modal-block modal-block-primary mfp-hide">
		<section class="panel">
			<div class="panel-body text-center">
				<div class="modal-wrapper">
					<div class="modal-icon center">
						<i class="fa fa-question-circle"></i>
					</div>
					<div class="modal-text">
						<input type="hidden" id="del_id" class="form-control" readonly="true">
						<h4>Are you sure?</h4>
						<p>To Delete This Record Permanentaly !!!!</p>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button class="btn btn-primary" onclick="confirm_deletion()">Confirm</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</section>
	</div>
	<!-- End of modals-->

	<!-- start: page -->	
	
	<div class="row">
		<div class="col-md-12">
			<!-- Section 1 -->
			<section class="panel">
				<header class="panel-heading hide">
					<div class="panel-actions">
						
						<!-- <a href="#" class="fa fa-times"></a> -->
						
						<a href="#" class="fa fa-caret-down"></a>
					</div>

					<h2 class="panel-title">Purchases Detail </h2>
				</header>
				<div class="panel-body">
				 	<form method="post" id="srchexp_form" action="<?php echo base_url(); ?>VEHICLE/srchVehicleExpense">   
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
                        <div class="row form-group">

                            <div class="col-sm-3 source">
								<select data-plugin-selectTwo class="form-control " id="srch_sourceid" name="srch_sourceid" >
                                    <option value="">Select Vehicle</option>
                                </select>									
							</div>   

							<div class="col-sm-3">
								<select data-plugin-selectTwo class="form-control " id="srch_ctype" name="srch_ctype" onchange="fetchMembers()">
                                    <option value="">Select Member Type</option>
                                     <?php 
                                        	foreach ($this->config->item('member_type') as $key => $value) 
                                        	{
                                        		echo "<option value='".$key."'".$sel.">".$value."</option>";
                                        	}
                                        ?>
                                </select>
								
							</div>

                            <div class="col-sm-3">
								<select data-plugin-selectTwo class="form-control " id="srch_cid" name="srch_cid">
                                    <option value="">Select Paid To</option>
									<?php foreach ($vendor as $key => $value) { echo "<option value='".$value['c_id']."'>".ucwords(strtolower($value['vendor']))."</option>";
									} ?>
                           		</select>
							</div>   							

	                        <div class="col-sm-3">
								<select data-plugin-selectTwo class="form-control" id="srch_ldgid" name="srch_ldgid" >
                                    <option value="">Select Ledger</option>  
                                    <?php foreach ($ledger_data as $ldg) {
                                    	$sel="";
                                    	if(isset($voch['acc_ldgid']) && $voch['acc_ldgid']==$ldg['c_id'])
                                    		$sel='selected';

                                    	echo "<option value='".$ldg['c_id']."' ".$sel.">".ucwords(strtolower($ldg['c_name']))."</option>";
                                    } ?>                                      
                                </select>								
							</div>                   
						</div>        
						 <div class="row form-group">
                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_ftdate" id="srch_ftdate" class="form-control mydatepicker" placeholder="From Expense Date">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_ttdate" id="srch_ttdate" class="form-control mydatepicker" placeholder="To Expense Date">                                     
                            </div>
                            
                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_fedate" id="srch_fedate" class="form-control mydatepicker" placeholder="From Entry Date">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_tedate" id="srch_tedate" class="form-control mydatepicker" placeholder="To Entry Date">                                     
                            </div>

                            <div class="col-md-3 text-right">
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-info reload-table" title="Search" onclick="srchExpense()"><i class="fa fa-search"></i></button> 
								<a class="mb-xs mt-xs mr-xs btn btn-primary pull-right" data-toggle="modal" data-original-title="Add New Member" data-placement="top" href="<?php echo base_url() ?>vehicle/addVehicleExpense"><i class="fa fa-plus"></i></a>
								<button name="exportbtn" id="exportbtn" class="mb-xs mt-xs mr-xs btn btn-success" title="Export"><i class="fa fa-download"></i></button>
							</div>
                        </div>
                   	</form>

					<div class="row">
						<div class="col-md-12" id="exp_table">
							
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/vehicle/vehicle_expense.js"></script>