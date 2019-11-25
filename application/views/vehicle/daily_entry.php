
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.css" />
<!-- Theme CSS -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme.css" />
<!-- Skin CSS -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/skins/default.css" />

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
				<li><span>Vehicle</span></li>
				<li><span>Daily Running Details</span></li>
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
				<form method="post" action="<?php echo base_url(); ?>VEHICLE/receiveVehicleAmt">
					<div class="modal-body">
						<div class="row">
							<div class="col-lg-12 cen">
								<label class="control-label" style="color:red" id="err_msg"></label>	
							</div> 
						</div>
						<div class="row">
							<div class="form-group col-lg-3">
								<label class="control-label">Select Date</label>
                                <input type="text" name="sattle_data" id="sattle_data" class="form-control mydatepicker" placeholder="Search To Date" value="<?php echo date('d-m-Y'); ?>" onkeydown="event.preventDefault();" autocomplete="off">                                     
                            </div>
							<div class="col-lg-3">
	                            <label class="control-label">Remaining Amount</label>			
	                            <input type="hidden" name="inv_id" id="inv_id" placeholder="Invoice Id" class="form-control" autocomplete="off" readonly>					
	                            <input type="hidden" name="inv_for" id="inv_for" placeholder="Invoice For" class="form-control" autocomplete="off" readonly>			
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

	<div class="modal zoom-anim-dialog" data-backdrop="static" id="vehicle_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Add Vehicle Running Details</h4>
				</div>
				<form method="post" id="add_vrun_form" class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url(); ?>VEHICLE/saveVehicles">
					<div class="modal-body">
										
						<div class="row" style="margin-top:0px !important; ">
							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Date<span class="required">*</span></label>
									<input type="text" id="vrun_date" name="vrun_date" class="form-control mydatepicker" placeholder="dd-mm-yyyy" onkeydown="event.preventDefault();" autocomplete="off" required/>

									<input type="hidden" id="vrun_id" name="vrun_id" class="form-control" placeholder="Vehicle Details Id..." readonly/>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Vehicle Type<span class="required">*</span></label>
									<select name="vrun_vtype" id="vrun_vtype" class="form-control" onchange="fetchVehicle()" required>
										<option value="">Select Type</option>
										<?php foreach ($vehi_type as $key => $value) {
											echo "<option value='".$value['vt_id']."'>".strtoupper($value['vt_name'])."</option>";
										} ?>
									</select>
								</div>
							</div>

							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Vehicle<span class="required">*</span></label>
									<select name="vrun_vid" id="vrun_vid" class="form-control" required>
										<option value="">Select Vehicle</option>
									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Running Status<span class="required">*</span></label>
									<select name="vrun_runstatus" id="vrun_runstatus" class="form-control" required>
										<option value="">Select Status</option>
										<option value="1">Running</option>
										<option value="2">Stop</option>
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Meter Start Value</label>
									<input type="text" name="vrun_meterstart" id="vrun_meterstart" placeholder="Enter From Location" class="form-control">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Meter Stop Value</label>
									<input type="text" name="vrun_meterstop" id="vrun_meterstop" placeholder="Enter To Location" class="form-control">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Customer Type<span class="required">*</span></label>
									<select name="vrun_memtype" id="vrun_memtype" class="form-control no-run" onchange="fetchMembers()" required>
										<option value="">Select Type</option>
										<?php foreach ($this->config->item('member_type') as $key => $value) {
											echo "<option value='".$key."' class='no-run'>".strtoupper($value)."</option>";
										} ?>
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Customer<span class="required">*</span></label>
									<select name="vrun_memid" id="vrun_memid" class="form-control no-run" data-plugin-selectTwo required>
										<option value="">Select Customer</option>
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Driver<span class="required">*</span></label>
									<select name="vrun_drivid" id="vrun_drivid" class="form-control no-run" data-plugin-selectTwo required>
										<option value="">Select Driver</option>
										<?php foreach ($driver_list as $key => $value) {
											echo "<option value='".$value['c_id']."'>".ucwords(strtolower($value['c_name']))."</option>";
										} ?>
									</select>
								</div>
							</div>
							
						</div>

						<div class="row">
							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Work<span class="required">*</span></label>
									<input type="text" id="vrun_work" name="vrun_work" class="form-control no-run requir" placeholder="Type Work" required/> 
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">From<span class="required">*</span></label>
									<input type="text" name="vrun_from" id="vrun_from" placeholder="Enter From Location" class="form-control no-run requir" required>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">To<span class="required">*</span></label>
									<input type="text" name="vrun_to" id="vrun_to" placeholder="Enter To Location" class="form-control no-run requir" required>
								</div>
							</div>
							
						</div>

						<div class="row">
							
							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Quantity<span class="required">*</span></label>
									<div class="row">
									<div class="col-sm-6">
										<select class="form-control requir" name="vrun_qtytype" id="vrun_qtytype" required>
										<option value="">Type</option>
										<?php 
										foreach ($vehi_qtytype as $qty_value) {
											echo "<option value='".$qty_value['vqt_id']."'>".$qty_value['vqt_name']."</option>";
										}
										?>
									</select>	
									</div>
									<div class="col-sm-6">
										<input type="text" name="vrun_qty" id="vrun_qty" placeholder="Enter Quantity" class="form-control no-run requir onlynumval" onkeyup="calAmt()" required autocomplete="off">
									</div>
									
									</div>
								</div>
							</div>

							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Rate<span class="required">*</span></label>
									<input type="text" onkeyup="calAmt()" name="vrun_rate" id="vrun_rate" placeholder="Enter Rate" class="form-control no-run requir onlynumval" required autocomplete="off">
								</div>
							</div>

							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Amount</label>
									<input type="text" name="vrun_fareamt" id="vrun_fareamt" placeholder="Enter Amount" readonly class="form-control" autocomplete="off">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Upload Docs</label>
									<input type="file" name="vrun_docs" id="vrun_docs" class="form-control no-run" />
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group1">
									<label class="control-label">Remark</label>
									<textarea rows="2" id="vrun_remark" name="vrun_remark" class="form-control no-run" placeholder="Type Remark..."></textarea>
								</div>
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
						<p>Are you sure that you want to delete this record?</p>
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
	
	
	<section class="panel">
		<div class="panel-body">
			<form name="srchvrun_form" id="srchvrun_form">
				<div class="row <?php if($this->session->flashdata('message')=='')echo 'hide'; ?>">
					<div class="col-lg-12">
						<div class="alert alert-info alert-dismissable">
							<button type="button" class="close mini" data-dismiss="alert" aria-hidden="true">&times;</button>			
							<?php echo $this->session->flashdata('message') ; ?>
						</div>
					</div>
				</div>
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
						<input type="text" class="form-control" placeholder="Search Vehicle No." id="srch_vnum" name="srch_vnum"> 
					</div>


					<div class="col-md-2">
						<input type="text" id="srch_fdate" name="srch_fdate" class="form-control mydatepicker" placeholder="From Date" required/>
					</div>

					<div class="col-md-2">
						<input type="text" id="srch_tdate" name="srch_tdate" class="form-control mydatepicker" placeholder="To Date" required/>
					</div>

					<div class="col-md-2">
						<input type="text" class="form-control" placeholder="Search Customer" id="srch_cus" name="srch_cus"> 
					</div>
					<div class="col-md-2">
						<input type="text" class="form-control" placeholder="Search Driver" id="srch_driver" name="srch_driver"> 
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
						<a class="mb-xs mt-xs mr-xs btn btn-primary pull-right reset" onclick="clearForm()" data-toggle="modal" data-target="#vehicle_modal" title="Add New"><i class="fa fa-plus"></i></a>
					</div>
				</div>
			</form>
			<div class="row">
				<div class="col-md-12" id="vrun_table">
					
				</div>
			</div>
		</div>
	</section>
	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/vehicle/daily_entry.js"></script>