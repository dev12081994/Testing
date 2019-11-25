<section role="main" class="content-body">
	<header class="page-header">
		<h2>GST Report</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Reports</span></li>				
				<li><span>GST Report</span></li>
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

					<h2 class="panel-title">GST Report</h2>
				</header>
				<div class="panel-body">
				 	<form method="post" id="srchgst_form" action="<?php echo base_url(); ?>REPORTS/exportGstRep">                  
                        <div class="row">
                            

                            <div class="col-sm-2">
								<select data-plugin-selectTwo class="form-control " id="srch_gst" name="srch_gst">
                                    <option value="">Select GST</option>
									<?php foreach ($gst_list as $key => $gst) { echo "<option value='".$gst['c_taxperc']."'>".ucwords(strtolower($gst['c_name']))."</option>";
									} ?>
                           		</select>
							</div>   
                            
                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_invid" id="srch_invid" class="form-control" placeholder="Bill No.">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_ftdate" id="srch_ftdate" class="form-control mydatepicker" placeholder="From Date"  autocomplete="off">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_ttdate" id="srch_ttdate" class="form-control mydatepicker" placeholder="To Date"  autocomplete="off">                                     
                            </div>

                            <div class="col-md-4 text-right">
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-info reload-table" title="Search" onclick="return srchGstRep()"><i class="fa fa-search"></i></button> 
								<a target="_blank" type="button" class="mb-xs mt-xs mr-xs btn btn-warning" title="Search" onclick="printDiv('print_div')"><i class="fa fa-print"></i></a> 
								<button id="exportbtn" class="mb-xs mt-xs mr-xs btn btn-success" title="Export To Excel"><i class="fa fa-download"></i></button>  
							</div>
                        </div>
                   	</form>

               		<div class="row" id="print_div">
                   		<div class="col-md-12" id="print_head" style="display:none;">
                   			<center><h1>GST Report</h1></center>
                   		</div>
                   		<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-condensed mb-none">
									<thead>
										<tr>
											<th rowspan="2">S.N.</th>
		                                    <th rowspan="2">Date</th> 
		                                    <th rowspan="2">Invoice Type</th> 
		                                    <th rowspan="2">Invoie No</th>
		                                    <th rowspan="2">Product</th> 
		                                    <th rowspan="2">Remark</th> 
		                                    <th rowspan="2">Amount</th> 
		                                    <th rowspan="2">GST(%)</th> 
		                                    <th colspan="3">GST Receive From Customer</th> 
		                                    <th colspan="3">GST Paid To Vendor</th>
		                                    <th>(Receive-Paid)</th>                                  
										</tr>
										<tr>
											<th>SGST</th> 
		                                    <th>CGST</th>  
		                                    <th>IGST</th>
		                                    <th>SGST</th> 
		                                    <th>CGST</th>  
		                                    <th>IGST</th>
		                                    <th>Balance	</th>
										</tr>
									</thead>
									<tbody id="gst_table">
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

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/reports/gst_report.js"></script>