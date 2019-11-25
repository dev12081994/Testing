<!DOCTYPE html>
<html>
<head>
	<title>Purchasing Report</title>
</head>
<body>
	<table border="1">
        <thead>            
            <tr>
                <td colspan="12" style="background:#330099;color:#ffffff;font-size:16px;">
                    <center>
                        <strong>Date Wise Material Purchasing Report</strong>                       
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
                <th>Paid/Credit</th>                                  
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
                $amt=$value['stk_rpu']*$value['stk_qty'];

                if($value['stk_disctype']==1)
                    $disc=round(($amt*$value['stk_disc'])/100,2);
                else
                    $disc=$value['stk_disc'];

                if($value['inclusive']==1)
                {
                    $gst=round($amt-(float)($amt*(100/(100+$value['stk_gstper']))),2);
                    $net=$amt;
                    $incl=" (inclusive)";
                }
                else
                {
                    $amt=$amt-$disc;
                    $gst=round(($amt*$value['stk_gstper'])/100,2);
                    $net= $amt+$gst;
                    $incl="";
                } 
                $tot_amt+=$amt;
                $tot_disc+=$disc;
                $tot_gst+=$gst;
                $tot_net+=$net;
            ?>
            <tr id='row_<?php echo $value["inv_id"]; ?>'>
                <td><?php echo $sl++; ?></td>
                <td><?php echo date('d-m-Y',strtotime($value['inv_date'])); ?></td>
                <td><?php echo strtoupper($value['inv_billno']); ?></td>                  
                <td><?php echo ucwords(strtolower($value['vendor'])); ?></td>                     
                <td><?php echo ucwords(strtolower($value['prod_name'])); ?></td>                     
                <td><?php echo $value['stk_qty'].'&nbsp;'.$value['prod_unit']; ?></td>                     
                <td><?php echo $value['stk_rpu']; ?></td>                     
                <td><?php echo $amt; ?></td>                     
                <td><?php echo $disc; ?></td>                     
                <td><?php echo $gst; ?></td>                     
                <td><?php echo $net; ?></td>                     
                <td class="cen"><?php if($value['inv_paidstatus']!=2)echo '<span style="color:red">Credit</span>';else echo '<span style="color:green">Paid</span>'; ?></td>  
            </tr> 
            <?php } } ?> 
            <tr>
                <th colspan="7">Total : </th>
                <th colspan="1" style="text-align:right"><?php echo $tot_amt; ?></th>
                <th colspan="1" style="text-align:right"><?php echo $tot_disc; ?></th>
                <th colspan="1" style="text-align:right"><?php echo $tot_gst; ?></th>
                <th colspan="1" style="text-align:right"><?php echo $tot_net; ?></th>
                <th colspan="1">&nbsp;</th>
            </tr>
        </tbody>
     </table>
	</body>
</html>