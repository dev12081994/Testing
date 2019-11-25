<?php
	$pur_inv=0;
	$sales_inv=0;
	$pur_amt=0;
	$sales_amt=0;
	$pay=0;
	$rec=0;
	$pend_proj=0;
	$done_proj=0;
	foreach ($today_pur as $key => $value) {
		$pur_inv+=$value['today_inv'];
		$pur_amt+=$value['amount'];
	}
	foreach ($today_sales as $key => $value) {
		$sales_inv+=$value['today_inv'];
		$sales_amt+=$value['amount'];
	}
	foreach ($today_pay as $key => $value) {
		$pay+=$value['amount'];		
	}
	foreach ($today_receive as $key => $value) {
		$rec+=$value['amount'];		
	}
	foreach ($tot_proj as $key => $value) {
		if($value['prod_isdone']==1)
			$done_proj+=$value['project'];		
		else
			$pend_proj+=$value['project'];
	}
?>

<section role="main" class="content-body">
	<header class="page-header">
		<h2>Dashboard</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="index.html">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Dashboard</span></li>
			</ol>	
			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<div class="row" style="margin-top:-2%;margin-bottom:-2%;">
		<div class="col-md-12">
			<!-- Section 1 -->
			<section class="panel">
				<div class="panel-body">
				 	<form method="post" id="srchsale_form" action="<?php echo base_url(); ?>ADMIN/index">
                        <div class="row">  
                            <div class="form-group col-lg-3">
                                <input type="text" name="srch_fdate" id="srch_fdate" class="form-control mydatepicker" placeholder="From Date" autocomplete="off" value="<?php echo $fdate; ?>">
                             </div>

                            <div class="form-group col-lg-3">
                                <input type="text" name="srch_tdate" id="srch_tdate" class="form-control mydatepicker" placeholder="To Date" autocomplete="off" value="<?php echo $tdate; ?>">
                            </div>                            
                                               	
                        	<div class="col-md-6 text-right">
								<button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary" title="Search"><i class="fa fa-search"></i> Search</button>
							</div>
                        </div>
                   	</form>
               	</div>
            </section>
        </div>
    </div>


	<!-- start: page -->
	<div class="row">		
		<div class="col-md-4 col-lg-8 col-xl-4">
			<div class="row">
				<div class="col-md-12">
					<button type="button" class="mb-xs mt-xs mr-xs btn btn-primary btn-block" style="text-align: left !important;font-weight:bold;">Invoices Info</button>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 col-lg-6 col-xl-6">
					<section class="panel panel-featured-left panel-featured-primary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-primary">
										<i class="fa fa-file-text-o"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Purchase Invoices</h4>
										<div class="info">
											<strong class="amount"><?php echo $pur_inv; ?></strong>
											<span class="text-primary"></span>
										</div>
									</div>
									<div class="summary-footer">
										<a href="<?php echo base_url().'INVENTORY/purchase'; ?>" class="text-muted text-uppercase">(view all)</a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>

				<div class="col-md-12 col-lg-6 col-xl-6">
					<section class="panel panel-featured-left panel-featured-secondary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-primary">
										<i class="fa fa-file-text-o"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Sales Invoices</h4>
										<div class="info">
											<strong class="amount"><?php echo $sales_inv; ?></strong>
										</div>
									</div>
									<div class="summary-footer">
										<a href="<?php echo base_url().'INVENTORY/sale'; ?>" class="text-muted text-uppercase">(View All)</a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>				
			</div>

			<div class="row">
				
				<div class="col-md-12 col-lg-6 col-xl-6">
					<section class="panel panel-featured-left panel-featured-primary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-danger">
										<i class="fa fa-rupee"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Purchase Amount</h4>
										<div class="info">
											<strong class="amount"><?php echo $pur_amt; ?></strong>
											<span class="text-primary"></span>
										</div>
									</div>
									<div class="summary-footer">
										<a href="<?php echo base_url().'INVENTORY/purchase'; ?>" class="text-muted text-uppercase">(view all)</a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>		

				<div class="col-md-12 col-lg-6 col-xl-6">
					<section class="panel panel-featured-left panel-featured-secondary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-success">
										<i class="fa  fa-rupee"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Sales Amount</h4>
										<div class="info">
											<strong class="amount"><?php echo $sales_amt; ?></strong>
										</div>
									</div>
									<div class="summary-footer">
										<a href="<?php echo base_url().'INVENTORY/sale'; ?>" class="text-muted text-uppercase">(View All)</a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			
			</div>
			
		</div>	

		<div class="col-md-2 col-lg-4 col-xl-2">
			<div class="row">
				<div class="col-md-12">
					<button type="button" class="mb-xs mt-xs mr-xs btn btn-primary btn-block" style="text-align: left !important;font-weight:bold;">Payment / Receives Info</button>
				</div>
			</div>
			<div class="row">				
				<div class="col-md-12 col-lg-12 col-xl-12">
					<section class="panel panel-featured-left panel-featured-primary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-success">
										<i class="fa fa-rupee"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Receive Amount</h4>
										<div class="info">
											<strong class="amount"><?php echo $rec; ?></strong>
											<span class="text-primary"></span>
										</div>
									</div>
									<div class="summary-footer">
										<a href="<?php echo base_url().'ACCOUNTS/receive'; ?>" class="text-muted text-uppercase">(view all)</a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
			<div class="row">	
				<div class="col-md-12 col-lg-12 col-xl-12">
					<section class="panel panel-featured-left panel-featured-secondary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-danger">
										<i class="fa  fa-rupee"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Payment Amount</h4>
										<div class="info">
											<strong class="amount"><?php echo $pay; ?></strong>
										</div>
									</div>
									<div class="summary-footer">
										<a href="<?php echo base_url().'ACCOUNTS/payment'; ?>" class="text-muted text-uppercase">(View All)</a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>

		<div class="col-md-12 col-lg-12 col-xl-12">
			<div class="row">
				<div class="col-md-12">
					<button type="button" class="mb-xs mt-xs mr-xs btn btn-primary btn-block" style="text-align: left !important;font-weight:bold;">Projects Info</button>
				</div>
			</div>

			<div class="row">	
				<div class="col-md-4 col-lg-4 col-xl-12">
					<section class="panel panel-featured-left panel-featured-secondary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-info">
										<i class="fa fa-building"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Total Projects</h4>
										<div class="info">
											<strong class="amount"><?php echo $pend_proj+$done_proj; ?></strong>
										</div>
									</div>
									<div class="summary-footer">
										<a href="<?php echo base_url().'PROJECTS/projects'; ?>" class="text-muted text-uppercase">(View All)</a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			
				<div class="col-md-4 col-lg-4 col-xl-12">
					<section class="panel panel-featured-left panel-featured-secondary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-success">
										<i class="fa fa-building"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Completed Projects</h4>
										<div class="info">
											<strong class="amount"><?php echo $done_proj; ?></strong>
										</div>
									</div>
									<div class="summary-footer">
										<a href="<?php echo base_url().'PROJECTS/projects'; ?>" class="text-muted text-uppercase">(View All)</a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			
				<div class="col-md-4 col-lg-4 col-xl-12">
					<section class="panel panel-featured-left panel-featured-secondary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-danger">
										<i class="fa fa-building"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Pending Projects</h4>
										<div class="info">
											<strong class="amount"><?php echo $pend_proj; ?></strong>
										</div>
									</div>
									<div class="summary-footer">
										<a href="<?php echo base_url().'PROJECTS/projects'; ?>" class="text-muted text-uppercase">(View All)</a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>

	<!-- end: page -->
</section>