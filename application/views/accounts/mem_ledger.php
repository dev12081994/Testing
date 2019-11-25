<section role="main" class="content-body">
	<header class="page-header">
		<h2>Member's Ledger</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Reports</span></li>				
				<li><span>Member's Ledger</span></li>
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
				 	<form method="post" id="srchldg_form" action="<?php echo base_url(); ?>ADMIN001/exportCusCsv">   
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
                        <div class="row">
                            <div class="col-sm-2">
								<select data-plugin-selectTwo class="form-control " id="srch_for" name="srch_for" onchange="fetchSourceData()">
                                    <option value="">Member Type</option>
                                    <?php 
                                    	$last_val='';
                                    	foreach ($this->config->item('member_type') as $key => $value) 
                                    	{
                                    		$last_val=$key;
                                    		echo "<option value='".$key."'>".$value."</option>";
                                    	}                                           	
                                    ?>
                                    <option value='<?php echo $last_val+1; ?>'>Vehicle</option>
                                    <option value='<?php echo $last_val+2; ?>'>Project</option>
                                </select>
								
							</div>

                            <div class="col-sm-2">
								<select data-plugin-selectTwo class="form-control " id="srch_cid" name="srch_cid" required>
                                   <option value="">Select Option</option>
                           		</select>
							</div>   
							
	                        <div class="col-sm-2">
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
                            
                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_ftdate" id="srch_ftdate" data-input-mask="99/99/9999" data-plugin-masked-input data-plugin-datepicker class="form-control datepicker" placeholder="Search From Date">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_ttdate" id="srch_ttdate" data-input-mask="99/99/9999" data-plugin-masked-input data-plugin-datepicker class="form-control datepicker" placeholder="Search To Date">                                     
                            </div>

                            <div class="col-md-2 text-right">
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-info reload-table" title="Search" onclick="return srchLedger()"><i class="fa fa-search"></i></button> 
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" title="Search" onclick="printDiv('print_div')"><i class="fa fa-print"></i></button> 
							</div>
                        </div>
                   	</form>

               		<div class="row" id="print_div">
                   		<div class="col-md-12" id="print_head" style="display:none;">
                   			<center><h1>Date Wise Material Purchasing Report</h1></center>
                   		</div>
                   		<div class="col-md-12">
		                   	<div class="row">
		                   		<div class="col-md-12">
		                   			<button type="button" id="perticular" class="mb-xs mt-xs mr-xs btn btn-primary btn-block">
		                   				<!-- <div style="width:49%;text-align: left !important;">ttt</div> -->	
		                   				<div class="row">
		                   					<div class="col-md-6" style="font-size:16px;text-align: left !important;"><span id="memname">Member Name : Not Selected</span></div>
		                   					<div class="col-md-6" style="font-size:16px;text-align: right !important;">Balance : <span id="membal">0</span></div>
		                   				</div>
		                   			</button>
		                   		</div>
		                   	</div>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-condensed mb-none">
									<thead>
										<tr>
											<th>S.N.</th>
		                                    <th>Date</th> 
		                                    <th>Ledger</th> 
		                                    <th>Remark</th> 
		                                    <th>Dr</th>
		                                    <th>Cr</th> 
										</tr>
									</thead>
									<tbody id="ledger_table">
									</tbody>
								</table>
							</div>
						</div>	
					</div>	
				</div>
			</section>
		</div>
	</div>
	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/accounts/mem_ledger.js"></script>