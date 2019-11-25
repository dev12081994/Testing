

<section role="main" class="content-body">
	<header class="page-header">
		<h2>Sub Category Master</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Masters</span></li>
				<li><span>Product Category</span></li>
				<li><span>Sub Category</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<!-- Starts of modals-->

	<div class="modal zoom-anim-dialog" data-backdrop="static" id="categ_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close reload-table" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Sub Category</h4>
				</div>
				<form method="post" id="add_subcat_form" class="form-horizontal">
					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-3 control-label">Category<span class="required">*</span></label>
							<div class="col-sm-9">
								<select data-plugin-selectTwo class="form-control populate" id="sc_category" name="sc_category" required>
									<option value="">Select Category</option>	
									<?php foreach ($category as $key => $value) { echo "<option value='".$value['c_id']."'>".ucwords(strtolower($value['c_name']))."</option>";
									} ?>		
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Sub Category<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" name="sc_name" id="sc_name" class="form-control " placeholder="Enter Category Name..." required/>
								<input type="hidden" name="sc_id" id="sc_id" class="form-control" placeholder="Category Id..." readonly/>
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-3 control-label">Remark</label>
							<div class="col-sm-9">
								<textarea rows="5" id="sc_remark" name="sc_remark" class="form-control" placeholder="Type Remark..."></textarea>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="submit" class="btn btn-primary">Save</button>
						<button type="reset" id="reset" class="btn btn-warning">Clear</button>
						<button type="button" class="btn btn-danger reload-table"  data-dismiss="modal">Close</button>
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
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				
				<!-- <a href="#" class="fa fa-times"></a> -->
				
				<a href="#" class="fa fa-caret-down"></a>
			</div>

			<h2 class="panel-title">Category Details </h2>
		</header>

		<div class="panel-body">
			<div class="row">
				<div class="col-md-3">
					<select id="srch_cat" data-plugin-selectTwo class="form-control populate">
						<option value="">Search By Category</option>
						<?php foreach ($category as $key => $value) { echo "<option value='".$value['c_id']."'>".ucwords(strtolower($value['c_name']))."</option>";
									} ?>
					</select>
				</div>
				<div class="col-md-3">
					<input type="text" class="form-control" placeholder="Search By Sub Category" id="srch_subcat"> 
				</div>
				<div class="col-md-6">
					<button class="mb-xs mt-xs mr-xs btn btn-info reload-table">Search</button> 
					<a class="mb-xs mt-xs mr-xs btn btn-primary pull-right"  data-toggle="modal" data-target="#categ_modal">Add New</a>
				</div>
			</div>

			<div class="table-responsive">
				<table class="table table-bordered table-striped table-condensed mb-none">
					<thead>
						<tr>
							<th>S.N.</th>
							<th>Category</th>
							<th>Sub Category</th>
							<th>Remark</th>
							<th>Edit</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody id="subcat_table">
						
					</tbody>
				</table>
			</div>
		</div>
	</section>
	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/masters/sub_category.js"></script>