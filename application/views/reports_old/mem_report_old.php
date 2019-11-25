<section role="main" class="content-body">
	<header class="page-header">
		<h2>Member's Report</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Reports</span></li>				
				<li><span>Member's Report</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<!-- start: page -->	
	
	<div class="row">
		<div class="col-md-12">
			<!-- Section 1 -->
			<section class="panel">
				<header class="panel-heading hide">
					<div class="panel-actions">						
						<a href="#" class="fa fa-caret-down"></a>
					</div>

					<h2 class="panel-title">Member's Report</h2>
				</header>
				<div class="panel-body">
				 	<form method="post" id="srchldg_form" action="<?php echo base_url(); ?>REPORTS/exportMemReport">                  
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

                            <div class="col-sm-3">
								<select data-plugin-selectTwo class="form-control " id="srch_cid" name="srch_cid" required>
                                    <option value="">Select Member</option>
									<?php foreach ($vendor as $key => $value) { echo "<option value='".$value['c_id']."'>".ucwords(strtolower($value['vendor']))."</option>";
									} ?>
                           		</select>
							</div>   
							                            
                            
                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_ftdate" id="srch_ftdate" data-input-mask="99/99/9999" data-plugin-masked-input data-plugin-datepicker class="form-control datepicker" placeholder="Search From Date"  autocomplete="off">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_ttdate" id="srch_ttdate" data-input-mask="99/99/9999" data-plugin-masked-input data-plugin-datepicker class="form-control datepicker" placeholder="Search To Date"  autocomplete="off">                                     
                            </div>

                            <div class="col-md-3 text-right">
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-info reload-table" title="Search" onclick="return srchMemReport()"><i class="fa fa-search"></i></button> 
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" title="Search" onclick="printDiv('print_div')"><i class="fa fa-print"></i></button> 
								<button id="exportbtn" class="mb-xs mt-xs mr-xs btn btn-success" title="Export To Excel"><i class="fa fa-download"></i></button>  
							</div>
                        </div>
                   	</form>

               		<div class="row" id="print_div">
                   		<div class="col-md-12" id="print_head" style="display:none;">
                   			<center><h1>Member's Report</h1></center>
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
	                   		<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-condensed mb-none">
										<thead>
											<tr>
												<th>S.N.</th>
			                                    <th>Date</th> 
			                                    <th>Bill No</th> 
			                                    <th>Remark</th>
			                                    <th>Description</th> 
			                                    <th>Qty</th> 
			                                    <th>Rate</th> 
			                                    <th>Discount</th> 
			                                    <th>GST</th> 
			                                    <th>Disc. On Bill</th> 
			                                    <th>Amt.(Dr)</th> 
			                                    <th>Amt.(Cr)</th> 
			                                    <th>Balance</th>                         
											</tr>
										</thead>
										<tbody id="member_rep">
										</tbody>
									</table>
								</div>
							</div>
						</div>	
					</div>	
				</div>
			</section>
		</div>
	</div>
	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/reports/mem_report.js"></script>