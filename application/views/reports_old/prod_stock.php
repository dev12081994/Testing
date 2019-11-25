<section role="main" class="content-body">
	<header class="page-header">
		<h2>Product Stock Report</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Reports</span></li>				
				<li><span>Product Stock</span></li>
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

					<h2 class="panel-title">Product Stock</h2>
				</header>
				<div class="panel-body">
				 	<form method="post" id="srchproduct_form" action="<?php echo base_url(); ?>REPORTS/exportProdStock">                               
                        <div class="row">                            
                            <div class="col-sm-2">
								<select data-plugin-selectTwo class="form-control " id="srch_ctgid" name="srch_ctgid">
                                    <option value="">Category</option>
									<?php foreach ($ctg_list as $key => $value) { echo "<option value='".$value['c_id']."'>".ucwords(strtolower($value['c_name']))."</option>";
									} ?>
                           		</select>
							</div>
                            <div class="col-sm-2">
								<select data-plugin-selectTwo class="form-control " id="srch_unit" name="srch_unit">
                                    <option value="">Select Unit</option>
									<option value="Kg.">Kg.</option>
									<option value="Gram.">Gram</option>
									<option value="Feet">Feet</option>
									<option value="Inch">Inch</option>
									<option value="Liter">Liter</option>
									<option value="Pcs.">Pcs.</option>
                           		</select>
							</div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_name" id="srch_name" class="form-control" placeholder="Product Name">
                            </div>
                            
                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_fdate" id="srch_fdate" class="form-control mydatepicker" placeholder="From Date">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_tdate" id="srch_tdate" class="form-control mydatepicker" placeholder="To Date">                                     
                            </div>

                            <div class="col-md-2 text-right">
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-info reload-table" title="Search" onclick="srchProdStock()"><i class="fa fa-search"></i></button> 
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" title="Search" onclick="printDiv('print_div')"><i class="fa fa-print"></i></button> 
								<button id="exportbtn" class="mb-xs mt-xs mr-xs btn btn-success" title="Export To Excel"><i class="fa fa-download"></i></button> 
							</div>
                        </div>
                   	</form>

                   	<div class="row" id="print_div">
                   		<div class="col-md-12" id="print_head" style="display:none;">
                   			<center><h1>Product Stock Report</h1></center>
                   		</div>
                   		<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-condensed mb-none">
									<thead>
										<tr>
											<th>S.N.</th>
		                                    <th>Category</th>
		                                    <th>Product</th>
		                                    <th>HSN/SAC</th>
		                                    <th>Unit</th>
		                                    <th>Opening Stock</th>
		                                    <th>Purchased Qty.</th>
		                                    <th>Sold Qty.</th>
		                                    <th>Current Stock</th>
		                                    <th>Purchase Rate</th>
		                                    <th>Sale Rate</th>
		                                    <th>GST</th>
		                                    <th>GST Included In Rate</th>
		                                    <th>Remark</th>
										</tr>
									</thead>
									<tbody id="product_table">
										
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

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/reports/prod_stock.js"></script>