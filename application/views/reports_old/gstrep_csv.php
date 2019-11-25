
<html>
<head>
    <title>GST Report</title>
</head>
<body>
    <table border="1">
        <thead>            
            <tr>
                <td colspan="15" style="background:#330099;color:#ffffff;font-size:16px;">
                    <center>
                        <strong>GST Report</strong>                       
                    </center>
                 </td>
            </tr>           
            <tr>
                <th rowspan="2">S.N.</th>
                <th rowspan="2">Date</th> 
                <th rowspan="2">Invoice Type</th> 
                <th rowspan="2">Invoie No</th>
                <th rowspan="2">Product</th> 
                <th rowspan="2">Remark</th> 
                <th rowspan="2">Amount</th> 
                <th rowspan="2">GST(%)</th> 
                <th colspan="3">GST Receive From Customer</th> 
                <th colspan="3">GST Paid To Vendor</th>
                <th>(Receive-Paid)</th>                                  
            </tr>
            <tr>
                <th>SGST</th> 
                <th>CGST</th>  
                <th>IGST</th>
                <th>SGST</th> 
                <th>CGST</th>  
                <th>IGST</th>
                <th>Balance </th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $sl=1;$tot_dr=0;$tot_cr=0;$tot_gross=0;$bal=0;$bal1=0;
                $totsgst_dr=0;$totcgst_dr=0;$totigst_dr=0;$totsgst_cr=0;
                $totcgst_cr=0;$totigst_cr=0;$totgst_cr=0;$totgst_dr=0;$row_bal=0;

                foreach ($ldg_data as $key => $value) 
                {   
                    $dr_amt='';$cr_amt='';
                    $gross = round(($value['qty']*$value['rate']),2);
                    if($value['disctype']==='1')
                        $disc = round(($gross*$value['disc'])/100,2);
                    else
                        $disc=$value['disc'];

                    if($value['inclusive']==1)
                    {
                        $gst=round($gross-(float)($gross*(100/(100+$value['gstper']))),2);
                        $net=$gross;
                        $incl=" (inclusive)";
                    }
                    else
                    {
                        $gross=$gross-$disc;
                        $gst=round(($gross*$value['gstper'])/100,2);
                        $net= $gross+$gst;
                        $incl="";
                    }
                    $tot_gross+=$gross;
                    if($value['inv_for']==='1')
                    {                    
                        $tot_dr+=$dr_amt=$net;
                        $bal+=$net;
                    }
                    else
                    {
                        $tot_cr+=$cr_amt=$net;
                        $bal-=$net;
                    }               

                    $sgst_dr='';
                    $cgst_dr='';
                    $igst_dr='';
                    $sgst_cr='';
                    $cgst_cr='';
                    $igst_cr='';
                    $gst_dr=0;
                    $gst_cr=0;

                    if($value['inv_for']==1)
                    {
                        $invtype='Purchase';

                        if($value['inv_location']==1)
                        {
                            $sgst_cr=round($gst/2,2);
                            $cgst_cr=round($gst/2,2);
                            $totsgst_cr+=$sgst_cr;
                            $totcgst_cr+=$sgst_cr;
                        }
                        else
                        {
                            $totigst_cr+=$gst;
                            $igst_cr=$gst;
                        }
                        $gst_cr=$gst;
                        $totgst_cr+=$gst_cr;
                        $row_bal+=$gst_cr;
                    }
                    else
                    {
                        if($value['inv_location']==1)
                        {
                            $sgst_dr=round($gst/2,2);
                            $cgst_dr=round($gst/2,2);
                            $totsgst_dr+=$sgst_dr;
                            $totcgst_dr+=$sgst_dr;
                        }
                        else
                        {
                            $totigst_dr+=$gst;
                            $igst_dr=$gst;
                        }
                        $invtype='Sales';
                        $gst_dr=$gst;
                        $totgst_dr+=$gst_dr;
                        $row_bal-=$gst_dr;
                    }

                    echo "<tr>";          
                    echo "<td>". $sl++ ."</td>";
                    echo "<td>". date('d-m-Y',strtotime($value['inv_date'])) ."</td>";
                    echo "<td>". $invtype."</td>";
                    echo "<td>". $value['inv_billno']."</td>";
                    echo "<td>". ucwords(strtolower($value['product'])) ."</td>";
                    echo "<td>". ucwords(strtolower($value['remark'])) ."</td>";
                    echo "<td class='align-right'>". $gross ."</td>";
                    echo "<td>". $value['gstper'].'%' ."</td>";
                    echo "<td class='align-right'>". $sgst_cr . "</td>";
                    echo "<td class='align-right'>". $cgst_cr ."</td>";
                    echo "<td class='align-right'>". $igst_cr ."</td>";
                    echo "<td class='align-right'>". $sgst_dr . "</td>";
                    echo "<td class='align-right'>". $cgst_dr ."</td>";
                    echo "<td class='align-right'>". $igst_dr ."</td>";
                    echo "<td class='align-right'>". $row_bal ."</td>";
                    echo "</tr>";       
                }
                $bal1=$totgst_cr-$totgst_dr;
                echo "<tr>";
                echo "<td colspan='6'><strong>Total Amount : </strong></td>";
                echo "<td colspan='1' class='align-right'><strong>". $tot_gross ."</strong></td>";
                echo "<td colspan='1' class='align-right'>&nbsp;</td>";
                echo "<td colspan='1' class='align-right'><strong>". $totsgst_cr ."</strong></td>";
                echo "<td colspan='1' class='align-right'><strong>". $totcgst_cr ."</strong></td>";
                echo "<td colspan='1' class='align-right'><strong>". $totigst_cr ."</strong></td>";
                echo "<td colspan='1' class='align-right'><strong>". $totsgst_dr ."</strong></td>";
                echo "<td colspan='1' class='align-right'><strong>". $totcgst_dr ."</strong></td>";
                echo "<td colspan='1' class='align-right'><strong>". $totigst_dr ."</strong></td>";
                echo "<td colspan='1' class='align-right'><strong>". $bal1 ."</strong></td>";
                echo "</tr>";               
            ?>
        </tbody>
     </table>
    </body>
</html>