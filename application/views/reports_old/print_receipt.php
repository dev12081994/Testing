<?php foreach ($payhist_data as $key => $value) {} ?>
<html>
	<head>
		<title>Porto Admin - Invoice Print</title>
		
		<!-- Vendor CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.css" />

		<!-- Invoice Print Style -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/invoice-print.css" />
	</head>
	<body id="prin_body">
		<?php if(count($payhist_data) > 0 && $payhist_data[0]['acc_id']!=null){ ?>
		<div class="invoice" id="prin_div">
			<header class="clearfix">
				<div class="row">
					<div class="col-sm-6 mt-md">
						<h3 class="h4 mt-none mb-sm text-dark text-bold">Payment Receipt</h4>
					</div>
					<div class="col-sm-6 text-right mt-md mb-md">
						<address class="ib mr-xlg">
							<?php echo strtoupper($value['f_name']); ?>
							<br/>
							<?php echo strtoupper($value['f_address']); ?>
							<br/>
							Contact : <?php echo $value['f_contact']; ?>
							<br/>
							<?php echo $value['f_email']; ?>
						</address>
						<div class="ib">
							<img src="<?php echo base_url().$value['f_logo']; ?>" height="80px;" width="80px;"/>
						</div>
					</div>
				</div>
			</header>
			<div class="bill-info">
				<div class="row">
					<div class="col-md-6">
						<div class="bill-to">
							<p class="h5 mb-xs text-dark text-semibold">
								<?php if($value['inv_for']==='1')echo "Paid To : ";else echo "Received From : "; ?>
							</p>
							<address>
								<?php 
									if($value['inv_type']==2)
										echo ucwords(strtolower($this->CommonModel->memberName($value['inv_perticular']))); 
									else
										echo ucwords(strtoupper($this->CommonModel->vehicleProject($value['inv_perticular'],$value['inv_type']))); 
									
									$member_data=$this->CommonModel->memberData($value['inv_perticular']);

									if($value['inv_type']==2)
									{
										if($member_data['address']!='')
											echo '<br/>'.ucwords(strtolower($member_data['address']));
									
										if(trim($member_data['contact'],',')!='')
											echo '<br/>'.$member_data['contact'];

										if($member_data['email']!='')
											echo '<br/>'.$member_data['email'];
									}
								?>
							</address>
						</div>
					</div>
					<div class="col-md-6">
						<div class="bill-data text-right">
							<p class="mb-none">
								<span class="text-dark">Date:</span>
								<span class="value"><?php echo date('d-m-Y'); ?></span>
							</p>
						</div>
					</div>
				</div>
			</div>
		
			<div class="table-responsive">
				<table class="table invoice-items">
					<thead>
						<thead>
							<tr class="h6 text-dark">
								<th id="cell-id"   style="width:8%"  class="text-semibold">#</th>
								<th id="cell-item" style="width:40%" class="text-semibold">Date</th>
								<th id="cell-item" style="width:40%" class="text-semibold">Against Bill No.</th>
								<th id="cell-item" style="width:10%"  class="text-semibold">Amount</th>
							</tr>
						</thead>
					</thead>
					<tbody>
						<?php 
							$sl=1;$total_paid=0;$tot_bill=0;
							foreach ($payhist_data as $key => $hd) {
								
								$total_paid +=$hd['acc_amt'];
							?>
							<tr>
								<td><?php echo $sl++; ?></td>
								<td class="text-left"><?php echo date('d-m-Y',strtotime($hd['acc_trandt'])); ?></td>
								<td class="text-left"><?php echo $hd['inv_billno']; ?></td>
								<td class="text-center"><?php echo $hd['acc_amt']; ?></td>
							</tr>
						<?php } ?>							
					</tbody>
				</table>
			</div>
		
			<div class="invoice-summary">
				<div class="row">
					<div class="col-sm-4 col-sm-offset-8">
						<table class="table h5 text-dark" width="30%" align="right">
							<tbody>
								<tr class="b-top-none">
									<td colspan="2">Total Bill Amount</td>
									<td class="text-right" style="padding-right:6% !important;"><?php echo $tot_bill=$value['inv_gross']+$value['inv_gstamt']-$value['inv_disc']+$value['inv_roundoff']; ?></td>
								</tr>
								<tr>
									<td colspan="2">Total Paid</td>
									<td class="text-right" style="padding-right:6% !important;"><?php echo $total_paid; ?></td>
								</tr>
								<tr class="h6 text-dark">
									<td colspan="2" style="font-weight:bold;">Balance Amount</td>
									<td class="text-right" style="font-weight:bold;padding-right:6% !important;"><?php if($tot_bill-$total_paid == 0) echo "NILL";else echo $tot_bill-$total_paid; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<script>
			document.getElementById("top_header").style.display='none';
			var divElements = document.getElementById("prin_body").innerHTML;
			document.body.innerHTML="<html><head><title></title></head><body>"+ 
	        divElements+"</body>";
	       	window.print();
	        window.location.href=base_url + '<?php echo $location; ?>';

		</script>
		<?php } else { ?>
		<div class="invoice">
			<header class="clearfix">
				<div class="row">
					<div class="col-sm-12 mt-md">
						<h2 class="h2 mt-none mb-sm text-dark text-bold" style="color:red !important;">Please Select Transactions </h2>
					</div>
				</div>
			</header>
		</div>
		<?php } ?>		
	</body>
</html>