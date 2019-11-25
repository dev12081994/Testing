<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.css" />
<!-- Theme CSS -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme.css" />
<!-- Skin CSS -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/skins/default.css" />

<section role="main" class="content-body">
	<header class="page-header">
		<h2>Member Wise Invoice Details</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Inventory</span></li>				
				<li><span>Member Wise Invoice</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<!-- Starts of modals-->

	
	<div class="modal zoom-anim-dialog" data-backdrop="static" id="invinfo_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
			                                <th>Date</th> 
			                                <th>Invoice No.</th> 
			                                <th>Perticular</th> 
			                                <th>Amount</th> 
			                                <th>GST</th>
			                                <th>Disc.</th>
			                                <th>Round off</th>
			                                <th>Net Amt</th> 
			                                <th>Remark</th> 
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
						<p>All Record Related To This Invoice Will Be Delete Permanentaly?</p>
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
				 	<form method="post" id="srchpurchase_form" action="<?php echo base_url(); ?>ADMIN001/exportCusCsv">                               
                        <div class="row">
                            
                            <div class="col-sm-2">
								<select data-plugin-selectTwo class="form-control" id="srch_invtype" name="srch_invtype" onchange="fetchMembers()">
                                    <option value="">Select Type</option>
                                    <?php foreach ($this->config->item('member_type') as $key => $value) {
                                    echo '<option value="'.$key.'">'.$value.'</option>';
                                    } ?>
                                    <option value="<?php echo $key+1; ?>">Project</option>
                           		</select>
							</div> 
                            <div class="col-sm-2">
								<select data-plugin-selectTwo class="form-control" id="srch_perticular" name="srch_perticular">
                                    <option value="">Vendor</option>
                           		</select>
							</div>   
                            
                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_fdate" id="srch_fdate" data-input-mask="99/99/9999" data-plugin-masked-input data-plugin-datepicker class="form-control datepicker" placeholder="Search From Date"  autocomplete="off">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_tdate" id="srch_tdate" data-input-mask="99/99/9999" data-plugin-masked-input data-plugin-datepicker class="form-control datepicker" placeholder="Search To Date"  autocomplete="off">                                     
                            </div>

                            <div class="col-md-4 text-right">
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-info reload-table" title="Search" onclick="srchMemWiseInv()"><i class="fa fa-search"></i></button> 	
							</div>
                        </div>
                   	</form>

					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<thead>
								<tr>
									<th>S.N.</th>
                                    <th>Member Type</th> 
                                    <th>Member</th>
                                    <th>No. Of Purchases</th> 
                                    <th>No. Of Sales</th> 
                                    <th>Total Purchase Amount</th>
                                    <th>Total Sales Amount</th>
                                    <th>Total Balance Amount</th>
								</tr>
							</thead>
							<tbody id="inv_table">
								
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	</div>
	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/inventory/memwise_invoice.js"></script>