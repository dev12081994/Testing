<section role="main" class="content-body">
	<header class="page-header">
		<h2>Firm Master</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Masters</span></li>
				<li><span>Firm</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<!-- Starts of modals-->
	<div class="modal zoom-anim-dialog" data-backdrop="static" id="categ_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Firm</h4>
				</div>
				<form method="post" id="add_cat_form" enctype="multipart/form-data" action="<?php echo base_url(); ?>MASTERS/saveFirm" class="form-horizontal">
					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-3 control-label">Firm Name<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" id="f_name" name="f_name" class="form-control" placeholder="Enter Firm Name..." required/>
								<input type="hidden" id="f_id" name="f_id" class="form-control" placeholder="Firm Id..." readonly/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Firm Contact<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" id="f_contact" name="f_contact" class="form-control" placeholder="Enter Contact Number..." required/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Firm Email</label>
							<div class="col-sm-9">
								<input type="text" id="f_email" name="f_email" class="form-control" placeholder="Enter Email Id..." />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">GSTIN/UIN</label>
							<div class="col-sm-9">
								<input type="text" id="f_gstin" name="f_gstin" class="form-control" placeholder="Enter GSTIN/UIN..." />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">PAN<span class="required"></span></label>
							<div class="col-sm-9">
								<input type="text" id="f_pan" name="f_pan" class="form-control" placeholder="Enter PAN..." />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Firm Logo</label>
							<div class="col-sm-9">
								<input type="file" id="f_logo" name="f_logo" class="form-control" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Bank Name</label>
							<div class="col-sm-9">
								<select name="f_bank" id="f_bank" data-plugin-selectTwo class="form-control"  onchange="getAccNo()">
                                    <option value="">Select Bank</option>
                                    <?php foreach ($bank_data as $key => $value) { ?>
                                    <option value="<?php echo $value['bank_id']; ?>">
                                        <?php echo strtoupper($value['bank_short']); ?>
                                    </option>
                                    <?php } ?>
                                </select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Bank A/C</label>
							<div class="col-sm-9">
								<select data-plugin-selectTwo class="form-control comnctrl" id="f_bankacc" name="f_bankacc">
                                    <option value="">Select A/C No.</option>
                           		</select>
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-3 control-label">Address<span class="required">*</span></label>
							<div class="col-sm-9">
								<textarea rows="1" id="f_address" name="f_address" class="form-control" placeholder="Enter Address..." required="true"></textarea>
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-3 control-label">State<span class="required">*</span></label>
							<div class="col-sm-9">                 
	                            <select name="f_state" id="f_state" data-plugin-selectTwo class="form-control" required>
	                                <option value="">Select State</option>  
	                                <?php foreach ($statedata as $key => $value) { ?>
	                                <option value="<?php echo $value['state_id']; ?>" <?php if($value['state_id']==19)echo "selected"; ?>><?php echo ucwords(strtolower($value['state_name'])); ?></option>         
	                                <?php } ?>      
	                            </select>
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
		<header class="panel-heading">
			<div class="panel-actions">
				
				<!-- <a href="#" class="fa fa-times"></a> -->
				
				<a href="#" class="fa fa-caret-down"></a>
			</div>

			<h2 class="panel-title">Firm Details </h2>
		</header>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-3">
					<input type="text" class="form-control" placeholder="Search By Firm" id="srch_firm"> 
				</div>
				<div class="col-md-9 text-right">
					<button class="mb-xs mt-xs mr-xs btn btn-info reload-table">Search</button> 
					<a class="mb-xs mt-xs mr-xs btn btn-primary pull-right reset" onclick="clearForm()" data-toggle="modal" data-target="#categ_modal">Add New</a>
				</div>
			</div>

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
			<div class="table-responsive">
				<table id="table_data" class="table table-bordered table-striped table-condensed mb-none">
					<thead>
						<tr>
							<th>S.N.</th>
							<th>Firm</th>
							<th>Contact</th>
							<th>Email Id</th>
							<th>GSTIN/UIN</th>
							<th>PAN</th>
							<th>Bank</th>
							<th>A/C</th>
							<th>State</th>
							<th>Address</th>
							<th>Logo</th>
							<th>Edit</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody id="cat_table">
						
					</tbody>
				</table>
			</div>
		</div>
	</section>x
	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/masters/firm.js"></script>