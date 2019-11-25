<?php foreach ($inventory_data as $key => $value) {} ?>

<section role="main" class="content-body">
	<header class="page-header">
		<h2>Sales Invoice</h2>
	
	</header>

	<!-- start: page -->
	<?php if(count($inventory_data) > 0 && $inventory_data[0]['inv_id']!=null){ ?>
	<section class="panel"> 
		<div class="panel-body">
			<div class="invoice">
				<header class="clearfix">
					<div class="row">
						<div class="col-sm-6 mt-md">
							<h2 class="h2 mt-none mb-sm text-dark text-bold">INVOICE - <?php echo $value['inv_billno']; ?></h2>
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
								<p class="h5 mb-xs text-dark text-semibold">To:</p>
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
									<span class="text-dark">Invoice Date:</span>
									<span class="value"><?php echo date('d-m-Y',strtotime($value['inv_date'])); ?></span>
								</p>
							</div>
						</div>
					</div>
				</div>
			
				<div class="table-responsive">
					<table class="table invoice-items">
						<thead>
							<tr class="h5 text-dark">
								<th id="cell-id"   style="width:8%"  class="text-semibold">#</th>
								<th id="cell-item" style="width:40%" class="text-semibold">Description</th>
								<th id="cell-item" style="width:10%"  class="text-semibold">Unit</th>
								<th id="cell-price" style="width:12%" class="text-center text-semibold">Price</th>
								<th id="cell-qty" style="width:12%" class="text-center text-semibold">Quantity</th>
								<th id="cell-total" style="width:18%" class="text-center text-semibold">Total</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$sl=1;$sub_total=0;$tot_disc=0;$transportation=0;$inv_roundoff=0;
								foreach ($inventory_data as $key => $items) {
									$rate=round($items['sl_rpu'],2);
									$qty=round($items['sl_qty'],2);
									$sub_total+=round($rate * $qty,2);

									$disc=0;
									if($items['sl_disc']>0)
									{
										if($value['sl_disctype']==='1')
										{
											$disc=round((($rate * $qty) * $items['sl_disc'])/100,2);
										}
										else
										{
											$disc=$items['sl_disc'];									
										}
									}

									$transportation = $items['inv_transportcharge'];
									$inv_roundoff = $items['inv_roundoff'];
									$tot_disc +=$disc;
								?>
								<tr>
									<td><?php echo $sl++; ?></td>
									<td class="text-semibold text-dark"><?php echo ucwords(strtolower($items['prod_name'])) ?></td>
									<td class="text-center"><?php echo strtoupper($items['prod_unit']); ?></td>
									<td class="text-center"><?php echo $rate; ?></td>
									<td class="text-center"><?php echo $qty; ?></td>
									<td class="text-right" style="padding-right:2% !important;"><?php echo round($rate * $qty,2); ?></td>
								</tr>
							<?php } ?>							
						</tbody>
					</table>
				</div>
			
				<div class="invoice-summary">
					<div class="row">
						<div class="col-sm-4 col-sm-offset-8">
							<table class="table h5 text-dark">
								<tbody>
									<tr class="b-top-none">
										<td colspan="2">Sub Total</td>
										<td class="text-right" style="padding-right:2% !important;"><?php echo $sub_total; ?></td>
									</tr>
									<tr>
										<td colspan="2">Discount</td>
										<td class="text-right" style="padding-right:2% !important;"><?php echo $tot_disc; ?></td>
									</tr>

									<?php if($transportation >0 ){ ?>
									<tr>
										<td colspan="2">Transportation Charge</td>
										<td class="text-right" style="padding-right:2% !important;"><?php echo $transportation; ?></td>
									</tr>
									<?php }

									if($inv_roundoff > 0 ) { ?>
									<tr>
										<td colspan="2">Round Off</td>
										<td class="text-right" style="padding-right:2% !important;"><?php echo $inv_roundoff; ?></td>
									</tr>
									<?php } ?>

									<tr class="h5 text-dark">
										<td colspan="2" style="font-weight:bold;">Grand Total</td>
										<td class="text-right" style="font-weight:bold;padding-right:2% !important;"><?php echo $sub_total-$tot_disc+$transportation+$inv_roundoff; ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="text-right mr-lg">
				<!-- <a href="#" class="btn btn-default">Submit Invoice</a> -->
				<a href="<?php echo base_url().'REPORTS/printSalesBill/'.$value['inv_id']; ?>" target="_blank" class="btn btn-primary ml-sm"><i class="fa fa-print"></i> Print</a>
			</div>
		</div>
	</section>
	<?php }else{ ?>
	<section class="panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-12 mt-md">
					<center><h2 class="h2 mt-none mb-sm text-dark text-bold" style="color: red !important">No Record Found</h2></center>
				</div>
			</div>
		</div>
	</section>
	<?php } ?>

	<!-- end: page -->
</section>

