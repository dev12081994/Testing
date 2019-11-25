<?php 
	 $url=$this->uri->segment(1).'/'.$this->uri->segment(2);
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.css" />
<!-- Theme CSS -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme.css" />
<!-- Skin CSS -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/skins/default.css" />


<section role="main" class="content-body">
	<header class="page-header">
		<h2>Purchase Details</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Inventory</span></li>				
				<li><span>Purchase</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<!-- Starts of modals-->

	<div class="modal zoom-anim-dialog" data-backdrop="static" id="addamt_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Sattle Amount</h4>
				</div>
				<form method="post" action="<?php echo base_url(); ?>/INVENTORY/sattleBill">
					<div class="modal-body">
						<div class="row">
							<div class="col-lg-12 cen">
								<label class="control-label" style="color:red" id="err_msg"></label>	
							</div> 
						</div>
						<div class="row">
							<div class="form-group col-lg-3">
								<label class="control-label">Select Date</label>
                                <input type="text" name="sattle_data" id="sattle_data" class="form-control mydatepicker" placeholder="Select Date" value="<?php echo date('d-m-Y'); ?>" onkeydown="event.preventDefault();" autocomplete="off">                                     
                            </div>
							<div class="col-lg-3">
	                            <label class="control-label">Remaining Amount</label>			
	                            <input type="hidden" name="inv_id" id="inv_id" placeholder="Invoice Id" class="form-control" autocomplete="off" readonly>					
	                            <input type="hidden" name="inv_for" id="inv_for" placeholder="Invoice For" class="form-control" autocomplete="off" value="2" readonly>			
	                            <input type="text" name="remain_amt" id="remain_amt" placeholder="Remaining Amount" class="form-control" autocomplete="off" readonly>
	                        </div>
	                        <div class="col-lg-3">
	                            <label class="control-label">Enter Amount<span class="required">*</span></label>			
	                            <input type="text" name="settle_amt" id="settle_amt" placeholder="Enter Paid Amount" class="form-control" required="true" autocomplete="off" onkeyup="calBal()">
	                        </div>
	                        <div class="col-lg-3">
	                            <label class="control-label">Balance Amount</label>			
	                            <input type="text" name="bal_amt" id="bal_amt" placeholder="Balance Amount" class="form-control" autocomplete="off" readonly>
	                        </div>
						</div>
						<div class="row">
							<div class="col-lg-3">
	                            <label class="control-label">Paymode<span class="required">*</span></label>			
	                            <select data-plugin-selectTwo class="form-control" id="acc_trantype" name="acc_trantype" onchange="showBankOpt()" required>
	                                <option value="">Select Type</option>
	                                <option value="1">Cash</option>
	                                <option value="2">Cheque</option>
	                                <option value="3">Online</option>
	                       		</select>
	                        </div>
	                        <div class="col-lg-3 comndiv hide">
	                            <label class="control-label chqdiv hide">Customer's Bank<span class="required">*</span></label>			
	                            <label class="control-label onldiv hide">Bank<span class="required">*</span></label>			
	                            <select data-plugin-selectTwo class="form-control comnctrl" id="acc_bankid" name="acc_bankid" onchange="getAccNo()">
	                                <option value="">Select Bank</option>
	                                <?php foreach ($bank_data as $key => $value) { ?>
	                                <option value="<?php echo $value['bank_id']; ?>">
	                                    <?php echo strtoupper($value['bank_name']); ?>
	                                </option>
	                                <?php } ?>
	                       		</select>
	                        </div>
	                        <div class="col-lg-3 onldiv hide">
	                            <label class="control-label">A/C Number<span class="required">*</span></label>			
	                            <select data-plugin-selectTwo class="form-control onlctrl" id="acc_bankaccid" name="acc_bankaccid">
	                                <option value="">Select A/C No.</option>
	                       		</select>
	                        </div>

	                        <div class="col-lg-3 chqdiv hide">
	                            <label class="control-label">Cheque No.<span class="required">*</span></label>
	                            <input type="text" class="form-control chqctrl" id="acc_chqno" name="acc_chqno" placeholder="Enter Cheque No." />
	                        </div>

							<div class="col-lg-3 chqdiv hide">
	                            <label class="control-label">Cheque Date<span class="required">*</span></label>
	                            <input type="text" id="acc_chqdt" name="acc_chqdt" class="form-control mydatepicker chqctrl" placeholder="dd-mm-yyyy" onkeydown="event.preventDefault();" autocomplete="off"/>
	                        </div>

	                        <div class="col-lg-3 onldiv hide">
	                            <label class="control-label">Transaction ID<span class="required">*</span></label>
	                            <input type="text" class="form-control onlctrl" id="acc_onlineid" name="acc_onlineid" placeholder="Enter Transaction Id" />
	                        </div>
	                        <div class="col-lg-9 remark-div">
	                            <label class="control-label">Remark<span class="required">*</span></label>			
	                            <input type="text" class="form-control" id="pay_remark" name="pay_remark" placeholder="Enter Remark">
	                        </div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" id="submit" class="btn btn-primary">Save</button>
						<button type="reset" onclick="clearForm()" class="btn btn-warning">Clear</button>
						<button type="button" class="btn btn-danger"  data-dismiss="modal">Close</button>
					</div>				
				</form>
			</div>
		</div>
	</div>
	
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
	
	<div class="modal zoom-anim-dialog" data-backdrop="static" id="payhist_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Payment History Details Of Invoice No. <span id="hist_invid"></span></h4>
				</div>
				
				<div class="modal-body">
					<form method="post" target="_blank" action="<?php echo base_url(); ?>REPORTS/printReceipt">
						<div class="row">
							<div class="col-md-12">
								<button type="submit" class="btn btn-info btn-sm pull"><i class="fa fa-print"></i> Print</button>
							</div>
						</div>
						<div class="row">	
							<div class="col-sm-12">
								<div class="table-responsive">								
									<input type="hidden" name="location" value="<?php echo $url; ?>">
									<table class="table table-bordered table-striped table-condensed mb-none">
										<thead>
											<tr>
												<th>S.N.</th>
				                                <th>Entry Date</th>
				                                <th>Date</th>
				                                <th>Amount</th>
				                                <th>Mode</th>
				                                <th>Bank</th>  
				                                <th>A/C</th>  
				                                <th>Cheque No.</th>  
				                                <th>Cheque Date</th>  
				                                <th>Transaction Id</th>  
				                                <th>Remark</th>  
				                                <th>Check</th>  
											</tr>
										</thead>
										<tbody id="payhist_table">
											
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</form>
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
                            	<input type="hidden" name="srch_invfor" id="srch_invfor" value="1">
								<select data-plugin-selectTwo class="form-control " id="srch_vendor" name="srch_vendor">
                                    <option value="">Vendor</option>
									<?php foreach ($vendor as $key => $value) { echo "<option value='".$value['c_id']."'>".ucwords(strtolower($value['vendor']))."</option>";
									} ?>
                           		</select>
							</div>                             

                            <div class="form-group col-lg-2">
                                <input type="text" name="inv_billno" id="inv_billno" class="form-control" placeholder="Invoice Number">
                            </div>
                            
                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_fdate" id="srch_fdate" class="form-control mydatepicker" placeholder="From Date" onkeydown="event.preventDefault();" autocomplete="off">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_tdate" id="srch_tdate" class="form-control mydatepicker" placeholder="To Date">                                     
                            </div>

                            <div class="col-md-2 text-right">
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-info reload-table" title="Search" onclick="srchPurchase()"><i class="fa fa-search"></i></button> 
								<a class="mb-xs mt-xs mr-xs btn btn-primary pull-right" data-toggle="tooltip" data-original-title="Add New Member" data-placement="top" href="<?php echo base_url() ?>INVENTORY/addPurchaseBill"><i class="fa fa-plus"></i></a>
							</div>
                        </div>
                   	</form>
                   	<div class="row">
	                   	<div class="col-md-12">
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
	                	</div>
	                </div>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<thead>
								<tr>
									<th>S.N.</th>
                                    <th>Date</th> 
                                    <th>Invoice No.</th> 
                                    <th>Vendor</th>
                                    <th>Bill Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Balance Amount</th>
                                    <th>Sattle Bill</th>
                                    <th class="h">Payment History</th>  
                                    <th class="h">Details</th> 
                                    <th class="h">View</th>  
                                    <th class="h" hidden>Edit</th>  
                                    <th class="h">Delete</th>  
								</tr>
							</thead>
							<tbody id="purchase_table">
								
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	</div>
	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/inventory/purchase.js"></script>