
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Users Masters</h2>

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
 <section class="panel">
    <header class="panel-heading">
      <div class="panel-actions">        
        <a href="#" class="fa fa-caret-down"></a>
      </div>

      <h2 class="panel-title">Users Details </h2>
    </header>
    <div class="panel-body">
      <div class="row <?php if($this->session->flashdata('message')=='')echo 'hide'; ?>">
    <div class="col-lg-12">
      <div class="alert alert-info alert-dismissable">
        <button type="button" class="close mini" data-dismiss="alert" aria-hidden="true">&times;</button>     
        <?php echo $this->session->flashdata('message') ; ?>
      </div>
    </div>
  </div>
      <div class="row">
        <div class="col-md-12 text-right">
          <a class="mb-xs mt-xs mr-xs btn btn-primary pull-right" href="<?php echo base_url(); ?>Auth/create_user">Add New</a>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-striped mb-none" id="datatable-default">
          <thead>
            <tr>
              <th>S.N.</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>User Name</th>  
              <th>Email</th>  
              <th>Group</th>
              <th>Company</th>
              <th>Edit</th>
            </tr>
          </thead>
          <tbody>
            <?php 
                  $sl=1;
                  foreach($user_data as $key=>$value){ 
                ?>
                  <tr>
                    <td><?php echo $sl++; ?></td>
                    <td><?php echo ucwords(strtolower($value['first_name'])); ?></td>
                    <td><?php echo ucwords(strtolower($value['last_name'])); ?></td>                
                    <td><?php echo ucwords(strtolower($value['username'])); ?></td>   
                    <td><?php echo $value['email']; ?></td>    
                    <td><?php echo ucwords(strtolower($value['name'])); ?></td>      
                    <td><?php echo ucwords(strtolower($value['company'])); ?></td>      
                    <td><?php echo anchor("auth/edit_user/".$value['id'], 'Edit') ;?></td>  
                  </tr>
                <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  </section>