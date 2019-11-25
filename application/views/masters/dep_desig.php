<section role="main" class="content-body">
	<header class="page-header">
		<h2>Department && Designation Master</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Masters</span></li>				
				<li><span>Department && Designation</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<!-- Starts of modals-->
	<div class="modal zoom-anim-dialog" data-backdrop="static" id="desig_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Add/Update Designation</h4>
				</div>
				<form method="post" id="add_desig_form" class="form-horizontal">
					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-3 control-label">Department<span class="required">*</span></label>
							<div class="col-sm-9">
								<select data-plugin-selectTwo class="form-control " id="desig_depid" name="desig_depid" required>
									<option value="">Select Department</option>
								</select>
								<input type="hidden" id="desig_id" name="desig_id" class="form-control" placeholder="Vechicle Id..." readonly/>
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-3 control-label">Designation </label>
							<div class="col-sm-9">
								<input type="text" name="desig_name" id="desig_name" class="form-control" placeholder="Enter Designation Name..." required />
							</div>
						</div>						
					</div>
					<div class="modal-footer">
						<button type="button" id="submit_desig" class="btn btn-primary">Submit</button>
						<button type="button"  onclick="clearForm()" class="btn btn-warning">Clear</button>
						<button type="button" class="btn btn-danger"  data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>


	<div class="modal zoom-anim-dialog" data-backdrop="static" id="dep_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Department</h4>
				</div>
				<form method="post" id="add_dep_form" class="form-horizontal">
					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-3 control-label">Department Name<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="dep_name" name="dep_name" placeholder="Enter Department Name" required>
								<input type="hidden" id="dep_id" name="dep_id" class="form-control " placeholder="Vechicle Id..." readonly/>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="submit_dep" class="btn btn-primary">Save</button>
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
		<div class="col-md-8">
			<!-- Section 1 -->
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						
						<!-- <a href="#" class="fa fa-times"></a> -->
						
						<a href="#" class="fa fa-caret-down"></a>
					</div>

					<h2 class="panel-title">Designation Details </h2>
				</header>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-4">
							<select data-plugin-selectTwo class="form-control" id="srch_desigdep" required>
								<option value="">Select Department</option>
							</select>	
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" placeholder="Search By Number" id="srch_desig"> 
						</div>
						<div class="col-md-4 text-right">
							<button class="mb-xs mt-xs mr-xs btn btn-info reload-desig-table"><i class="fa fa-search"></i></button> 
							<a class="mb-xs mt-xs mr-xs btn btn-primary pull-right" data-toggle="modal" data-target="#desig_modal" onclick="clearForm()" title="Add New Vechicle"><i class="fa fa-plus"></i></a>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<thead>
								<tr>
									<th>S.N.</th>
									<th>Department</th>
									<th>Designation</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody id="desig_table">
								
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
		<div class="col-md-4">
			<!-- Section 2 -->
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						
						<!-- <a href="#" class="fa fa-times"></a> -->
						
						<a href="#" class="fa fa-caret-down"></a>
					</div>

					<h2 class="panel-title">Department Details</h2>
				</header>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-7">
							<input type="text" class="form-control" placeholder="Search By Type" id="srch_dep"> 
						</div>
						<div class="col-md-5 text-right">
							<button class="mb-xs mt-xs mr-xs btn btn-info reload-dep-table"><i class="fa fa-search"></i></button> 
							<a class="mb-xs mt-xs mr-xs btn btn-primary pull-right"  data-toggle="modal" data-target="#dep_modal" onclick="clearForm()" title="Add New Type"><i class="fa fa-plus"></i></a>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<thead>
								<tr>
									<th>S.N.</th>
									<th>Department</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody id="dep_table">
								
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	</div>	

	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/masters/dep_desig.js"></script>