
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Update User's Details </h2>

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

      <h2 class="panel-title">Edit User</h2>
    </header>
    <div class="panel-body">
      <?php echo form_open(uri_string());?>
      
          <div class="row">
                <div class="form-group col-lg-4">
                  <?php echo lang('edit_user_fname_label', 'first_name');?>
                  <?php echo form_input($first_name);?>
                </div>
                <div class="form-group col-lg-4">
                  <?php echo lang('edit_user_lname_label', 'last_name');?> 
                  <?php echo form_input($last_name);?>
                </div>
                <div class="form-group col-lg-4"> 
                  <?php echo lang('edit_user_company_label', 'company');?>
                  <?php echo form_input($company);?>
                </div>
              </div>
              
              <div class="row">
                <div class="form-group col-lg-4"> 
                  <?php echo lang('edit_user_phone_label', 'phone');?> 
                  <?php echo form_input($phone);?>
                </div>
                <div class="form-group col-lg-4">               
                  <?php echo lang('edit_user_password_label', 'password');?>                
                  <?php echo form_input($password);?>
                </div>
                <div class="form-group col-lg-4">               
                  <?php echo lang('edit_user_password_confirm_label', 'password_confirm');?>
                  <?php echo form_input($password_confirm);?>
                </div>
              </div>
              
              <div class="row">
                <div class="form-group col-lg-4">
                   <?php if ($this->ion_auth->is_admin()): ?>

                  <h3><?php echo lang('edit_user_groups_heading');?></h3>
                  <?php foreach ($groups as $group):?>
                    <label class="checkbox-inline">
                    <?php
                      $gID=$group['id'];
                      $checked = null;
                      $item = null;
                      foreach($currentGroups as $grp) {
                        if ($gID == $grp->id) {
                          $checked= ' checked="checked"';
                        break;
                        }
                      }
                    ?>
                    <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"                                        <?php echo $checked;?>>
                    <?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
                    </label>
                  <?php endforeach?>
                  <?php endif ?>
  
                  <?php echo form_hidden('id', $user->id);?>
                  <?php echo form_hidden($csrf); ?>
                </div>
               </div>
               <div class="row">  
                <div class="form-group col-lg-3">
                  <br/>
                  <?php echo form_submit('submit', lang('edit_user_submit_btn'),'class="btn                                     btn-success"');?>
                </div>
              </div>

            <?php echo form_close();?>
    </div>
  </section>
  
 
  <!-- end: page -->
</section>

