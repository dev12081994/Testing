<section role="main" class="content-body">
	<header class="page-header">
		<h2>Expenses On Vehicles Report</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Reports</span></li>				
				<li><span>Vechicle Expenses</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<!-- start: page -->	
	
	<div class="row">
		<div class="col-md-12">
			<!-- Section 1 -->
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						
						<!-- <a href="#" class="fa fa-times"></a> -->
						
						<a href="#" class="fa fa-caret-down"></a>
					</div>

					<h2 class="panel-title">Date Wise Expense Details On Vehicles</h2>
				</header>
				<div class="panel-body">
				 	<form method="post" id="srchexp_form" action="<?php echo base_url(); ?>REPORTS/exportVehicleExp">   
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
                                <input type="text" name="srch_ftdate" id="srch_ftdate" class="form-control mydatepicker" placeholder="From Expense Date" onkeydown="event.preventDefault();" autocomplete="off">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_ttdate" id="srch_ttdate" class="form-control mydatepicker" placeholder="To Expense Date" onkeydown="event.preventDefault();" autocomplete="off">                                     
                            </div>
                            
                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_fedate" id="srch_fedate" class="form-control mydatepicker" placeholder="From Entry Date" onkeydown="event.preventDefault();" autocomplete="off">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_tedate" id="srch_tedate" class="form-control mydatepicker" placeholder="To Entry Date" onkeydown="event.preventDefault();" autocomplete="off">                                     
                            </div>

                            <div class="col-md-4 text-right">
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-info reload-table" title="Search" onclick="srchExpense()"><i class="fa fa-search"></i></button> 
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" title="Search" onclick="printDiv('print_div')"><i class="fa fa-print"></i></button> 
								<button name="format1" class="mb-xs mt-xs mr-xs btn btn-success" title="Export To Excel"><i class="fa fa-download"></i></button> 
							</div>
                        </div>
                   	</form>
                   	<div class="row" id="print_div">
		           		<div class="col-md-12" id="print_head" style="display:none;">
		           			<center><h1>Date Wise Vehicle Expense Report</h1></center>
		           		</div>
		           		<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-condensed mb-none">
									<thead>
										<tr>
											<th>S.N.</th>
		                                    <th>Entry</th> 
		                                    <th title="Expense Date">Expense</th> 
		                                    <th>Vehicle No.</th> 
		                                    <th>Member Type</th> 
		                                    <th>Payable To</th> 
		                                    <th>Ledger</th>                                 
		                                    <th>Amount</th>
		                                    <th>Remark</th>
		                                    <th class="h">View</th>  
										</tr>
									</thead>
									<tbody id="exp_table">
										
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

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/reports/vehicleexp_report.js"></script>