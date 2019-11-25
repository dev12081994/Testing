<div class="row">
	<div class="col-md-12">
		<button type="button" id="perticular" class="mb-xs mt-xs mr-xs btn btn-primary btn-block">
			<!-- <div style="width:49%;text-align: left !important;">ttt</div> -->	
			<div class="row">
				<div class="col-md-6" style="font-size:16px;text-align: left !important;"><span id="memname">Member Name : <?php echo  $paidto; ?></span></div>
				<div class="col-md-6" style="font-size:16px;text-align: right !important;">Balance : <span id="membal"><?php echo $bal1; ?></span></div>
			</div>
		</button>
	</div>
</div>

<div class="col-md-12" >	
	<div class="table-responsive">
		<table <?php if($is_export>0)echo 'border=1'; ?> class="table table-bordered table-striped table-condensed mb-none">
			<thead>
				<tr>
					<th>S.N.</th>
	                <th>Date</th> 
	                <th>Bill No</th> 
	                <th>Remark</th>
	                <th>Description</th> 
	                <th>Qty</th> 
	                <th>Rate</th> 
	                <th>Discount</th> 
	                <th>GST</th> 
	                <th>Transportation</th> 
	                <th>Round Off</th> 
	                <th>Amount(Dr)</th> 
	                <th>Cr</th> 
	                <th>Balance</th>                                         
				</tr>
			</thead>
			<tbody>
				<?php
				$sl=1;$transport_arr=[];$tot_dr=0;$tot_cr=0;$bal=0;$bal1=0;$member='Not Selected1';
	            
	            if(count($ldg_data)===0 || $ldg_data[0]['acc_id']===null)  
	            {
	                echo '<tr><td colspan="14" class="no-data-found">No Record Found</td></tr>';
	            }
	            else
	            {
	                
	                foreach ($ldg_data as $key => $value) 
	                {   
	                    $dr_amt='';$cr_amt='';
	                   if($value['disctype']==1)
	                    {
	                        $gross = round((((float)$value['qty']*(float)$value['rate'])*(100 - (float)$value['disc']))/100,2);
	                        $net= $gross*(100+$value['gstper'])/100;
	                        
	                       $disc_sign=' %';
	                    }
	                    else
	                    {
	                       $gross = round(((float)$value['qty'] * (float)$value['rate'])-(float)$value['disc'],2);
	                        $net= ((float)$gross * (100 + (float)$value['gstper']))/100;
	                        $disc_sign=' /-';

	                    }   

	                    if($value['disc']=='')
	                        $disc_sign="";
	                    if($value['gstper']=='')
	                        $gst_sign="";
	                    else
	                        $gst_sign=' %';


	                    $transport_charge=0;$roundoff=0;
	                    if(in_array($value['tbl'],array("s_sale","s_stock")))
	                    {                        
	                        if(array_search($value['inv_id'], $transport_arr)===false)
	                        {
	                            array_push($transport_arr,$value['inv_id']);
	                            $transport_charge=$value['transport'];
	                            $roundoff=$value['roundoff'];
	                        }
	                    }

	                    if($value['acc_trantype']==='1')
	                    {                    
	                        $tot_dr+=$dr_amt=round($net,2)+$transport_charge+$roundoff;
	                        $bal+=round($dr_amt,2);
	                    }
	                    else
	                    {
	                        $tot_cr+=$cr_amt=round($net,2)+$transport_charge+$roundoff;
	                        $bal-=round($cr_amt,2);
	                    }                    

	                    ?>
	                    <tr>
	                    <?php
	                    echo "<td>". $sl++ ."</td>";
	                    echo "<td>". date('d-m-Y',strtotime($value['date'])) ."</td>";
	                    echo "<td>". $value['inv_id']."</td>";
	                    echo "<td>". $value['remark'] ."</td>";
	                    echo "<td>". $value['descri'] ."</td>";
	                    echo "<td>". $value['qty']. ' ' .$value['unit'] ."</td>";
	                    echo "<td class='align-right'>". $value['rate'] ."</td>";
	                    echo "<td class='align-right'>". $value['disc'] . $disc_sign . "</td>";
	                    echo "<td class='align-right'>". $value['gstper'] .$gst_sign."</td>";
	                    echo "<td class='align-right'>". $transport_charge ."</td>";
	                    echo "<td class='align-right'>". $roundoff ."</td>";
	                    echo "<td class='align-right'>". $dr_amt ."</td>";
	                    echo "<td class='align-right'>". $cr_amt ."</td>";
	                    echo "<td class='align-right'>". round($bal,2) ."</td>";
	                    echo "</tr>";       	                   
	                }

	                if($tot_cr>$tot_dr)
	                    $bal1=abs($tot_cr-$tot_dr) . ' (Cr.)';
	                elseif($tot_cr<$tot_dr)
	                    $bal1=abs($tot_cr-$tot_dr) . ' (Dr.)';
	                else
	                    $bal1='0';
	            }

	            echo "<tr >";
	            echo "<td colspan='11'><strong>Total Amount : </strong></td>";
	            echo "<td colspan='1' class='align-right'><strong>". $tot_dr ."</strong></td>";
	            echo "<td colspan='1' class='align-right'><strong>". $tot_cr ."</strong></td>";
	            echo "<td colspan='1' class='align-right'><strong>". $bal1 ."</strong></td>";
	            echo "</tr>";            
	            ?>
	            <script type="text/javascript">
	                $(function(){

	                    $('#membal').html('<?php echo $bal1; ?>');
	                    $('.modal-basic').magnificPopup({
	                        type: 'inline',
	                        preloader: false,
	                        modal: true
	                    });
	                });
	            </script>
	            
			</tbody>
		</table>
	</div>
</div>