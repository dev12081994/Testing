<section role="main" class="content-body">
	<header class="page-header">
		<h2>Members Master</h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Masters</span></li>				
				<li><span>Members</span></li>
			</ol>

			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<!-- Starts of modals-->
	
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
	<div class="row <?php if($this->session->flashdata('message')=='')echo 'hide'; ?>">
		<div class="col-lg-12">
			<div class="alert alert-info alert-dismissable">
				<button type="button" class="close mini" data-dismiss="alert" aria-hidden="true">&times;</button>			
				<?php echo $this->session->flashdata('message') ; ?>
			</div>
		</div>
	</div>
	
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
				 	<form method="post" id="srchmember_form" action="<?php echo base_url(); ?>ADMIN001/exportCusCsv">                               
                        <div class="row">
                            
                            <div class="col-sm-3">
								<select data-plugin-selectTwo class="form-control " id="srch_type" name="srch_type">
                                    <option value="">Select Type</option>
                                    <option value="1">Customer</option>
                                    <option value="2">Vendor</option>
                                    <option value="3">Employee</option>
                                    <option value="4">Other</option>
                           		</select>
							</div>

                            <div class="form-group col-lg-3">
                                <input type="text" name="srch_name" id="srch_name" class="form-control" placeholder="Search Customer Name">
                            </div>
                            
                            <div class="form-group col-lg-3">
                                <input type="text" name="srch_cgardian" id="srch_cgardian" class="form-control"  placeholder="Search Father/Husband Name">
                            </div>

                            <div class="form-group col-lg-3">
                                <input type="text" name="srch_contact" id="srch_contact" class="form-control" placeholder="Search Contact Number">
                            </div>                            
                        </div>                           
                        
                        <div class="row">
                        	<div class="form-group col-lg-3">
                                <input type="text" name="srch_fdate" id="srch_fdate" class="form-control mydatepicker" placeholder="Select From Date" onkeydown="event.preventDefault();" autocomplete="off">                                     
                             </div>

                            <div class="form-group col-lg-3">
                                <input type="text" name="srch_tdate" id="srch_tdate" class="form-control mydatepicker" placeholder="Select To Date" onkeydown="event.preventDefault();" autocomplete="off">                                     
                            </div>                            

                            <div class="col-md-6 text-right">
								<button type="button" class="mb-xs mt-xs mr-xs btn btn-info reload-vd-table" onclick="srchMember()"><i class="fa fa-search"></i>&nbsp;Search</button> 
								<a class="mb-xs mt-xs mr-xs btn btn-primary pull-right" data-toggle="tooltip" data-original-title="Add New Member" data-placement="top" href="<?php echo base_url() ?>MASTERS/addMember"><i class="fa fa-plus"></i>&nbsp;Add New</a>
							</div>
                        </div>
                   	</form>

					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<thead>
								<tr>
									<th>S.N.</th>
                                    <th>Entry</th> 
                                    <th>Type</th>
                                    <th>Member</th>
                                    <th>Father/Husband</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>PAN</th>
                                    <th class="h">GSTIN</th>
                                    <th class="h">TIN</th>
                                    <?php if($this->ion_auth->is_admin()){ ?>
                                    <th class="h" colspan="2"></th>                                               
                                    <?php } ?>
								</tr>
							</thead>
							<tbody id="member_table">
								
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	</div>
	<!-- end: page -->
</section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/masters/members.js"></script>