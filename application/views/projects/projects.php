<section role="main" class="content-body">
	<header class="page-header">
		<h2>Project Details</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Projects</span></li>
				<li><span>Projects</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<!-- Starts of modals-->




	<div class="modal zoom-anim-dialog" data-backdrop="static" id="project_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Add Project Details</h4>
				</div>
				<form method="post" id="add_proj_form" class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url(); ?>PROJECTS/saveProjects">
					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-3 control-label">Project Type<span class="required">*</span></label>
							<div class="col-sm-9">
								<select name="proj_type" id="proj_type" class="form-control" required>
									<option value="">Select Type</option>
									<option value="1">Govt.</option>
									<option value="2">Private</option>
								</select>
								<input type="hidden" id="proj_id" name="proj_id" class="form-control" placeholder="Project Id..." readonly/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Project Name<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" id="proj_name" name="proj_name" class="form-control" placeholder="Enter Project Name..." required/>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Duration In<span class="required">*</span></label>
							<div class="col-sm-9">
								<select name="proj_durtype" id="proj_durtype" onchange="setRequire()" class="form-control" required>
									<option value="">Select Type</option>
									<option value="1">Date</option>
									<option value="2">Duration</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Project Start Date<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" id="proj_startdt" name="proj_startdt" class="form-control mydatepicker" placeholder="dd-mm-yyyy" required onkeydown="event.preventDefault();" autocomplete="off" />
							</div>
						</div>
						<div class="form-group dtdiv"  style="display:none;">
							<label class="col-sm-3 control-label">Project End Date<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" id="proj_enddt" name="proj_enddt" class="form-control mydatepicker dtctrl" placeholder="dd-mm-yyyy" onkeydown="event.preventDefault();" autocomplete="off" />
							</div>
						</div>


						<div class="form-group durdiv" style="display:none;">
							<label class="col-sm-3 control-label">Duration Type<span class="required">*</span></label>
							<div class="col-sm-9">
								<select id="proj_durin" name="proj_durin" class="form-control durctrl">
									<option value="">Select Type</option>
									<option value="1">Days</option>
									<option value="2">Month</option>
									<option value="3">Year</option>
								</select>
							</div>
						</div>

						
						<div class="form-group durdiv" style="display:none;">
							<label class="col-sm-3 control-label">Duration<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control durctrl" id="proj_duration" name="proj_duration" placeholder="Enter Duration value" autocomplete="off">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Project Amount<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" id="proj_amt" name="proj_amt" class="form-control" placeholder="Enter Project Amount..." required/>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Upload Docs</label>
							<div class="col-sm-9">
								<input type="file" name="proj_docs" id="proj_docs" class="form-control" />
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-3 control-label">Remark</label>
							<div class="col-sm-9">
								<textarea rows="1" id="proj_remark" name="proj_remark" class="form-control" placeholder="Type Remark..."></textarea>
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

	<div id="done_proj" class="modal-block modal-block-primary mfp-hide">
		<section class="panel">
			<div class="panel-body text-center">
				<div class="modal-wrapper">
					<div class="modal-icon center">
						<i class="fa fa-question-circle"></i>
					</div>
					<div class="modal-text">
						<input type="hidden" id="projid_done" class="form-control" readonly="true">
						<h4>Are you sure?</h4>
						<p>Has This Project Been Completed?</p>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button class="btn btn-primary" onclick="doneProject()">Confirm</button>
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
	
	<section class="panel">
		<div class="panel-body">
			<form name="srchproj_form" method="post" id="srchproj_form" action="<?php echo base_url("PROJECTS/srchProject"); ?>">
				<div class="row">
					<div class="col-md-3">
						<select name="srch_projtype" id="srch_projtype" class="form-control">
							<option value="">Select Type</option>
							<option value="1">Govt.</option>
							<option value="2">Private</option>
						</select>
					</div>
					<div class="col-md-3">
						<input type="text" class="form-control" placeholder="Search Project Name" id="srch_projname"> 
					</div>
					<div class="col-md-3">
						<input type="text" class="form-control" placeholder="Search Project Amount" id="srch_projamt"> 
					</div>
					<div class="col-md-3 text-right">
						<button type="button" onclick="srchProject()" class="mb-xs mt-xs mr-xs btn btn-info reload-table" title="Search"><i class="fa fa-search"></i></button> 
						
						<button class="mb-xs mt-xs mr-xs btn btn-success pull-right" title="Export To Excel" name="exportbtn" id="exportbtn"><i class="fa fa-download"></i></button>

						<a class="mb-xs mt-xs mr-xs btn btn-primary pull-right reset" onclick="clearForm()" data-toggle="modal" data-target="#project_modal" title="Add New"><i class="fa fa-plus"></i></a>
					</div>
				</div>
			</form>
			<div class="table-responsive"  id="proj_table">
				
			</div>
		</div>
	</section>
	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/projects/projects.js"></script>