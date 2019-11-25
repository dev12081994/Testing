<div class="inner-wrapper">
  <!-- start: sidebar -->
    <aside id="sidebar-left" class="sidebar-left">        
          <div class="sidebar-header">
            <div class="sidebar-title">
              Navigation
            </div>
            <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
              <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
            </div>
          </div>
        
          <div class="nano">
            <div class="nano-content">
              <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                  <li class="nav-active">
                    <a href="<?php echo base_url(); ?>ADMIN/index">
                      <i class="fa fa-home" aria-hidden="true"></i>
                      <span>Dashboard</span>
                    </a>
                  </li>
                  
                  <li class="nav-parent">
                    <a>
                      <i class="fa fa-users " aria-hidden="true"></i>
                      <span>Manage Users</span>
                    </a>
                    <ul class="nav nav-children"> 
                      <li><a href="<?php echo base_url(); ?>MASTERS/users">Users</a></li>     
                    </ul>
                  </li>

                  <li class="nav-parent">
                    <a>
                      <i class="fa fa-list-alt" aria-hidden="true"></i>
                      <span>Masters</span>
                    </a>
                    <ul class="nav nav-children">                      
                      <li class="nav-parent hide">
                        <a>Address</a>
                        <ul class="nav nav-children">                          
                          <li><a>Village</a></li>
                          <li><a>Panchayat</a></li>
                          <li><a>Block</a></li>
                          <li><a>City</a></li>
                          <li><a>State</a></li>
                          <li><a>Country</a></li>
                        </ul>
                      </li>    
                      <li><a href="<?php echo base_url(); ?>MASTERS/bank">Bank &amp; A/C No.</a></li>            
                      <li><a href="<?php echo base_url(); ?>MASTERS/category">Category</a></li>      
                      <li><a href="<?php echo base_url(); ?>MASTERS/cityState">City && State </a></li>         
                      <li><a href="<?php echo base_url(); ?>MASTERS/depDesig">Department && Designation </a></li>
                      <li><a href="<?php echo base_url(); ?>MASTERS/firm">Firm</a></li>            
                      <li><a href="<?php echo base_url(); ?>MASTERS/gst">GST</a></li>
                      <li class="nav-parent hide">
                        <a>Product Category</a>
                        <ul class="nav nav-children">    
                          <li><a href="<?php echo base_url(); ?>MASTERS/subCategory">Sub Category</a></li>
                        </ul>
                      </li>
                      <li><a href="<?php echo base_url(); ?>MASTERS/ledger">Ledger</a></li>
                      <li><a href="<?php echo base_url(); ?>MASTERS/members">Members</a></li>
                      <li><a href="<?php echo base_url(); ?>MASTERS/product">Product</a></li>
                      <li><a href="<?php echo base_url(); ?>MASTERS/vehicle">Vehicle</a></li>
                    </ul>
                  </li>


                  <li class="nav-parent">
                    <a>
                      <i class="fa fa-money" aria-hidden="true"></i>
                      <span>Accounts</span>
                    </a>
                    <ul class="nav nav-children"> 
                      <li><a href="<?php echo base_url(); ?>ACCOUNTS/payment">Payment</a></li>            
                      <li><a href="<?php echo base_url(); ?>ACCOUNTS/receive">Receive</a></li>   
                    </ul>
                  </li>

                  
                  <li class="nav-parent">
                    <a>
                      <i class="fa fa-list-alt" aria-hidden="true"></i>
                      <span>Inventory</span>
                    </a>
                    <ul class="nav nav-children"> 
                      <li><a href="<?php echo base_url(); ?>INVENTORY/purchase">Purchase</a></li>            
                      <li><a href="<?php echo base_url(); ?>INVENTORY/sale">Sales</a></li>            
                      <li><a href="<?php echo base_url(); ?>INVENTORY/memWiseInvoice">Member Wise Invoice</a></li>            
                    </ul>
                  </li>

                  <li class="nav-parent">
                    <a>
                      <i class="fa fa-list-alt" aria-hidden="true"></i>
                      <span>Projects</span>
                    </a>
                    <ul class="nav nav-children"> 
                      <li><a href="<?php echo base_url(); ?>PROJECTS/projects">Projects</a></li>             
                    </ul>
                  </li>                  

                  <li class="nav-parent">
                    <a>
                      <i class="fa fa-automobile" aria-hidden="true"></i>
                      <span>Vehicle</span>
                    </a>
                    <ul class="nav nav-children"> 
                      <li><a href="<?php echo base_url(); ?>VEHICLE/dailyEntry">Daily Running Details</a></li>
                      <li><a href="<?php echo base_url(); ?>VEHICLE/vehicleExpense">Expense</a></li>
                    </ul>
                  </li>

                  <li class="nav-parent">
                    <a>
                      <i class="fa fa-info" aria-hidden="true"></i>
                      <span>Reports</span>
                    </a>
                    <ul class="nav nav-children"> 
                      
                      <li><a href="<?php echo base_url(); ?>REPORTS/soldProduct">Daily Material Sales</a></li>
                      <li><a href="<?php echo base_url(); ?>REPORTS/purchasedProduct">Daily Material Purchase</a></li>
                      <li><a href="<?php echo base_url(); ?>REPORTS/gstRep">GST Report</a></li>
                      <li><a href="<?php echo base_url(); ?>REPORTS/prodStock">Product Stock</a></li>
                      <li><a href="<?php echo base_url(); ?>REPORTS/vehicleRunRep">Vechicle Runing</a></li>
                      <li><a href="<?php echo base_url(); ?>REPORTS/vehicleExpRep">Vechicle Expenses</a></li>
                      <!-- <li><a href="<?php echo base_url(); ?>ACCOUNTS/memLedger">Member's Ledger</a></li> -->            
                      <li><a href="<?php echo base_url(); ?>REPORTS/memReport">Member's Report</a></li>            
                    </ul>
                  </li>
