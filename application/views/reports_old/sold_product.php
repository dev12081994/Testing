<section role="main" class="content-body">
	<header class="page-header">
		<h2>Daily Material Sales reports</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Reports</span></li>				
				<li><span>Daily Material Sales</span></li>
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

					<h2 class="panel-title">Date Wise Material Sales Report </h2>
				</header>
				<div class="panel-body">
				 	<form method="post" id="srchpurchase_form" action="<?php echo base_url(); ?>REPORTS/exportSoldProduct">                               
                        <div class="row">
                            
                            <div class="col-sm-2">
								<select data-plugin-selectTwo class="form-control " id="srch_memtype" name="srch_memtype" onchange="fetchMembers()">
                                    <option value="">Select Type</option>
                                    <?php foreach ($this->config->item('member_type') as $key => $value) {
                                    echo '<option value="'.$key.'">'.$value.'</option>';
                                    } ?>
                                    <option value="<?php echo $key+1; ?>">Project</option>
                                </select>									
							</div>   
                            
							<div class="col-sm-2">
								<select data-plugin-selectTwo class="form-control" id="srch_member" name="srch_member">
                                    <option value="">Customer</option>                                        
                                </select>
								<input type="hidden" name="prod_ids" id="prod_ids" class="form-control" placeholder="Row Ids..." readonly>									
							</div>

                            <div class="col-sm-2">
								<select data-plugin-selectTwo class="form-control " id="srch_type" name="srch_type">
                                    <option value="">Sattled Type</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Partial Sattled</option>
                                    <option value="2">Sattled</option>
                           		</select>
							</div>                            

                            <div class="form-group col-lg-2">
                                <input type="text" name="inv_billno" id="inv_billno" class="form-control" placeholder="Invoice Number">
                            </div>
                            
                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_fdate" id="srch_fdate" class="form-control mydatepicker" placeholder="From Date" onkeydown="event.preventDefault();" autocomplete="off">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_tdate" id="srch_tdate" class="form-control mydatepicker" placeholder="To Date" onkeydown="event.preventDefault();" autocomplete="off">                                     
                            </div>                            
                        </div>
                        <div class="row">
                        	<div class="col-md-12 text-right">
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-info reload-table" title="Search" onclick="srchSales()"><i class="fa fa-search"></i></button> 
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" title="Search" onclick="printDiv('print_div')"><i class="fa fa-print"></i></button> 
								<button name="format1" class="mb-xs mt-xs mr-xs btn btn-success" title="Export To Excel"><i class="fa fa-download"></i> Format-1 </button> 
								<button name="format2" class="mb-xs mt-xs mr-xs btn btn-success" title="Export To Excel"><i class="fa fa-download"></i> Format-2 </button> 
							</div>
                        </div>
                   	</form>
                   	<div class="row" id="print_div">
                   		<div class="col-md-12" id="print_head" style="display:none;">
                   			<center><h1>Date Wise Material Sales Report</h1></center>
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

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/reports/sold_product.js"></script>