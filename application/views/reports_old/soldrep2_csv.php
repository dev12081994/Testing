
<!DOCTYPE html>
<html>
<head>
	<title>Sales Report</title>
</head>
<body>
    <table border="1">
        <tr>
            <td colspan="7" style="background:#330099;color:#ffffff;font-size:16px;">
                <center>
                    <strong>डेली मटेरियल रपोर्ट </strong>                       
                </center>
             </td>
        </tr>
    </table>
    <?php 
               
        $date='';
        $date1='';
        $new_tbl=0;
        $tot_amt=0;$tot_disc=0;$tot_gst=0;$tot_net=0; 
        foreach($inventory_data as $key=>$value)
        { 
            if($date!=$value['inv_date'])
            { 
                $date_summary=array();               
                $sl=1;
                $new_tbl=1;
                $prod_id='';
                $prod_qty=0;
                $tot_amt=0;
                $tot_disc=0;
                $tot_gst=0;
                $tot_net=0;
                $date1=$date;
                $date=$value['inv_date'];
            ?>
            <br/>
            <table border="1">
                <tr style="background-color: yellow; ">
                    <th colspan="2">दिनाँक </th>
                    <th colspan="5" style="text-align: right;"><?php echo date('d/m/Y',strtotime($value['inv_date'])); ?></th>
                </tr>
                <tr style="background-color: yellow;">
                    <th>क्र. </th>
                    <th>बिल नं.</th> 
                    <th>पार्टी नाम </th> 
                    <th>विवरण </th>
                    <th>मात्रा </th>
                    <th>उधारी / नगद </th>
                    <th>चिन्ह </th>
                </tr>
            <?php } 
            
            if($value['prod_id']!=$prod_id)
            {
                if($prod_id!='')
                {
                    $summry['product']=$prod_name;
                    $summry['rate']=$prod_rpu;
                    $summry['qty']=$prod_qty;
                    $summry['amt']=$tot_amt;
                    $summry['disc']=$tot_disc;
                    $summry['gst']=$tot_gst;
                    $summry['net']=$tot_net;
                    array_push($date_summary,$summry);
                }                
                $prod_name=$value['prod_name'];
                $prod_rpu=$value['sl_rpu'];
                $prod_qty=0;
                $tot_amt=0;
                $tot_disc=0;
                $tot_gst=0;
                $tot_net=0;
            }

            $prod_id=$value['prod_id'];
            
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
            
            $prod_qty+=$value['sl_qty'];
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
                <td><?php echo strtoupper($value['inv_billno']); ?></td>                  
                <td><?php echo ucwords(strtolower($cus_name)); ?></td>                     
                <td><?php echo ucwords(strtolower($value['prod_name'])); ?></td>                     
                <td><?php echo $value['sl_qty'].'&nbsp;'.$value['prod_unit']; ?></td>                       
                <td class="cen"><?php if($value['inv_paidstatus']!='2')echo '<span style="color:red">उधारी </span>';else echo '<span style="color:green">नगद </span>'; ?></td>  
                <td>&nbsp;</td>  
            </tr> 
        <?php if((count($inventory_data) > ($key+1) && $date!=$inventory_data[$key + 1 ]['inv_date']) || count($inventory_data) == ($key+1)) { 

            $summry['product']=$value['prod_name'];
            $summry['rate']=$value['sl_rpu'];
            $summry['qty']=$prod_qty;
            $summry['amt']=$tot_amt;
            $summry['disc']=$tot_disc;
            $summry['gst']=$tot_gst;
            $summry['net']=$tot_net;
            array_push($date_summary,$summry);
        ?> 
        </table>
        <br/>
        <table border="1">
            <tr style="background-color: yellow; ">
                <th colspan="8" style="text-align: left;">गोसबारा </th>
            </tr>
            <tr style="background-color: yellow; ">
                <th>क्र. </th>
                <th>विवरण </th>
                <th>मात्रा </th>
                <th>दर </th>
                <th>राशि  </th>
                <th>छूट </th>
                <th>GST</th>
                <th>नेट </th>
            </tr>
            <?php 
            $s_sn=1;$s_tot=0;
            foreach($date_summary as $d_summary) {
                $s_tot+=$d_summary['net'];
            ?> 
            <tr>
                <td><?php echo $s_sn++; ?></td>
                <td><?php echo $d_summary['product']; ?></td>
                <td><?php echo $d_summary['qty']; ?></td>
                <td style="text-align: right;"><?php echo $d_summary['rate']; ?></td>
                <td style="text-align: right;"><?php echo $d_summary['amt']; ?></td>
                <td style="text-align: right;"><?php echo $d_summary['disc']; ?></td>
                <td style="text-align: right;"><?php echo $d_summary['gst']; ?></td>
                <td style="text-align: right;"><?php echo $d_summary['net']; ?></td>
            </tr>
            <?php } ?>
            <tr>
                <th colspan="7" style="text-align: left;">टोटल : </th>
                <th colspan="1" style="text-align: right;"><?php echo $s_tot; ?></th>
            </tr>
        </table>
        <br/>
        <?php } ?>
        <?php } ?>      
	</body>
</html>