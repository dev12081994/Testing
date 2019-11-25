<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
</head>
<body>
    <table border="1">
        <thead>            
            <tr>
                <td colspan="12" style="background:#330099;color:#ffffff;font-size:16px;">
                    <center>
                        <strong>Member's Report</strong>                       
                    </center>
                 </td>
            </tr>
            <tr>
                <th colspan="6" style="background:green;color:#ffffff;font-size:16px;">
                    <?php echo $member ?>
                 </th>
                <th colspan="6" style="background:green;color:#ffffff;font-size:16px;">
                    Balance : <?php echo $balance; ?>
                 </th>
            </tr>
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
                <th>Amount(Dr)</th> 
                <th>Cr</th> 
                <th>Balance</th>                                         
            </tr>
        </thead>
        <tbody>
            <?php 
                $sl=1;$tot_dr=0;$tot_cr=0;$bal=0;$bal1=0;$member='Not Selected';
                foreach ($ldg_data as $key => $value) 
                {   
                    $dr_amt='';$cr_amt='';
                    if($value['disctype']==='1')
                    {
                        $gross = round((($value['qty']*$value['rate'])*(100 - $value['disc']))/100,2);
                        $net= $gross*(100+$value['gstper'])/100;
                        $disc_sign=' %';
                    }
                    else
                    {
                        $gross = round(($value['qty']*$value['rate'])-$value['disc'],2);
                        $net= $gross*(100+$value['gstper'])/100;
                        $disc_sign=' /-';
                    }

                    if($value['disc']=='')
                        $disc_sign="";
                    if($value['gstper']=='')
                        $gst_sign="";
                    else
                        $gst_sign=' %';

                    if($value['acc_trantype']==='1')
                    {                    
                        $tot_dr+=$dr_amt=round($net,2);
                        $bal+=round($net,2);
                    }
                    else
                    {
                        $tot_cr+=$cr_amt=round($net,2);
                        $bal-=round($net,2);
                    }

                    echo "<tr>";          
                    echo "<td>". $sl++ ."</td>";
                    echo "<td>". date('d-m-Y',strtotime($value['date'])) ."</td>";
                    echo "<td>". $value['inv_id']."</td>";
                    echo "<td>". $value['remark'] ."</td>";
                    echo "<td>". $value['descri'] ."</td>";
                    echo "<td>". $value['qty']. ' ' .$value['unit'] ."</td>";
                    echo "<td class='align-right'>". $value['rate'] ."</td>";
                    echo "<td class='align-right'>". $value['disc'] . $disc_sign . "</td>";
                    echo "<td class='align-right'>". $value['gstper'] .$gst_sign."</td>";
                    echo "<td class='align-right'>". $dr_amt ."</td>";
                    echo "<td class='align-right'>". $cr_amt ."</td>";
                    echo "<td class='align-right'>". $bal ."</td>";
                    echo "</tr>";       
                }

                if($tot_cr>$tot_dr)
                    $bal1=abs($tot_cr-$tot_dr) . ' (Cr.)';
                elseif($tot_cr<$tot_dr)
                    $bal1=abs($tot_cr-$tot_dr) . ' (Dr.)';
                else
                    $bal1='0';

                echo "<tr >";
                echo "<td colspan='9'><strong>Total Amount : </strong></td>";
                echo "<td colspan='1' class='align-right'><strong>". $tot_dr ."</strong></td>";
                echo "<td colspan='1' class='align-right'><strong>". $tot_cr ."</strong></td>";
                echo "<td colspan='1' class='align-right'><strong>". $bal1 ."</strong></td>";
                echo "</tr>";            
            ?>
        </tbody>
     </table>
    </body>
</html>