<!--
                 <li>
                    <a href="<?php echo base_url() ?>../New-Templates/octopus-master-template/octopus/index.html" target="_blank">
                      <i class="fa fa-external-link" aria-hidden="true" ></i>
                      <span>Template<em class="not-included">(Orignal Theme)</em></span>
                    </a>
                  </li>  
 
                  <li class="nav-parent">
                    <a>
                      <i class="fa fa-list-alt" aria-hidden="true"></i>
                      <span>Masters</span>
                    </a>
                    <ul class="nav nav-children">
                      <li>
                        <a>Members</a>
                      </li>
                      <li class="nav-parent">
                        <a>Address</a>
                        <ul class="nav nav-children">
                          <li class="nav-parent">
                            <a>Third Level</a>
                            <ul class="nav nav-children">
                              <li>
                                <a>Village</a>
                              </li>
                              <li>
                                <a>Panchayat</a>
                              </li>
                            </ul>
                          </li>
                          <li>
                            <a>Second Level Link #1</a>
                          </li>
                          <li>
                            <a>Second Level Link #2</a>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li> -->

                </ul>
              </nav>
        
              <hr class="separator" />
        <!-- 
              <div class="sidebar-widget widget-tasks">
                <div class="widget-header">
                  <h6>Projects</h6>
                  <div class="widget-toggle">+</div>
                </div>
                <div class="widget-content">
                  <ul class="list-unstyled m-none">
                    <li><a href="#">JSOFT HTML5 Template</a></li>
                    <li><a href="#">Tucson Template</a></li>
                    <li><a href="#">JSOFT Admin</a></li>
                  </ul>
                </div>
              </div>
        
              <hr class="separator" />
        
              <div class="sidebar-widget widget-stats">
                <div class="widget-header">
                  <h6>Company Stats</h6>
                  <div class="widget-toggle">+</div>
                </div>
                <div class="widget-content">
                  <ul>
                    <li>
                      <span class="stats-title">Stat 1</span>
                      <span class="stats-complete">85%</span>
                      <div class="progress">
                        <div class="progress-bar progress-bar-primary progress-without-number" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 85%;">
                          <span class="sr-only">85% Complete</span>
                        </div>
                      </div>
                    </li>
                    <li>
                      <span class="stats-title">Stat 2</span>
                      <span class="stats-complete">70%</span>
                      <div class="progress">
                        <div class="progress-bar progress-bar-primary progress-without-number" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
                          <span class="sr-only">70% Complete</span>
                        </div>
                      </div>
                    </li>
                    <li>
                      <span class="stats-title">Stat 3</span>
                      <span class="stats-complete">2%</span>
                      <div class="progress">
                        <div class="progress-bar progress-bar-primary progress-without-number" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="width: 2%;">
                          <span class="sr-only">2% Complete</span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div> -->
            </div>
        
          </div>
        
        </aside>
        <!-- end: sidebar -->