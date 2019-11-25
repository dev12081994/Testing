<!DOCTYPE html>
<html>
<head>
	<title>Sales Report</title>
</head>
<body>	
    <?php
    $vnum='';
    foreach($vehicle_data as $key=>$value) 
    { 
        if($vnum!=$value['v_num']) 
        { 
            $vnum =$value['v_num'];
            $sn=1;
            $tot_amt=0;
            $tot_exp=0;
            ?>
            <br/>
            <table border="1">
                <tr>
                    <td colspan="12" style="background:#330099;color:#ffffff;font-size:16px;">
                        <center><strong>गाड़ी नंबर  : <?php echo strtoupper($vnum); ?></strong></center>
                     </td>
                </tr>
                <tr>
                    <th>क्र. </th>
                    <th>दिनाँक </th>
                    <th>ड्राइवर </th>
                    <th>नाम</th>
                    <th>कार्य </th>
                    <th>कहाँ से </th>
                    <th>कहाँ तक </th>
                    <th>चालू </th>
                    <th>बंद </th>
                    <th>भाड़ा </th>
                    <th>खर्च </th>
                    <th>रिमार्क </th>                                 
                </tr>
            <?php
        } 
            $tot_amt+=$value['vrun_fareamt'];
            $tot_exp+=$value['expense'];
        ?>
        
                <tr>
                    <td><?php echo $sn++; ?></td>
                    <td><?php echo date('d-m-Y',strtotime($value['vrun_date'])); ?></td>                    
                    <td><?php echo ucwords(strtolower(str_replace('  ',' ',$value['driver']))); ?></td>
                    <td><?php echo ucwords(strtolower(str_replace('  ',' ',$value['customer']))); ?></td>
                    <td><?php echo ucwords(strtolower($value['vrun_work'])); ?></td>
                    <td><?php echo ucwords(strtolower($value['vrun_from'])); ?></td>
                    <td><?php echo ucwords(strtolower($value['vrun_to'])); ?></td>
                    <td><?php echo $value['vrun_meterstart']; ?></td>
                    <td><?php echo $value['vrun_meterstop']; ?></td>                    
                    <td style="text-align: right;"><?php echo $value['vrun_fareamt']; ?></td>
                    <td style="text-align: right;"><?php echo $value['expense']; ?></td>
                    <td><?php echo ucwords(strtolower($value['vrun_remark'])); ?></td>                   
                </tr>
            <?php if((count($vehicle_data) > ($key+1) && $vnum!=$vehicle_data[$key + 1 ]['v_num']) || count($vehicle_data) == ($key+1)) { ?> 
                <tr>
                    <th colspan="9" style="text-align: left;">टोटल भाड़ा  : </th>
                    <th colspan="1" style="text-align: right;"><?php echo $tot_amt; ?></th>
                    <th colspan="1" style="text-align: right;"><?php echo $tot_exp; ?></th>
                    <th colspan="1">&nbsp;</th>
                </tr>
            </table>
    <?php }  } ?> 
            
        </tbody>
     </table>
	</body>
</html>