<!DOCTYPE html>
<html>
<head>
	<title>Sales Report</title>
</head>
<body>
	<table border="1">
        <thead>            
            <tr>
                <td colspan="11" style="background:#330099;color:#ffffff;font-size:16px;">
                    <center>
                        <strong>Date Wise Material Sales Report</strong>                       
                    </center>
                 </td>
            </tr>
            <tr>
                <th>S.N.</th>
                <th>Date</th> 
                <th>Invoice No.</th> 
                <th>Party</th>
                <th>Material</th>
                <th>Qty.</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Discount</th>
                <th>GST Amt.</th>
                <th>Net</th>                               
            </tr>
        </thead>
        <tbody>
            <?php 
            $sl=1;$tot_amt=0;$tot_disc=0;$tot_gst=0;$tot_net=0;
            if(count($inventory_data) < 1)
            {
                echo "<tr><td colspan='12' class='no-data-found'>No Record Found</td></tr>";
            }
            else {
          
            foreach($inventory_data as $value){ 
                $amt=$value['sl_rpu']*$value['sl_qty'];

                if($value['sl_disctype']==1)
                    $disc=round(($amt*$value['sl_disc'])/100,2);
                else
                    $disc=$value['sl_disc'];

                if($value['inclusive']==1)
                {
                    $gst=round($amt-(float)($amt*(100/(100+$value['sl_gstper']))),2);
                    $net=$amt;
                    $incl=" (inclusive)";
                }
                else
                {
                    $amt=$amt-$disc;
                    $gst=round(($amt*$value['sl_gstper'])/100,2);
                    $net= $amt+$gst;
                    $incl="";
                }

                $tot_amt+=$amt;
                $tot_disc+=$disc;
                $tot_gst+=$gst;
                $tot_net+=$net;
            
                if($value['inv_type']=='3' || $value['inv_type']=='4')
                    $cus_name=$this->CommonModel->vehicleProject($value['inv_perticular'],$value['inv_type']);
                else
                    $cus_name=$value['vendor'];

            ?>
            <tr>
                <td><?php echo $sl++; ?></td>
                <td><?php echo date('d-m-Y',strtotime($value['inv_date'])); ?></td>
                <td><?php echo strtoupper($value['inv_billno']); ?></td>                  
                <td><?php echo ucwords(strtolower($cus_name)); ?></td>                     
                <td><?php echo ucwords(strtolower($value['prod_name'])); ?></td>                     
                <td><?php echo $value['sl_qty'].'&nbsp;'.$value['prod_unit']; ?></td>                     
                <td style="text-align:right"><?php echo $value['sl_rpu']; ?></td>                     
                <td style="text-align:right"><?php echo $amt; ?></td>                     
                <td style="text-align:right"><?php echo $disc; ?></td>                     
                <td style="text-align:right"><?php echo $incl.$gst; ?></td>                     
                <td style="text-align:right"><?php echo $net; ?></td>   
            </tr> 
            <?php } } ?> 
            <tr>
                <th colspan="7">Total : </th>
                <th colspan="1" style="text-align:right"><?php echo $tot_amt; ?></th>
                <th colspan="1" style="text-align:right"><?php echo $tot_disc; ?></th>
                <th colspan="1" style="text-align:right"><?php echo $tot_gst; ?></th>
                <th colspan="1" style="text-align:right"><?php echo $tot_net; ?></th>
            </tr>
        </tbody>
     </table>
	</body>
</html>