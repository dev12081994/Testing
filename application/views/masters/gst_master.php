

<section role="main" class="content-body">
	<header class="page-header">
		<h2>GST Master</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Masters</span></li>
				<li><span>GST</span></li>
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
					<h4 class="modal-title" id="myModalLabel">Ledger</h4>
				</div>
				<form method="post" id="add_cat_form" class="form-horizontal">
					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-3 control-label">GST Name<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" id="c_name" name="c_name" class="form-control" placeholder="Enter Ledger Name..." required/>
								<input type="hidden" id="c_id" name="c_id" class="form-control" placeholder="Ledger Id..." readonly/>
								<input type="hidden" id="c_for" name="c_for" value="3" placeholder="Ledger Id..." readonly/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Percantage(%)<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" id="c_taxperc" name="c_taxperc" class="form-control" placeholder="Enter Percantage..." required/>
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-3 control-label">Remark</label>
							<div class="col-sm-9">
								<textarea rows="5" id="c_remark" name="c_remark" class="form-control" placeholder="Type Remark..."></textarea>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="submit" class="btn btn-primary">Save</button>
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

			<h2 class="panel-title">GST Details </h2>
		</header>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-3">
					<input type="hidden" class="form-control" placeholder="Search For Ledger" value="3" id="srch_cfor"> 
				</div>
				<div class="col-md-9 text-right">
					<button class="mb-xs mt-xs mr-xs btn btn-info reload-table hide">Search</button> 
					<a class="mb-xs mt-xs mr-xs btn btn-primary pull-right reset" onclick="clearForm()" data-toggle="modal" data-target="#categ_modal">Add New</a>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-condensed mb-none">
					<thead>
						<tr>
							<th>S.N.</th>
							<th>GST</th>
							<th>GST(%)</th>
							<th>Remark</th>
							<th>Edit</th>
							<th>Delete</th>
							<!-- <th class="hidden-phone">Engine version</th> -->
						</tr>
					</thead>
					<tbody id="cat_table">
						
					</tbody>
				</table>
			</div>
		</div>
	</section>
	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/masters/category.js"></script>