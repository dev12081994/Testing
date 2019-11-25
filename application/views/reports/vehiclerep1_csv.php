<!DOCTYPE html>
<html>
<head>
	<title>Sales Report</title>
</head>
<body>
	<table border="1">
        <thead>            
            <tr>
                <td colspan="15" style="background:#330099;color:#ffffff;font-size:16px;">
                    <center>
                        <strong>Date Wise Vehicle Report</strong>                       
                    </center>
                 </td>
            </tr>
            <tr>
                <th>S.N.</th>
                <th>Date</th>
                <th>Vehicle</th>
                <th>Number</th>
                <th>Driver</th>
                <th>Status</th>
                <th>Meter Start</th>
                <th>Meter Stop</th>
                <th>Customer</th>
                <th>From</th>
                <th>To</th>
                <th>Work</th>
                <th>Fare</th>
                <th>Expense</th>
                <th>Remark</th>                                 
            </tr>
        </thead>
        <tbody>
            <?php 
            $sn=1;$tot_amt=0;$tot_exp=0;
            if(count($vehicle_data) < 1)
            {
                echo "<tr><td colspan='15' style='color:red'><center>No Record Found</center></td></tr>";
            }
            else {
          
            foreach($vehicle_data as $value){ 
                $tot_amt+=  $value['vrun_fareamt'];
                $tot_exp+=  $value['expense'];
            ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo date('d-m-Y',strtotime($value['vrun_date'])); ?></td>
                <td><?php echo $value['vt_name']; ?></td>
                <td><?php echo strtoupper($value['v_num']); ?></td>
                <td><?php echo str_replace('  ',' ',$value['driver']); ?></td>
                <td><?php if($value['vrun_runstatus']==1)echo 'Running';else echo 'Stopped'; ?></td>
                <td><?php echo $value['vrun_meterstart']; ?></td>
                <td><?php echo $value['vrun_meterstop']; ?></td>
                <td><?php echo str_replace('  ',' ',$value['customer']); ?></td>
                <td><?php echo $value['vrun_from']; ?></td>
                <td><?php echo $value['vrun_to']; ?></td>
                <td><?php echo $value['vrun_work']; ?></td>
                <td style="text-align: right;"><?php echo $value['vrun_fareamt']; ?></td>
                <td style="text-align: right;"><?php echo $value['expense']; ?></td>
                <td><?php echo $value['vrun_remark']; ?></td>                   
            </tr>
            <?php } } ?> 
            <tr>
                <th colspan="12" style="text-align: left;">Total : </th>
                <th colspan="1" style="text-align: right;"><?php echo $tot_amt; ?></th>
                <th colspan="1" style="text-align: right;"><?php echo $tot_exp; ?></th>
                <th colspan="1">&nbsp;</th>
            </tr>
        </tbody>
     </table>
	</body>
</html>