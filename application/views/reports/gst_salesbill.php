<script type="text/javascript">

function printDiv(div_id) 
{    
    var divToPrint = document.getElementById(div_id);
    var popupWin = window.open('', '_blank');
    popupWin.document.open();
    popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
    popupWin.document.close();           
}

function NumToWord(inputNumber, outputControl) 
{
    var str = new String(inputNumber);
    var once = ['Zero', ' One', ' Two', ' Three', ' Four', ' Five', ' Six', ' Seven', ' Eight', ' Nine'];
    var twos = ['Ten', ' Eleven', ' Twelve', ' Thirteen', ' Fourteen', ' Fifteen', ' Sixteen', ' Seventeen', ' Eighteen', ' Nineteen'];
    var tens = ['', 'Ten', ' Twenty', ' Thirty', ' Forty', ' Fifty', ' Sixty', ' Seventy', ' Eighty', ' Ninety'];

    var splt_arr = str.split(".");
    splt=splt_arr[0].split('');
    var rev = splt.reverse();


	var splt_dec=(splt_arr.length>1) ? splt_arr[1].split('') : new Array();
    var rev_dec = splt_dec.reverse();

    numLength = rev.length;
    var word = new Array();
    var word_dec = new Array();
    var j = 0;

    for (i = 0; i < numLength; i++) {
        switch (i) {

            case 0:
                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
                    word[j] = '';
                }
                else {
                    word[j] = once[rev[i]];
                }
                word[j] = word[j];
                break;
            case 1:
                aboveTens();
                break;

            case 2:
                if (rev[i] == 0) {
                    word[j] = '';
                }
                else if ((rev[i - 1] == 0) || (rev[i - 2] == 0)) {
                    word[j] = once[rev[i]] + " Hundred ";
                }
                else {
                    word[j] = once[rev[i]] + " Hundred  ";
                }
                break;

            case 3:
                if (rev[i] == 0 || rev[i + 1] == 1) {
                    word[j] = '';
                }
                else {
                    word[j] = once[rev[i]];
                }
                if ((rev[i + 1] != 0) || (rev[i] > 0)) {
                    word[j] = word[j] + " Thousand";
                }
                break;

                
            case 4:
                aboveTens();
                break;

            case 5:
                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
                    word[j] = '';
                }
                else {
                    word[j] = once[rev[i]];
                }
                if (rev[i + 1] !== '0' || rev[i] > '0') {
                    word[j] = word[j] + " Lakh";
                }
                 
                break;

            case 6:
                aboveTens();
                break;

            case 7:
                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
                    word[j] = '';
                }
                else {
                    word[j] = once[rev[i]];
                }
                if (rev[i + 1] !== '0' || rev[i] > '0') {
                    word[j] = word[j] + " Crore";
                }                
                break;

            case 8:
                aboveTens();
                break;

            default: break;
        }
        j++;
    }

    function aboveTens() {
        if (rev[i] == 0) { word[j] = ''; }
        else if (rev[i] == 1) { word[j] = twos[rev[i - 1]]; }
        else { word[j] = tens[rev[i]]; }
    }

    word.reverse();
    var finalOutput = 'Rupee';
    for (i = 0; i < numLength; i++) {
        finalOutput = finalOutput + word[i];
    }

    var n=0;
    for(m=0; m<rev_dec.length;m++)
    {
    	switch (m) {
            case 0:
                if ((rev_dec[m] == 0) || (rev_dec[m + 1] == 1)) {
                    word_dec[n] = '';
                }
                else {
                    word_dec[n] = once[rev_dec[m]];
                }
                word_dec[n] = word_dec[n];
                break;
            case 1:
                aboveTensRev();
                break;

            default: break;
        }
        n++;
    }  

    function aboveTensRev() {
        if (rev_dec[m] == 0) { word_dec[n] = ''; }
        else if (rev_dec[m] == 1) { word_dec[n] = twos[rev_dec[m - 1]]; }
        else { word_dec[n] = tens[rev_dec[m]]; }
    }     

    word_dec.reverse();
    if(word_dec.length > 0)
   		finalOutput = finalOutput + ' And ';

    for (i = 0; i < rev_dec.length; i++) {
        finalOutput = finalOutput + word_dec[i];
    }
    if(word_dec.length > 0)
   		finalOutput = finalOutput + ' Paise ';
    document.getElementById(outputControl).innerHTML = finalOutput + " Only";
}
</script> 

