
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Create New User</h2>

    <div class="right-wrapper pull-right">
      <ol class="breadcrumbs">
        <li>
          <a href="<?php echo base_url(); ?>">
            <i class="fa fa-home"></i>
          </a>
        </li>
        <li><span>Manage Users</span></li>
        <li><span>Users</span></li>
      </ol>

      <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
    </div>
  </header>


  <!-- start: page -->  

  <section class="panel">
    <header class="panel-heading">
      <div class="panel-actions">        
        <a href="#" class="fa fa-caret-down"></a>
      </div>

      <h2 class="panel-title">Add New User</h2>
    </header>
    <div class="panel-body">
      <?php echo form_open("auth/create_user");?>
      
      <div class="row">
        <div class="form-group col-lg-3">
            <label>Employee Name</label>
            <select class="form-control" id="agent_id" name="agent_id" 
              onchange="setagentdetails(this.value);" data-plugin-selectTwo required>
              <option value="">Select Employee</option>
              <?php foreach($agent_data as $key=>$value){ ?>
              <option value="<?php echo $value['agent_id']; ?>">
                <?php 
                  echo ucwords(strtolower($value['agent_firstname']))." ".
                     ucwords(strtolower($value['agent_middlename']))." ".
                     ucwords(strtolower($value['agent_lastname']));
                 ?>
              </option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group col-lg-3">
            <?php echo lang('create_user_fname_label', 'first_name');?>
            <?php echo form_input($first_name);?>
          </div>
          <div class="form-group col-lg-3">
            <?php echo lang('create_user_lname_label', 'last_name');?>
            <?php echo form_input($last_name);?>
          </div>
          <?php
          if($identity_column!=='email') {
            echo '<div class="col-lg-12">';
            echo lang('create_user_identity_label', 'identity');
            echo '<br />';
            echo form_error('identity');
            echo form_input($identity);
            echo '</div>';
          }
          ?>

          <div class="form-group col-lg-3">
            <?php echo lang('create_user_company_label', 'company');?>
            <?php echo form_input($company);?>
          </div>
      </div>
      <div class="row">   
                <div class="form-group col-lg-3">              
                  <?php echo lang('create_user_email_label', 'email');?>
                  <?php echo form_input($email);?>
                </div>
                          
                <div class="form-group col-lg-3">
                  <?php echo lang('create_user_phone_label', 'phone');?> 
                  <?php echo form_input($phone);?>
                </div>
  
                <div class="form-group col-lg-3">
                  <?php echo lang('create_user_password_label', 'password');?> 
                  <?php echo form_input($password);?>
                </div>
  
                <div class="form-group col-lg-3">
                  <?php echo lang('create_user_password_confirm_label', 'password_confirm');?>
                  <?php echo form_input($password_confirm);?>
                </div>  
              </div>
              
      <div class="row">
         <div class="col-lg-3">
          <br/>
          <?php echo form_submit('submit', lang('create_user_submit_btn'),'class="btn                                     btn-success"');?>
        </div>
      </div>

      <?php echo form_close();?>
    </div>
  </section>
  
 
  <!-- end: page -->
</section>

<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/javascripts/views/masters/category.js"></script> -->

<script type="text/javascript">
  function setagentdetails(agent_id)
  {
    $('#first_name').val("");
       $('#last_name').val("");
       $('#phone').val("");
  
      $.ajax({
    type:"POST",
    url:"<?php echo base_url(); ?>auth/fetchAgentData",
    data:{agent_id:agent_id},
    success:function(msg)
    {
       var obj = JSON.parse(msg);
       
       $('#first_name').val(obj[0].agent_firstname);
       $('#last_name').val(obj[0].agent_lastname);
       $('#phone').val(obj[0].agent_contact);
       
              
    }
     });
  }
</script>