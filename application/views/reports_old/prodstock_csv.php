<section role="main" class="content-body">
	<header class="page-header">
		<h2>Daily Material Purchasing Report </h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Reports</span></li>				
				<li><span>Daily Material Purchase</span></li>
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

					<h2 class="panel-title">Date Wise Material Purchasing Report </h2>
				</header>
				<div class="panel-body">
				 	<form method="post" id="srchpurchase_form" action="<?php echo base_url(); ?>REPORTS/exportPurchasedProduct">                               
                        <div class="row">
                            
                            <div class="col-sm-2">
                            	<input type="hidden" name="srch_invfor" id="srch_invfor" value="1">
								<select data-plugin-selectTwo class="form-control " id="srch_vendor" name="srch_vendor">
                                    <option value="">Select Vendor</option>
									<?php foreach ($vendor as $key => $value) { echo "<option value='".$value['c_id']."'>".ucwords(strtolower($value['vendor']))."</option>";
									} ?>
                           		</select>
							</div>   

                            <div class="col-sm-2">
								<select data-plugin-selectTwo class="form-control" id="srch_prod" name="srch_prod">
                                    <option value="">Select Product</option>
									<?php foreach ($product_list as $key => $value) { echo "<option value='".$value['prod_id']."'>".ucwords(strtolower($value['prod_name']))."</option>";
									} ?>
                           		</select>
							</div>   
                            
                            <div class="col-sm-2">
								<select data-plugin-selectTwo class="form-control " id="srch_type" name="srch_type">
                                    <option value="">Select Type</option>
                                    <option value="1">Credit</option>
                                    <option value="2">Paid</option>									
                           		</select>
							</div>                            

                            <div class="form-group col-lg-2">
                                <input type="text" name="inv_billno" id="inv_billno" class="form-control" placeholder="Search Invoice Number">
                            </div>
                            
                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_fdate" id="srch_fdate" data-input-mask="99/99/9999" data-plugin-masked-input data-plugin-datepicker class="form-control datepicker" placeholder="Search From Date">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_tdate" id="srch_tdate" data-input-mask="99/99/9999" data-plugin-masked-input data-plugin-datepicker class="form-control datepicker" placeholder="Search To Date">                                     
                            </div>                            
                        </div>
                        <div class="row">
                        	<div class="col-md-12 text-right">
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-info reload-table" title="Search" onclick="srchPurchase()"><i class="fa fa-search"></i></button> 
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" title="Search" onclick="printDiv('print_div')"><i class="fa fa-print"></i></button> 
								<button id="exportbtn" class="mb-xs mt-xs mr-xs btn btn-success" title="Export To Excel"><i class="fa fa-download"></i></button> 
							</div>
                        </div>
                   	</form>
                   	<div class="row" id="print_div">
                   		<div class="col-md-12" id="print_head" style="display:none;">
                   			<center><h1>Date Wise Material Purchasing Report</h1></center>
                   		</div>
                   		<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-condensed mb-none">
									<thead>
										<tr>
											<th>S.N.</th>
		                                    <th>Date</th> 
		                                    <th>Invoice No.</th> 
		                                    <th>Party</th>
		                                    <th>Material</th>
		                                    <th>Qty.</th>
		                                    <th>Rate</th>
		                                    <th>Amount</th>
		                                    <th>Discount</th>
		                                    <th>GST Amt.</th>
		                                    <th>Net</th>  
		                                    <th>Paid/Credit</th>                                  
										</tr>
									</thead>
									<tbody id="purchase_table">
										
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

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/reports/purchased_product.js"></script>