<?php foreach ($inventory_data as $key => $value) {} ?>
<!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/stylesheets/views/gst_bill.css"> -->
<section role="main" class="content-body">
	<header class="page-header">
		
			<div class="row">
				<div class="col-sm-10">
					<h2>Sales GST-Invoice</h2>
				</div>
				<div class="col-sm-2">
					<h2><button type="button" class="mb-xs mt-xs mr-xs btn btn-primary" title="Search" onclick="printDiv('print_div')"><i class="fa fa-print"></i> Print</button><h2>
				</div>
			</div>	
	</header>

	<!-- start: page -->
	<?php if(count($inventory_data) > 0 && $inventory_data[0]['inv_id']!=null){ ?>
	<section class="panel" id='print_div'> 
		<style type="text/css">
			
			.panel-body{
				font-size:12px;
				font-weight:bold; 
			}

			.main-div{
				border:1px solid black;min-height:787px;width:600px;text-align: left;
			}

			.address-main{
				min-height:30%;border-bottom:1px solid black;
			}

			.address-part{
				width:44%;min-height:30%;line-height:1.2;float: left;
			}

			#inv_info td{
				border-bottom:1px solid;border-right: 1px solid;line-height: 1.2;padding-left:1%;font-size:12px;
			}

			.inv-details-part{
				width:54%;min-height:30%;line-height:1.2;float: right;margin-left: 1%; border-left:1px solid black;
			}

			.supplyer-add{
				min-height:50%;border-bottom:1px solid black;padding: 1%;
			}

			.buyer-add{
				min-height:50%;padding: 1%;
			}

			.product-details{
				min-height:40%;
			}

			#prod_info th{
				border-bottom:1px solid;border-top:1px solid black;border-right: 1px solid;line-height: 1.2;font-size:12px;
			}

			#prod_info td{
				border-right: 1px solid;
				line-height: 1.6;
				padding-left:1%;font-size:12px;
			}

			.tax-details{
				min-height:10%;
				line-height: 1.3;	
				padding-left: 1%;
			}
			.declare-part{
				min-height:10%;
				padding-left: 1%;
				line-height: 	1.3;
			}
			.sign-part{
				min-height:10%;
				padding-left: 1%;
				border-top:1px solid black;
			}

			#tax_table td{
				border-right: 1px solid;
				border-bottom: 1px solid;
				padding-left:0.5%;
				font-size:12px;
			}
			#tax_table th{
				border-right: 1px solid;
				border-bottom: 1px solid;
				border-top: 1px solid;
				padding-left:0.5%;
				font-size:12px;
			}
	</style>
	<center>
		<div class="panel-body">
			<div>
				<h4 class="h4 mt-none mb-sm text-dark text-bold cen">Tax Invoice</h4><hr>
				<div class="main-div">
					<div class='address-main'>
						<div class="address-part">
							<div class="supplyer-add text-dark">
								<span class="text-dark"><?php echo ucwords(strtolower($value['f_name'])); ?></span><br />
								<span><?php echo ucwords(strtolower($value['f_address'])); ?></span><br/>
								<span>Mobile - <?php echo $value['f_contact']; ?></span><br/>
								<span>GSTIN/UIN - <?php echo strtoupper($value['f_gstin']); ?></span><br/>
								<span>State - <?php echo ucwords(strtolower($value['state_name'])).'&nbsp Code - '.$value['state_tin']; ?></span><br/>
								<span>E-Mail - <?php echo $value['f_email']; ?></span>
							</div>
							<div class="buyer-add text-dark">
								<span>Buyer : </span><br/>
								<span>
									<?php
									if($value['inv_type']==2)
										echo ucwords(strtolower($this->CommonModel->memberName($value['inv_perticular']))); 
									else
										echo ucwords(strtoupper($this->CommonModel->vehicleProject($value['inv_perticular'],$value['inv_type']))); 
									?>
								</span>								
								<?php 
								if($value['inv_type']==2)
								{
									$member_data=$this->CommonModel->memberData($value['inv_perticular']);
									if($member_data['address']!='')
										echo '<span>'.ucwords(strtolower($member_data['address'])).'</span><br/>';
								
									if(trim($member_data['contact'],',')!='')
										echo '<span>Mobile - '.$member_data['contact'].'</span><br/>';

									if($member_data['email']!='')
										echo '<span>Mobile - '.$member_data['email'].'</span><br/>';
								}
								?>
							</div>
						</div>
						<div class="inv-details-part text-dark">
							<table id="inv_info" width="100%">
								<tr>
									<td>
										Invoice No.<br/>
										<span><?php echo $value['inv_billno']; ?></span>
									</td>
									<td style="border-right:none !important;">
										Dated<br/>
										<span><?php echo date('d-M-Y',strtotime($value['inv_date'])); ?></span>
									</td>
								</tr>
								<tr>
									<td>Delivery Note<br />&nbsp;</td>
									<td style="border-right:none !important;">Mode/Terms Of Payment<br />&nbsp;</td>
								</tr>
								<tr>
									<td>
										Supplier's Ref.<br />
										<span><?php echo $value['inv_billno']; ?></span>
									</td>
									<td style="border-right:none !important;">Other Reference(s)<br />&nbsp;</td>
								</tr>
								<tr>
									<td>Buyer's Order No.<br />&nbsp;</td>
									<td style="border-right:none !important;">Dated<br />&nbsp;</td>
								</tr>
								<tr>
									<td>Dispatch Document No.<br />&nbsp;</td>
									<td style="border-right:none !important;">Delivery Note Date<br />&nbsp;</td>
								</tr>
								<tr>
									<td>Dispatched Through<br />&nbsp;</td>
									<td style="border-right:none !important;">Destination<br />&nbsp;</td>
								</tr>	
							</table>
							<span class="text-dark">Terms Of Delivery</span>
						</div>
					</div>
					<div class="product-details text-dark" >
						<table id="prod_info" width="100%;">
							<tr>
								<th class="text-dark">S.N.</th>
								<th class="text-dark">Description</th>
								<th class="text-dark">HSN/SAC</th>
								<th class="text-dark">Quantity</th>
								<th class="text-dark">Rate</th>
								<th class="text-dark">Disc.</th>
								<th class="text-dark">GST(%)</th>
								<th class="text-dark" style="border-right:none !important;">Amount</th>
							</tr>
							<?php
							$sl=1;$tot_net=0;$tot_gst=0;$hsn='';$tax_data=array();$i=0;
							foreach ($inventory_data as $inv_value) 
							{
								$gross = round(($inv_value['sl_qty']*$inv_value['sl_rpu']),2);
				              	if($inv_value['sl_disctype']==='1')
					       			$disc = round(($gross*$inv_value['sl_disc'])/100,2);
					       		else
					       			$disc=$inv_value['sl_disc'];

					       		if($inv_value['sl_gstinclusive']==1)
					       		{
					       			$gst=round($gross-(float)($gross*(100/(100+$inv_value['sl_gstper']))),2);
					       			$net=$gross;
					       			$gross=$gross-$gst;
					       			$incl=" (inclusive)";
					       		}
					       		else
					       		{
					       			$gross=$gross-$disc;
					       			$gst=round(($gross*$inv_value['sl_gstper'])/100,2);
					       			$net= $gross+$gst;
					       			$incl="";
					       		}       		
					       		$tot_net+=$net;
					       		$tot_gst+=$gst;

					       		if($hsn=='')
					       		{
					       			$hsn=$inv_value['prod_hsn_sac'];
					       			$igst=0;
						       		$sgst=0;
						       		$cgst=0;
						       		$taxable=0;
						       		$gst_per=0;
					       		}


					       			$taxable+=$gross;
					       			if($inv_value['inv_location']==2)
					       			{
										$gst_per=$inv_value['sl_gstper'];
					       				$igst+=$gst;
					       				$sgst+=0;
					       				$cgst+=0;
					       			}
					       			else
					       			{
					       				$gst_per=round($inv_value['sl_gstper']/2,2);
					       				$igst+=0;
					       				$sgst+=round($gst/2,2);
					       				$cgst+=round($gst/2,2);
					       			}
					       				

					       		if((count($inventory_data) > ($key+1) && $hsn!=$inventory_data[$key + 1 ]['prod_hsn_sac']) || count($inventory_data) == ($key+1)) 
					       		{
					       			$tax_arr['hsn']=$inv_value['prod_hsn_sac'];
					       			$tax_arr['taxable']=$taxable;
					       			$tax_arr['igst']=$igst;
					       			$tax_arr['gst_per']=$gst_per;
				       				$tax_arr['sgst']=$sgst;
				       				$tax_arr['cgst']=$cgst;
				       				$hsn=$inv_value['prod_hsn_sac'];
					       			
					       			$igst=0;
						       		$sgst=0;
						       		$cgst=0;
						       		$taxable=0;
						       		if($gst_per>0)
					       				array_push($tax_data,$tax_arr);
					       		}

								?>
								<tr>
									<td><?php echo $sl++; ?></td>
									<td><?php echo ucwords(strtolower($inv_value['prod_name'])); ?></td>
									<td><?php echo ucwords(strtolower($inv_value['prod_hsn_sac'])); ?></td>
									<td><?php echo round($inv_value['sl_qty'],2).' '.$inv_value['prod_unit']; ?></td>
									<td style="text-align: right;padding-right: 1%;"><?php echo round($inv_value['sl_rpu'],2); ?></td>
									<td style="text-align: right;padding-right: 1%;"><?php echo round($disc,2); ?></td>									
									<td><?php if($inv_value['sl_gstper']>0)echo round($inv_value['sl_gstper'],2).'%'; ?></td>
									<td style="border-right:none !important;text-align: right;padding-right: 1%;"><?php echo $net; ?></td>
								</tr>
								<?php	
							}
							?>
							<tr>
								<td style="border-top:1px solid !important;border-bottom:1px solid !important;" colspan="7" class="text-dark">Total</td>
								<td style="border-top:1px solid !important;border-bottom:1px solid !important;border-right:none !important;text-align: right;padding-right: 1%;" class="text-dark"><?php echo round($tot_net,2); ?></td>
							</tr>
						</table>
					</div>
						
					<div class="tax-details">
						<div class="text-dark" >
							<div class="text-dark">
								<span >Amount (In Words)</span>
								<span style="float: right;">E. & O.E</span><br/>
							</div>
							<div>
								<span>
									<?php echo ucwords(strtolower($value['f_name'])); ?>
									<span id="amt_inwords">&nbsp;</span>	
									<script type="text/javascript">
										NumToWord('<?php echo round($tot_net,2); ?>','amt_inwords');
									</script>
								</span>
							</div>
						</div>
							<br>
						<div  class="text-dark">

							<?php //print_r($tax_data);die;?>
							<table width="100%" id='tax_table'>
								<tr>
									<th rowspan="2">HSN/SAC</th>
									<th rowspan="2">Taxabale Value</th>
									<th colspan="2">CGST</th>
									<th colspan="2">SGST</th>
									<th colspan="2">IGST</th>	
									<th rowspan='2' style="border-right:none !important;text-align: right;padding-right: 1%;">Tax Amount</th>	
								</tr>
								<tr>
									<td>Rate</td>
									<td>Amt.</td>
									<td>Rate</td>
									<td>Amt.</td>
									<td>Rate</td>
									<td>Amt.</td>
								</tr>
								<?php 
								$tot_cgst=0;$tot_sgst=0;$tot_igst=0;
								foreach($tax_data as $td) 
								{
									$tot_cgst+=$td['cgst'];
									$tot_sgst+=$td['sgst'];
									$tot_igst+=$td['igst'];
								?>
								<tr>
									<td><?php echo $td['hsn']; ?></td>
									<td><?php echo $td['taxable']; ?></td>
									<td><?php if($td['cgst']>0)echo $td['gst_per'].'%'; ?></td>
									<td style="text-align: right;padding-right: 1%;"><?php if($td['cgst']>0)echo $td['cgst']; ?></td>
									<td><?php if($td['sgst']>0)echo $td['gst_per'].'%'; ?></td>
									<td style="text-align: right;padding-right: 1%;"><?php if($td['sgst']>0)echo $td['sgst']; ?></td>
									<td><?php if($td['igst']>0)echo $td['gst_per'].'%'; ?></td>
									<td style="text-align: right;padding-right: 1%;"><?php if($td['igst']>0)echo $td['igst']; ?></td>
									<td style="border-right:none !important;text-align: right;padding-right: 1%;"><?php if($td['igst']>0)echo $td['igst'];else echo $td['sgst']+$td['cgst']; ?></td>
								</tr>
								<?php } ?>
								<tr>
									<td>Total</td>
									<td>Taxabale Value</td>
									<td>&nbsp;</td>
									<td style="text-align: right;padding-right: 1%;"><?php if($tot_cgst>0)echo $tot_cgst; ?></td>
									<td>&nbsp;</td>
									<td style="text-align: right;padding-right: 1%;"><?php if($tot_cgst>0)echo $tot_cgst; ?></td>
									<td>&nbsp;</td>
									<td style="text-align: right;padding-right: 1%;"><?php if($tot_igst>0)echo $tot_igst; ?></td>
									<td style="border-right:none !important;text-align: right;padding-right: 1%;"><?php echo $tot_gst; ?></td>
								</tr>
							</table>
						</div>
						<div class="text-dark">
							Tax Amount (In Words) : <span id="taxamt_inwords">&nbsp;</span>	
							<?php if($tot_gst>0){ ?>
							<script type="text/javascript">
								NumToWord('<?php echo round($tot_gst,2); ?>','taxamt_inwords');
							</script>
							<?php } ?>
						</div>												
					</div>
					<div class="declare-part text-dark" style="	height:300px !important	; "	>
						
							<div style="width:53%;float: left;">
								<br/>
								<span>Company's PAN : <?php echo $value['f_pan']; ?></span><br/>
								<span>
									<u>Declaration</u><br/>
									We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.
								</span>
							</div>
							<div style="width:45%;float: right;padding-left: 1%">
								<br/>
								<span>Company's Bank Details :</span><br/>
								<table style="font-size:12px;">
									<tr>
										<td>Bank Name </td>
										<td>: <?php if($value['bank_name']!='')echo $value['bank_name'];else echo "<span style='color:red'>NA</span>"; ?></td>
									</tr>
									<tr>
										<td>A/C No. </td>
										<td>: <?php if($value['acc_num']!='')echo $value['acc_num'];else echo "<span style='color:red'>NA</span>"; ?></td>
									</tr>
									<tr>
										<td>IFSC Code </td>
										<td>: <?php if($value['acc_ifsc']!='')echo $value['acc_ifsc'];else echo "<span style='color:red'>NA</span>"; ?></td>
									</tr>
									<tr>
										<td>Branch </td>
										<td>: <?php if($value['acc_branch']!='')echo $value['acc_branch'];else echo "<span style='color:red'>NA</span>"; ?></td>
									</tr>
								</table>
							</div>
						</div>
					<div class="sign-part text-dark">
						<div style="border-right:1px solid;width:50%;">Customer's Seal & Signature</div>
						<div style="width:50%;float: right;"></div>
					</div>
				</div>
			</div>
		</div>
		</center>
	</section>
	<?php }else{ ?>
	<section class="panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-12 mt-md">
					<center><h2 class="h2 mt-none mb-sm text-dark text-bold" style="color: red !important">No Record Found<s/h2></center>
				</div>
			</div>
		</div>
	</section>
	<?php } ?>

	<!-- end: page -->
</section>

