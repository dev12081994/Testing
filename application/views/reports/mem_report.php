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
				 	<form method="post" id="srchldg_form" action="<?php echo base_url(); ?>REPORTS/srchMemReport">                  
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
                                <input type="text" name="srch_ftdate" id="srch_ftdate" class="form-control mydatepicker" placeholder="From Date" onkeydown="event.preventDefault();" autocomplete="off">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_ttdate" id="srch_ttdate" class="form-control mydatepicker" placeholder="To Date" onkeydown="event.preventDefault();" autocomplete="off">                                     
                            </div>

                            <div class="col-md-3 text-right">
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-info reload-table" title="Search" onclick="return srchMemReport()"><i class="fa fa-search"></i></button> 
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" title="Search" onclick="printDiv('print_div')"><i class="fa fa-print"></i></button> 
								<button id="exportbtn" name="exportbtn" class="mb-xs mt-xs mr-xs btn btn-success" title="Export To Excel"><i class="fa fa-download"></i></button>  
							</div>
                        </div>
                   	</form>

               		<div class="row" id="print_div">
                   		<div class="col-md-12" id="print_head" style="display:none;">
                   			<center><h1>Member's Report</h1></center>
                   		</div>
                   		<div class="col-md-12" id="member_rep">
		                   	
	                   		<div class="col-md-12"  >
								<!-- table will be replaced here-->
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