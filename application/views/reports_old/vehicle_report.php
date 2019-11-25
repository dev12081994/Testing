<section role="main" class="content-body">
	<header class="page-header">
		<h2>Daily Vehicle's Running Details</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Reports</span></li>
				<li><span>Vehicle Running Report</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	
	<!-- start: page -->
	
	
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">						
				<!-- <a href="#" class="fa fa-times"></a> -->						
				<a href="#" class="fa fa-caret-down"></a>
			</div>

			<h2 class="panel-title">Date Wise Vehicle Running Report </h2>
		</header>
		<div class="panel-body">
			<form name="srchvrun_form" id="srchvrun_form" method="post" action="<?php echo base_url(); ?>REPORTS/exportVehicleRep">				
				<div class="row form-group">
					<div class="col-md-2">
						<select name="srch_vtype" id="srch_vtype" class="form-control">
							<option value="">Select Type</option>
							<?php foreach ($vehi_type as $key => $value) {
								echo "<option value='".$value['vt_id']."'>".strtoupper($value['vt_name'])."</option>";
							} ?>
						</select>
					</div>
					
					<div class="col-md-2">
						<input type="text" class="form-control" placeholder="Vehicle Number" id="srch_vnum" name="srch_vnum"> 
					</div>


					<div class="col-md-2">
						<input type="text" id="srch_fdate" name="srch_fdate" class="form-control mydatepicker" placeholder="From Date" onkeydown="event.preventDefault();" autocomplete="off"/>
					</div>

					<div class="col-md-2">
						<input type="text" id="srch_tdate" name="srch_tdate" class="form-control mydatepicker" placeholder="To Date" onkeydown="event.preventDefault();" autocomplete="off"/>
					</div>

					<div class="col-md-2">
						<input type="text" class="form-control" placeholder="Customer Name" id="srch_cus" name="srch_cus"> 
					</div>
					<div class="col-md-2">
						<input type="text" class="form-control" placeholder="Driver Name" id="srch_driver" name="srch_driver"> 
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-2">
						<select name="srch_runstatus" id="srch_runstatus" class="form-control">
							<option value="">Select Status</option>
							<option value="1">Running</option>
							<option value="2">Stop</option>
						</select>					
					</div>
					<div class="col-md-2">
						<input type="text" class="form-control" placeholder="Search From" id="srch_from" name="srch_from"> 
					</div>
					<div class="col-md-2">
						<input type="text" class="form-control" placeholder="Search To" id="srch_to" name="srch_to"> 
					</div>
					<div class="col-md-6 text-right">
						<button type="button" onclick="srchVrunDetails()" title="Click To Search" class="mb-xs mt-xs mr-xs btn btn-info"><i class="fa fa-search"></i></button> 
						<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" title="Search" onclick="printDiv('print_div')"><i class="fa fa-print"></i></button> 
						<button name="format1" class="mb-xs mt-xs mr-xs btn btn-success" title="Export To Excel"><i class="fa fa-download"></i> Format-1 </button> 
						<button name="format2" class="mb-xs mt-xs mr-xs btn btn-success" title="Export To Excel"><i class="fa fa-download"></i> Format-2 </button> 
					</div>
				</div>
			</form>
			<div class="row" id="print_div">
           		<div class="col-md-12" id="print_head" style="display:none;">
           			<center><h1>Date Wise Vehicle Running Report</h1></center>
           		</div>
           		<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<thead>
								<tr>
									<th>S.N.</th>
									<th>Date</th>
									<th>Vehicle</th>
									<th>Number</th>
									<th>Driver</th>
									<th>Status</th>
									<th>Meter Start</th>
									<th>Meter Stop</th>
									<th>Customer</th>
									<th>From</th>
									<th>To</th>
									<th>Work</th>
									<th>Amount</th>
									<th>Remark</th>
									<th>View</th>
								</tr>
							</thead>
							<tbody id="vrun_table">
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/reports/vehicle_report.js"></script>