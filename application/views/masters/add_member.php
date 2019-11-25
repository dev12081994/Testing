<?php foreach ($cus_data as $cus) {} ?>

<section role="main" class="content-body">
	<header class="page-header">
		<h2>Add/Update Members</h2>

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

	<!-- Modal Form Start -->
	<div class="modal zoom-anim-dialog" data-backdrop="static" id="gstregtype_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">GST Registration Type</h4>
				</div>
				<form method="post" id="add_gsttype_form" class="form-horizontal">
					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-3 control-label">Registration Type<span class="required">*</span></label>
							<div class="col-sm-9">
								<input type="text" id="grt_type" name="grt_type" class="form-control" placeholder="Enter Registration Type..." required/>
								<input type="hidden" id="grt_id" name="grt_id" class="form-control" placeholder="GST Registration Id..." readonly/>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="add_gsttype" class="btn btn-primary">Save</button>
						<button type="reset" onclick="clearForm()" class="btn btn-warning">Clear</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Modal Form nd -->

	<div class="row">
		<div class="col-md-12">
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
					
					<form method="post" id="add_member_form" class="form-horizontal" action="<?php echo base_url() ?>/MASTERS/saveMember">
						<div class="modal-body">
							<h4>REGISTRATION TYPE :</h4>					
							<div class="form-group row">
								<div class="col-sm-3">
									<label class="control-label">Member Type<span class="required">*</span></label>
									<input type="hidden" name="c_id" class="form-control" placeholder="Member Id..." value="<?php if(isset($cus['c_id'])) echo $cus['c_id']; ?>" readonly>

									<input type="hidden" id="c_oldstate" name="c_oldstate" class="form-control" placeholder="state Id..." value="<?php if(isset($cus['c_state'])) echo $cus['c_state']; ?>" readonly>

									<input type="hidden" id="c_oldcity" name="c_oldcity" class="form-control" placeholder="city Id..." value="<?php if(isset($cus['c_city'])) echo $cus['c_city']; ?>" readonly>

									<input type="hidden" id="c_olddep" name="c_olddep" class="form-control" placeholder="dep Id..." value="<?php if(isset($cus['c_dep'])) echo $cus['c_dep']; ?>" readonly>

									<input type="hidden" id="c_olddesig" name="c_olddesig" class="form-control" placeholder="desig Id..." value="<?php if(isset($cus['c_desig'])) echo $cus['c_desig']; ?>" readonly>

									<select data-plugin-selectTwo class="form-control " id="c_type" name="c_type" onchange="showEmpDiv(this.value)" required>
                                        <option value="">Select Type</option>
                                        <option value="1" <?php if((isset($cus['c_type']) && $cus['c_type']=='1') || !isset($cus['c_type']))echo 'selected'; ?>>Customer</option>
                                        <option value="2" <?php if(isset($cus['c_type']) && $cus['c_type']=='2')echo 'selected'; ?>>Vendor</option>
                                        <option value="3" <?php if(isset($cus['c_type']) && $cus['c_type']=='3')echo 'selected'; ?>>Employee</option>
                                        <option value="4" <?php if(isset($cus['c_type']) && $cus['c_type']=='4')echo 'selected'; ?>>Other</option>
                                    </select>
								</div>
								<div class="col-sm-3 empdiv" style="display:none">
									<label class="control-label ">Department</label>
									<select id="c_dep" class="form-control empopt" data-plugin-selectTwo name="c_dep" onchange="getDesig(this.value)"> 
	                                    <option value="">Select Department</option>
	                                    <?php foreach ($depdata as $key => $value) { ?>
	                                    <option value="<?php echo $value['dep_id']; ?>">
	                                        <?php echo ucwords(strtolower($value['dep_name'])); ?>
	                                    </option>
	                                    <?php } ?>
	                                </select>
								</div>

								<div class="col-sm-3 empdiv" style="display:none">
									<label class="control-label ">Designation</label>
									<select id="c_desig" class="form-control empopt" data-plugin-selectTwo name="c_desig" > 
	                                    <option value="">Select Designation</option>
	                                </select>
								</div>

								<div class="empdiv col-lg-3"  style="display:none">
	                                <label class="control-label">Date Of Join</label>

	                                <div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
										<input type="text" name="c_doj" id="c_doj" class="form-control mydatepicker empopt" placeholder="dd-mm-yyyy" value="<?php if(isset($cus['c_doj']) && $cus['c_doj']!="0000-00-00") echo date('d-m-Y',strtotime($cus['c_doj'])); ?>" onkeydown="event.preventDefault();" autocomplete="off">
									</div>
	                                
	                            </div>
							</div>
							<br/><h4>MEMBER'S PERSONAL DETAILS :</h4>
							<div class="form-group row">
	                            <div class="col-lg-3">
	                                <label class="control-label">Salutaion<span class="required">*</span></label>	
	                                <select name="c_salutation" id="c_salutation" 
	                                class="form-control" required>
	                                    <option value="">Select</option>	
	                                    <option value="Mr." <?php if(isset($cus['c_salutation']) && $cus['c_salutation']=='Mr.') echo 'selected'; ?>>Mr.</option>	
	                                    <option value="Mrs." <?php if(isset($cus['c_salutation']) && $cus['c_salutation']=='Mrs.') echo 'selected'; ?>>Mrs.</option>			
	                                    <option value="Miss." <?php if(isset($cus['c_salutation']) && $cus['c_salutation']=="Miss.") echo 'selected'; ?>>Miss.</option>
	                                </select>
	                            </div>	

	                            <div class="col-lg-3">
	                                <label class="control-label">First Name<span class="required">*</span></label>								
	                                <input type="text" name="c_firstname" id="c_firstname" placeholder="Enter First Name" class="form-control" required="true" value="<?php if(isset($cus['c_firstname'])) echo $cus['c_firstname']; ?>">
	                            </div>

	                            <div class="col-lg-3">
	                                <label class="control-label">Middle Name</label>								
	                                <input type="text" name="c_middlename" id="c_middlename" placeholder="Enter Middle Name" class="form-control" value="<?php if(isset($cus['c_middlename'])) echo $cus['c_middlename']; ?>">
	                            </div>

	                            <div class="col-lg-3">
	                                <label class="control-label">Last Name<span class="required">*</span></label>								
	                                <input type="text" name="c_lastname" id="c_lastname" value="<?php if(isset($cus['c_lastname'])) echo $cus['c_lastname']; ?>" placeholder="Enter Last Name" class="form-control" required>
	                            </div>                               				
	                        </div>	

	                        <div class="form-group row">

			                    <div class="col-lg-3">
		                        	<label class="control-label">Date of Birth</label>	
		                            <div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
										<input type="text" name="c_dob" id="c_dob" class="form-control mydatepicker" placeholder="dd-mm-yyyy" onkeydown="event.preventDefault();" autocomplete="off" value="<?php if(isset($cus['c_dob']) && $cus['c_dob']!="0000-00-00") echo date('d-m-Y',strtotime($cus['c_dob'])); ?>">
									</div>
			                    </div>	

			                    		
			                    <div class="col-lg-3">
			                        <label class="control-label">Gender</label>								
			                        <select name="c_gender" id="c_gender" class="form-control">
			                            <option value="">Select Gender</option>
			                            <option value="1" <?php if(isset($cus['c_gender']) && $cus['c_gender']=='1') echo 'selected'; ?>>Male</option>
			                            <option value="2" <?php if(isset($cus['c_gender']) && $cus['c_gender']=='2') echo 'selected'; ?>>Female</option>
			                            <option value="3" <?php if(isset($cus['c_gender']) && $cus['c_gender']=='3') echo 'selected'; ?>>Other</option>
			                        </select>
			                    </div>

			                     <div class="col-lg-3">
	                                <label class="control-label">Father/Husband</label>								
	                                <select name="c_gardiantype" id="c_gardiantype" class="form-control">
	                                    <option value="">Select</option>
	                                   <?php foreach($gardiandata as $value){ ?>
	                                    <option value="<?php echo $value['g_id']; ?>" <?php if(isset($cus['c_gardiantype']) && $cus['c_gardiantype']==$value['g_id']) echo 'selected'; ?>><?php echo strtoupper($value['g_name']); ?></option>
	                                    <?php } ?>
	                                </select>
	                            </div>

	                            <div class="col-lg-3">
	                                <label class="control-label">Father's Name</label>								
	                                <input type="text" name="c_gardianname" id="c_gardianname"
	                                placeholder="Enter Father/Husband Name" class="form-control" value="<?php if(isset($cus['c_gardianname'])) echo $cus['c_gardianname']; ?>">
	                            </div>

			                </div>


			                <div class="form-group row">
			                	<div class=" col-lg-3">
			                		<label class="control-label">Phone Number</label>                                
				                	<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-phone"></i>
										</span>
										<input id="c_phone" name="c_phone" placeholder="Enter Phone Number" class="form-control" value="<?php if(isset($cus['c_phone'])) echo $cus['c_phone']; ?>">
									</div>
								</div>

								<div class="col-lg-3">
			                		<label class="control-label">Mobile Number1<span class="required">*</span></label> 
			                		<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-phone"></i>
										</span> 
                                		<input id="c_mob1" name="c_mob1" placeholder="Enter Mobile Number1" class="form-control" required="true" value="<?php if(isset($cus['c_mob1'])) echo $cus['c_mob1']; ?>">
                                	</div>
								</div>
								<div class="col-lg-3">
			                		<label class="control-label">Mobile Number2 (Alternate)</label>  
			                		<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-phone"></i>
										</span>
                                		<input id="c_mob2" name="c_mob2"  placeholder="Enter Mobile Number2" class="form-control" value="<?php if(isset($cus['c_mob2'])) echo $cus['c_mob2']; ?>">
                                	</div>
								</div>

	                            <div class="col-lg-3">
	                                <label class="control-label">Email Id</label>                                
	                                <input type="text" name="c_email" id="c_email"
	                                placeholder="Enter Email Id" class="form-control" value="<?php if(isset($cus['c_email'])) echo $cus['c_email']; ?>">
	                            </div>

	                           

	                        </div>                        
		                        
		                    <br/><h4>KYC DETAILS :</h4>                    
		                    
		                    <div class="form-group row">                                
		                        <div class=" col-lg-3">
		                            <label class="control-label">Aadhar Number</label>
		                            <input type="text" name="c_aadharno" id="c_aadharno"
		                            placeholder="Enter Aadhar Number" class="form-control" value="<?php if(isset($cus['c_aadharno'])) echo $cus['c_aadharno']; ?>">
		                        </div>                                    
		                        <div class="col-lg-3">
		                            <label class="control-label">PAN Number</label>                               
		                            <input type="text" name="c_panno" id="c_panno"
		                            placeholder="Enter PAN Numebr" class="form-control" value="<?php if(isset($cus['c_panno'])) echo $cus['c_panno']; ?>">
		                        </div>

		                        <div class="col-lg-3">
		                    		<label class="control-label">GST Registration Type <a></a></label>
		                    		
		                    		<div class="input-group">
		                    			<select class="form-control" id="c_gstregtype" name="c_gstregtype">
		                    				<option value="">Select Type</option>
		                    			</select>
										<span class="input-group-addon" style="cursor:pointer;"  onclick="gstRegTypeModal()" title="Add New Registration Type">
											<i class="fa fa-plus"></i>
										</span>
									</div>		
		                    	</div>

		                        <div class="col-lg-3">
		                            <label class="control-label">GSTIN</label>                                
		                            <input type="text" name="c_gstno" id="c_gstno"
		                            placeholder="Enter GSTIN" class="form-control" value="<?php if(isset($cus['c_gstno'])) echo $cus['c_gstno']; ?>">
		                        </div> 
		                                                   
		                    </div>
		                    <div class="form-group row">
		                    	
		                    	<div class="col-lg-3">
		                            <label class="control-label">TIN Number</label>                                
		                            <input type="text" name="c_tinno" id="c_tinno"
		                            placeholder="Enter TIN Number" class="form-control" value="<?php if(isset($cus['c_tinno'])) echo $cus['c_tinno']; ?>">
		                        </div>   
		                    </div>

			                <br/><h4>BANK DETAILS :</h4>
			                <div class="form-group row">                                
	                            <div class="col-lg-3">
	                                <label class="control-label">Bank</label>                                
	                                <select name="c_bank" id="c_bank" data-plugin-selectTwo class="form-control">
	                                    <option value="">Select Bank</option>
	                                    <?php foreach ($bank_data as $key => $value) { ?>
	                                    <option value="<?php echo $value['bank_id']; ?>" >
	                                        <?php echo strtoupper($value['bank_short']); ?>
	                                    </option>
	                                    <?php } ?>
	                                </select>
	                            </div>                                    
	                            <div class="col-lg-3">
	                                <label class="control-label">A/C Number</label>                              
	                                <input type="text" name="c_accno" id="c_accno"
	                                placeholder="Enter A/C Numebr" class="form-control" value="<?php if(isset($cus['c_accno'])) echo $cus['c_accno']; ?>">
	                            </div>                               
	                            <div class="col-lg-3">
	                                <label class="control-label">IFSC Code</label>                               
	                                <input type="text" name="c_ifsc" id="c_ifsc"
	                                placeholder="Enter IFSC Code" class="form-control" value="<?php if(isset($cus['c_ifsc'])) echo $cus['c_ifsc']; ?>">
	                            </div>
	                            <div class="col-lg-3">
	                                <label class="control-label">Branch</label>                                
	                                <input type="text" name="c_branch" id="c_branch"
	                                placeholder="Enter Branch Name" class="form-control" value="<?php if(isset($cus['c_branch'])) echo $cus['c_branch']; ?>">
	                            </div>                              
	                        </div>

	                        <br/><h4>ADDRESS :</h4>
	                        <div class="form-group row">
	                                    
		                        <div class="col-lg-3">
		                            <label class="control-label">Country</label>                          
		                            <select name="c_country" data-plugin-selectTwo id="c_country" class="form-control"                                        onchange="fetchState(),fetchCity()" >
		                                <option value="">Select Country</option>    
		                            <?php foreach($countrydata as $key=>$value){ ?>
		                                <option value="<?php echo $value['id']; ?>" <?php if($value['id']==99)echo "selected"; ?> >
		                                    <?php echo ucwords(strtolower($value['country_name'])); ?>
		                                </option>
		                            <?php } ?>
		                            </select>
		                        </div>
		                        
		                        <div class="col-lg-3">
		                            <label class="control-label">State</label><br>                            
		                            <select name="c_state" id="c_state" data-plugin-selectTwo class="form-control" onchange="fetchCity()">
		                                <option value="">Select State</option>  
		                                <?php foreach ($statedata as $key => $value) { ?>
		                                <option value="<?php echo $value['state_id']; ?>" <?php if($value['state_id']==19)echo "selected"; ?>><?php echo ucwords(strtolower($value['state_name'])); ?></option>         
		                                <?php } ?>      
		                            </select>
		                        </div>  
		                                                  
		                       
		                        <div class="col-lg-3">
		                            <label class="control-label">City</label><br>                         
		                            <select name="c_city" id="c_city" data-plugin-selectTwo class="form-control">
		                                <option value="">Select City</option>       
		                                <?php foreach ($citydata as $key => $value) { ?>
		                                <option value="<?php echo $value['city_id']; ?>"><?php echo ucwords(strtolower($value['city_name'])); ?></option>           
		                                <?php } ?>
		                            </select>
		                        </div>
		                                                                   
		                    </div>
		                    <div class="row">
		                    	<div class="col-lg-12">
		                            <label class="control-label">Address</label><br/>                             
		                            <textarea name="c_address" id="c_address" class="form-control" placeholder="Enter Address" row="1"><?php if(isset($cus['c_address'])) echo $cus['c_address']; ?></textarea>
		                        </div>
		                    </div>

						</div>

						 
						<div class="modal-footer">
							<button type="submit" id="submit_member" class="btn btn-primary">Submit</button>
							<button type="button"  onclick="clearForm()" class="btn btn-warning">Clear</button>
							<a class="mb-xs mt-xs mr-xs btn btn-danger" data-toggle="tooltip" data-original-title="Back To Members Master" data-placement="top" href="<?php echo base_url() ?>MASTERS/members">Back</a>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
	<!-- end: page -->
</section>

<script type="text/javascript">
	
	$(document).ready(function(){
		<?php if(isset($cus['c_type']) && $cus['c_type']!=null) { ?> 
			showEmpDiv('<?php echo $cus['c_type']; ?>');
		<?php } ?>

		<?php if(isset($cus['c_dep']) && $cus['c_dep']!=null) { ?>
			$('#c_dep').val('<?php echo $cus['c_dep']; ?>').trigger('change'); 
		<?php } ?>


		<?php if(isset($cus['c_country']) && $cus['c_country']!=null) { ?>
			$('#c_country').val('<?php echo $cus['c_country']; ?>').trigger('change');
		<?php } ?>

		
		<?php if(isset($cus['c_bank']) && $cus['c_bank']!=null) { ?>
			$('#c_bank').val('<?php echo $cus['c_bank']; ?>').trigger('change');
		<?php } ?>

	});

</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/masters/members.js"></script>