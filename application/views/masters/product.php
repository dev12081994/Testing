<section role="main" class="content-body">
	<header class="page-header">
		<h2>Product Master</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Masters</span></li>				
				<li><span>Product</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<!-- Starts of modals-->

	<div class="modal zoom-anim-dialog" data-backdrop="static" id="addproduct_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Product Details</h4>
				</div>
				<form method="post" id="add_prod_form" class="form-horizontal">
					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-3 control-label">Category<span class="required">*</span></label>
							<div class="col-sm-9">
								<select data-plugin-selectTwo class="form-control " id="prod_ctgid" name="prod_ctgid" required>
									<option value="">Select Category</option>
									<?php foreach ($ctg_list as $key => $value) { echo "<option value='".$value['c_id']."'>".ucwords(strtolower($value['c_name']))."</option>";
									} ?>
								</select>
								<input type="hidden" id="prod_id" name="prod_id" class="form-control " placeholder="Product Id..." readonly/>
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-3 control-label">HSN/SAC Code</label>
							<div class="col-sm-9">
								<input type="text" id="prod_hsn_sac" name="prod_hsn_sac" class="form-control " placeholder="HSN/SAC Code..." />
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-3 control-label">Product Name<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" id="prod_name" name="prod_name" class="form-control " placeholder="Product Name..." required />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Unit<span class="required">*</span></label>
							<div class="col-sm-9">
								<select data-plugin-selectTwo class="form-control " id="prod_unit" name="prod_unit" required>
									<option value="">Select Unit</option>
									<option value="Kg.">Kg.</option>
									<option value="Gram.">Gram</option>
									<option value="Feet">Feet</option>
									<option value="Inch">Inch</option>
									<option value="Liter">Liter</option>
									<option value="Pcs.">Pcs.</option>
								</select>
							</div>
						</div>

						<div class="form-group">	
							<label class="col-sm-3 control-label">Purchase Rate</label>
							<div class="col-sm-9">
								<input type="text" id="prod_purrate" name="prod_purrate" class="form-control " placeholder="Enter Purchase Rate..." required />
							</div>
						</div>

						<div class="form-group">	
							<label class="col-sm-3 control-label">Sale Rate</label>
							<div class="col-sm-9">
								<input type="text" id="prod_salerate" name="prod_salerate" class="form-control " placeholder="Enter Sale Rate..." required />
							</div>
						</div>

						<div class="form-group">	
							<label class="col-sm-3 control-label">GST Applicable<span class="required">*</span></label>
							<div class="col-sm-9">
								<select id="prod_isgst" name="prod_isgst" class="form-control" onchange="gstRequired()" required>
									<option value="">Select Option</option>
									<option value="1">Yes</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>

						<div class="form-group">	
							<label class="col-sm-3 control-label">GST Included</label>
							<div class="col-sm-9">
								<select id="prod_purgstincl" name="prod_purgstincl" class="form-control">
									<option value="">GST Included In Rate</option>
									<option value="1">Yes</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>

						<div class="form-group">	
							<label class="col-sm-3 control-label">GST Rate(%)</label>
							<div class="col-sm-9">
								<select id="prod_gstrate" name="prod_gstrate" class="form-control">
									<option value="">Select GST</option>
								</select>
							</div>
						</div>						

						<div class="form-group">	
							<label class="col-sm-3 control-label">Opening Stock</label>
							<div class="col-sm-9">
								<input type="text" id="prod_openstock" name="prod_openstock" class="form-control " placeholder="Enter Opening Stock..." />
							</div>
						</div>

						<div class="form-group">	
							<label class="col-sm-3 control-label">Remark</label>
							<div class="col-sm-9">
								<textarea rows="1" id="prod_remark" name="prod_remark" class="form-control " placeholder="Type Remark..."></textarea>
							</div>
						</div>					</div>
					<div class="modal-footer">
						<button type="button" id="submit_product" class="btn btn-primary">Submit</button>
						<button type="button"  onclick="clearForm()" class="btn btn-warning">Clear</button>
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
						<p>All Records Related To This Member Will Be Delete Permanentaly ?</p>
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

					<h2 class="panel-title">Member's Detail </h2>
				</header>
				<div class="panel-body">
				 	<form method="post" id="srchproduct_form" action="<?php echo base_url(); ?>ADMIN001/exportCusCsv">                               
                        <div class="row">
                            
                            <div class="col-sm-2">
								<select data-plugin-selectTwo class="form-control " id="srch_ctgid" name="srch_ctgid">
                                    <option value="">Select Category</option>
									<?php foreach ($ctg_list as $key => $value) { echo "<option value='".$value['c_id']."'>".ucwords(strtolower($value['c_name']))."</option>";
									} ?>
                           		</select>
							</div>
                            <div class="col-sm-2">
								<select data-plugin-selectTwo class="form-control " id="srch_unit" name="srch_unit">
                                    <option value="">Select Unit</option>
									<option value="Kg.">Kg.</option>
									<option value="Gram.">Gram</option>
									<option value="Feet">Feet</option>
									<option value="Inch">Inch</option>
									<option value="Liter">Liter</option>
									<option value="Pcs.">Pcs.</option>
                           		</select>
							</div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_name" id="srch_name" class="form-control" placeholder="Search Product Name">
                            </div>
                            
                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_fdate" id="srch_fdate" class="form-control mydatepicker" placeholder="Select From Date" onkeydown="event.preventDefault();" autocomplete="off">                                     
                             </div>

                            <div class="form-group col-lg-2">
                                <input type="text" name="srch_tdate" id="srch_tdate" class="form-control mydatepicker" placeholder="Search To Date" onkeydown="event.preventDefault();" autocomplete="off">                                     
                            </div>

                            <div class="col-md-2 text-right">
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-info reload-table" title="Search" onclick="srchProduct()"><i class="fa fa-search"></i></button> 
								<a class="mb-xs mt-xs mr-xs btn btn-primary pull-right"  data-toggle="modal" data-target="#addproduct_modal" onclick="clearForm()" title="Add New Product"><i class="fa fa-plus"></i></a>
							</div>
                        </div>
                   	</form>

					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<thead>
								<tr>
									<th>S.N.</th>
                                    <th>Entry</th> 
                                    <th>Category</th>
                                    <th>Product</th>
                                    <th>HSN/SAC</th>
                                    <th>Unit</th>
                                    <th>Opening Stock</th>
                                    <th>Purchase Rate</th>
                                    <th>Sale Rate</th>
                                    <th>GST</th>
                                    <th>GST Included In Rate</th>
                                    <th>Remark</th>
                                    <th class="h" colspan="2"></th>  
								</tr>
							</thead>
							<tbody id="product_table">
								
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	</div>
	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/masters/product.js"></script>