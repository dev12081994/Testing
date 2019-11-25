<?php 

foreach ($inv_data as $inv) {}

$prod_opt='<option value="">Select Product</option>';
foreach ($prod_data as $inv) 
{
	$prod_opt.="<option value='".$inv['prod_id']."'>".ucwords(strtolower($inv['prod_name']))."</option>";
}

?>

<section role="main" class="content-body">
	<header class="page-header">
		<h2>Add/Update Sales Invoice</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Inventory</span></li>				
				<li><span>Sales</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<div class="row">
		<div class="col-md-12">
				<!-- Modal Form Start -->

			<div class="modal zoom-anim-dialog" data-backdrop="static" id="addmember_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title" id="myModalLabel">Memebr Registration</h4>
						</div>
						<form method="post" id="add_member_form" class="form-horizontal">
							<div class="modal-body">
								<div class="form-group">
									<label class="col-sm-3 control-label">Member Type<span class="required">*</span></label>
									<div class="col-sm-9">
										<input type="hidden" id="c_id" name="c_id" class="form-control" placeholder="GST Registration Id..." readonly/>
										<select data-plugin-selectTwo class="form-control" id="c_type" name="c_type" required>
	                                        <option value="" >Member Type</option>
	                                        <option value="1" >Customer</option>
	                                        <option value="2" >Vendor</option>
	                                        <option value="3" >Employee</option>
	                                        <option value="4" >Other</option>
	                                    </select>										
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Salutaion<span class="required">*</span></label>	
									<div class="col-sm-9">
		                                <select name="c_salutation" id="c_salutation" 
		                                class="form-control" required>
		                                    <option value="">Select</option>	
		                                    <option value="Mr." <?php if(isset($cus['c_salutation']) && $cus['c_salutation']=='Mr.') echo 'selected'; ?>>Mr.</option>	
		                                    <option value="Mrs." <?php if(isset($cus['c_salutation']) && $cus['c_salutation']=='Mrs.') echo 'selected'; ?>>Mrs.</option>			
		                                    <option value="Miss." <?php if(isset($cus['c_salutation']) && $cus['c_salutation']=="Miss.") echo 'selected'; ?>>Miss.</option>
		                                </select>
	                              	</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Fisrt Name<span class="required">*</span></label>
									<div class="col-sm-9">
										<input type="text" id="c_firstname" name="c_firstname" class="form-control" placeholder="First Name..." required />				
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Middle Name<span class="required">*</span></label>
									<div class="col-sm-9">
										<input type="text" id="c_middlename" name="c_middlename" class="form-control" placeholder="Enter Middle Name..." />				
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Last Name<span class="required">*</span></label>
									<div class="col-sm-9">
										<input type="text" id="c_lastname" name="c_lastname" class="form-control" placeholder="Enter Last Name..." required/>				
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Contact<span class="required">*</span></label>
									<div class="col-sm-9">
										<input type="text" id="c_mob1" name="c_mob1" class="form-control" placeholder="First Name..." required />				
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" id="add_member" class="btn btn-primary">Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<!-- Modal Form End -->

			<!-- Section 1 -->
			<section class="panel">
				<header class="panel-heading hide">
					<div class="panel-actions">						
						<!-- <a href="#" class="fa fa-times"></a> -->						
						<a href="#" class="fa fa-caret-down"></a>
					</div>

					<h2 class="panel-title">Members Details </h2>
				</header>

				<div class="panel-body">

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
					
					<div id="prod_opt" class="hide">
						<?php echo $prod_opt; ?>
					</div>

					<form method="post" id="add_prod_form" class="form-horizontal" action="<?php echo base_url() ?>INVENTORY/saveSalesBill">
						<div class="modal-body">
							<h4>Invoice Details :</h4>	
							<div class="form-group row">

								<div class="col-sm-3">
									<label class="control-label">Firm<span class="required">*</span></label>
																		
                    				<select data-plugin-selectTwo class="form-control" id="inv_firmid" name="inv_firmid"  required>
                                        <option value="">Select Firm</option>
                                        <?php foreach ($firm_data as $firm) {
                                        	echo "<option value='".$firm['f_id']."'>".strtoupper($firm['f_name'])."</option>";
                                        } ?>
                                    </select>							
								</div>
								<div class="col-lg-3">
	                                <label class="control-label">Invoice Date<span class="required">*</span></label>	
	                                <input type="text" name="inv_date" id="inv_date" class="form-control mydatepicker empopt" onkeydown="event.preventDefault();" placeholder="dd-mm-yyyy"  required autocomplete="off">
	                            </div>							

								<div class="col-sm-3">
									<label class="control-label">Customer Type<span class="required">*</span></label>
									<select data-plugin-selectTwo class="form-control " id="member_type" name="member_type" onchange="fetchMembers()" required>
                                        <option value="">Select Type</option>
                                        <?php foreach ($this->config->item('member_type') as $key => $value) {
                                        echo '<option value="'.$key.'">'.$value.'</option>';
                                        } ?>
                                        <option value="<?php echo $key+1; ?>">Project</option>
                                    </select>									
								</div>

								<div class="col-sm-3">
									<label class="control-label">Customer<span class="required">*</span></label>
									<div class="input-group">										
	                    				<select data-plugin-selectTwo class="form-control" id="inv_perticular" name="inv_perticular"  required>
	                                        <option value="">Select Customer</option>
	                                    </select>
										<span class="input-group-addon" style="cursor:pointer;"  onclick="addMember()" title="Add New Registration Type">
											<i class="fa fa-plus"></i>
										</span>
									</div>
									<input type="hidden" name="prod_ids" id="prod_ids" class="form-control" placeholder="Row Ids..." readonly>									
								</div>

								<div class="col-sm-3">
									<label class="control-label">Purchase Location<span class="required">*</span></label>
									<select class="form-control" id="inv_location" name="inv_location" required>
										<option value="1">In City</option>
										<option value="2">Out Of City</option>
									</select>
								</div>

								<div class="col-sm-6">
									<label class="control-label">Any Remark<span class="required">*</span></label>
									<input type="text" class="form-control" id="inv_remark" name="inv_remark" placeholder="Enter Remark">
								</div>
								<div class="col-sm-3">
									<label class="control-label">Any Transportation Charge<span class="required">*</span></label>
									<input type="text" class="form-control onlynumval" id="inv_transportcharge" name="inv_transportcharge" placeholder="Enter Amount" onkeyup="resetSeq()">
								</div>
								<div class="col-sm-2 hide">
									<label class="control-label">Reverse Calculation</label>
									<input type="checkbox" id="stk_revcal" class="form-control" onclick="reverseCal()" />
								</div>
							</div>														
							
							<div class="row">
								<div class="col-lg-3">
									<h4>Product Details :</h4>
								</div>
								<div class="col-lg-9">
									<span class="no-data-found" id="err_msg"></h4>
								</div>
							</div>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-condensed mb-none" width="100%">
									<thead>
										<tr>
											<th width="2%">S.N.</th>
		                                    <th width="12%">Product</th> 
		                                    <th width="4%">Unit</th> 
		                                    <th width="8%">Quantity</th>
		                                    <th width="9%">Rate</th>	                                    
		                                    <th width="10%">Amount</th>
		                                    <th width="9%">Discount</th>
		                                    <th width="8%">Discount In</th>
		                                    <th width="9%">GST(%)</th>
		                                    <th width="5%">Inclusive</th>
		                                    <th width="10%">Total</th>
		                                    <th width="9%">Exp.</th>
		                                    <th width="3%"></th>  
										</tr>
									</thead>
									<tbody id="product_table">
										
									</tbody>
								</table>
							</div>
							<div class="row">
								<div class="col-lg-8">
									
								</div>
								<div class="col-lg-4">
									<table class="table table-bordered table-striped table-condensed mb-none" width="100%">
										<thead>
											<tr>
												<th width="50%">Gross</th>
			                                    <th width="50%">
			                                    	<span style="float:right;padding-right: 10;" id="gross">0</span>
			                                    </th>
											</tr>
											<tr>
												<th width="50%">Discount</th>
			                                    <th width="50%">
			                                    	<span style="float:right;padding-right: 10;" id="disc">0
			                                    	</span>
			                                    </th>
											</tr>
											<tr>
												<th width="50%">GST Amount</th>
			                                    <th width="50%">
			                                     	<span style="float:right;padding-right: 10;" id="gst">0</span>
			                                 	</th>
											</tr>
											<tr>
												<th width="50%">Sub Total</th>
			                                    <th width="50%"><span style="float:right;padding-right: 10;" id="subtot">0</span></th>
											</tr>
											<tr>
												<th width="50%">Round Off</th>
			                                    <th width="50%"><input type="tex" name="inv_roundoff" id="inv_roundoff" placeholder="Enter Amount" class="form-control" value="0" onkeyup="calRoundOff()"></th>
											</tr>
											<tr>
												<th width="50%" title="Transportation Charge">Transportation</th>
			                                    <th width="50%"><span style="float:right;padding-right: 10;" id="transport_chrg">0</span></th>
											</tr>
											<tr>
												<th width="50%">
													Net Amount
													<input type="hidden" class="form-control" id="bkpnetamt" readonly >
												</th>
			                                    <th width="50%">
			                                    	<span style="float:right;padding-right: 10;" id="netamt">0</span>
		                                    	</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
		                    <div class="row hide">
		                    	<div class="col-lg-12">
		                            <label class="control-label">Address</label><br/>                             
		                            <textarea name="c_address" id="c_address" class="form-control" placeholder="Enter Address" row="1"><?php if(isset($inv['c_address'])) echo $inv['c_address']; ?></textarea>
		                        </div>
		                    </div>

						</div>

						 
						<div class="modal-footer">
							<button type="button" onclick="addMoreItem(),resetSeq()" class="btn btn-success pull-left"><i class="fa fa-plus"></i>&nbsp;Add More Item</button>
							<button type="submit" id="submit_prod" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;Submit</button>
							<button type="reset" class="btn btn-danger"><i class="fa fa-refresh "></i>&nbsp;Clear</button>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
	<!-- end: page -->
</section>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/inventory/add_salesbill.js"></script>