<section role="main" class="content-body">
	<header class="page-header">
		<h2>Bank Master</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Masters</span></li>				
				<li><span>Bank &amp; A/C No.</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<!-- Starts of modals -->
	<div class="modal zoom-anim-dialog" data-backdrop="static" id="bankacc_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Bank A/C</h4>
				</div>
				<form method="post" id="add_bankacc_form" class="form-horizontal">
					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-3 control-label">Bank<span class="required">*</span></label>
							<div class="col-sm-9">
								<select data-plugin-selectTwo class="form-control " id="acc_bankid" name="acc_bankid" required>
									<option value="">Select Bank</option>
								</select>
								<input type="hidden" id="acc_id" name="acc_id" class="form-control " placeholder="Bank Id..." readonly/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Firm<span class="required">*</span></label>
							<div class="col-sm-9">
								<select data-plugin-selectTwo class="form-control " id="acc_firm" name="acc_firm" required>
									<option value="">Select Firm</option>
									<?php
										foreach ($firm as $f) {
											echo '<option value="'.$f['f_id'].'">'.strtoupper($f['f_name']).'</option>';
										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-3 control-label">A/C Number<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" id="acc_num" name="acc_num" class="form-control " placeholder="Account Number..." required />
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-3 control-label">Branch<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" id="acc_branch" name="acc_branch" class="form-control " placeholder="Enter Branch Name..." required />
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-3 control-label">IFSC Code<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" id="acc_ifsc" name="acc_ifsc" class="form-control " placeholder="Enter IFSC Code..." required />
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="submit_bankacc" class="btn btn-primary">Submit</button>
						<button type="button"  onclick="clearForm()" class="btn btn-warning">Clear</button>
						<button type="button" class="btn btn-danger"  data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>


	<div class="modal zoom-anim-dialog" data-backdrop="static" id="bank_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Bank</h4>
				</div>
				<form method="post" id="add_bank_form" class="form-horizontal">
					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-3 control-label">Bank<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control " id="bank_name" name="bank_name" placeholder="Enter Bank Name" required >
								<input type="hidden" id="bank_id" name="bank_id" class="form-control " placeholder="Bank Id..." readonly/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Bank Short Name<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control " id="bank_short" name="bank_short" placeholder="Enter Short Name" required >
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="submit_bank" class="btn btn-primary">Save</button>
						<button type="button" onclick="clearForm()" class="btn btn-warning">Clear</button>
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
	<div class="row <?php if($this->session->flashdata('message')=='')echo 'hide'; ?>">
		<div class="col-lg-12">
			<div class="alert alert-info alert-dismissable">
				<button type="button" class="close mini" data-dismiss="alert" aria-hidden="true">&times;</button>			
				<?php echo $this->session->flashdata('message') ; ?>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-7">
			<!-- Section 1 -->
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">						
						<a href="#" class="fa fa-caret-down"></a>
					</div>

					<h2 class="panel-title">Bank A/C Number Details </h2>
				</header>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-4">
							<select data-plugin-selectTwo class="form-control" id="srch_accbank">
								<option value="">Select Bank</option>
							</select>	
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" placeholder="Search By A/C Number" id="srch_accnum"> 
						</div>
						<div class="col-md-4 text-right">
							<button class="mb-xs mt-xs mr-xs btn btn-info reload-vd-table"><i class="fa fa-search"></i></button> 
							<a class="mb-xs mt-xs mr-xs btn btn-primary pull-right"  data-toggle="modal" data-target="#bankacc_modal" onclick="clearForm()" title="Add New Bank"><i class="fa fa-plus"></i></a>
						</div>
					</div>
					<div class="table-responsive">
						<table id="table_vddata" class="table table-bordered table-striped table-condensed mb-none">
							<thead>
								<tr>
									<th>S.N.</th>
									<th>Bank</th>
									<th>Firm</th>
									<th>A/C Number</th>
									<th>Branch</th>
									<th>IFSC</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody id="bankacc_table">
								
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
		<div class="col-md-5">
			<!-- Section 2 -->
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						
						<!-- <a href="#" class="fa fa-times"></a> -->
						
						<a href="#" class="fa fa-caret-down"></a>
					</div>

					<h2 class="panel-title">Bank </h2>
				</header>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-7">
							<input type="text" class="form-control" placeholder="Search By Bank" id="srch_bank"> 
						</div>
						<div class="col-md-5 text-right">
							<button class="mb-xs mt-xs mr-xs btn btn-info reload-vt-table"><i class="fa fa-search"></i></button> 
							<a class="mb-xs mt-xs mr-xs btn btn-primary pull-right"  data-toggle="modal" data-target="#bank_modal" onclick="clearForm()" title="Add New Type"><i class="fa fa-plus"></i></a>
						</div>
					</div>
					<div class="table-responsive">
						<table id="table_vtdata" class="table table-bordered table-striped table-condensed mb-none">
							<thead>
								<tr>
									<th>S.N.</th>
									<th>Bank</th>
									<th>Short Name</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody id="bank_table">
								
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	</div>
	


	

	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/masters/bank.js"></script>